<?php
session_start();
define('__PLUGIN_ROOT__', __DIR__);
class GmLogin_Action extends Typecho_Widget implements Widget_Interface_Do
{
    
    public $dir;
    public $plugin;
    public function __construct()
    {
        $this->dir = Typecho_Widget::widget('Widget_Options')->pluginUrl.'/GmLogin';
        $this->plugin = Typecho_Widget::widget('Widget_Options')->plugin('GmLogin');
    }
    
    public function action(){
        throw new Typecho_Exception(_t('故梦登陆注册插件！看到这段文字表示插件已安装'));
        exit();
    }
    
    public function login(){
        if($_GET['from']){
            if(!$this->plugin->login) Typecho_Widget::widget('Widget_Options')->response->redirect($_GET['from']);
            if(Typecho_Widget::widget('Widget_User')->hasLogin()) Typecho_Widget::widget('Widget_Options')->response->redirect($_GET['from']);
        }else{
            if(!$this->plugin->login) Typecho_Widget::widget('Widget_Options')->response->redirect('/');
            if(Typecho_Widget::widget('Widget_User')->hasLogin()) Typecho_Widget::widget('Widget_Options')->response->redirect('/');
        }
        exit(require __PLUGIN_ROOT__.'/lib/login.php');
    }
    
    public function register(){
        if($_GET['from']){
            if(!$this->plugin->login) Typecho_Widget::widget('Widget_Options')->response->redirect($_GET['from']);
            if(Typecho_Widget::widget('Widget_User')->hasLogin()) Typecho_Widget::widget('Widget_Options')->response->redirect($_GET['from']);
        }else{
            if(!$this->plugin->login) Typecho_Widget::widget('Widget_Options')->response->redirect('/');
            if(Typecho_Widget::widget('Widget_User')->hasLogin()) Typecho_Widget::widget('Widget_Options')->response->redirect('/');
        }
        exit(require __PLUGIN_ROOT__.'/lib/register.php');
    }
    
    public function forget(){
        if($_GET['from']){
            if(!$this->plugin->login) Typecho_Widget::widget('Widget_Options')->response->redirect($_GET['from']);
            if(Typecho_Widget::widget('Widget_User')->hasLogin()) Typecho_Widget::widget('Widget_Options')->response->redirect($_GET['from']);
        }else{
            if(!$this->plugin->login) Typecho_Widget::widget('Widget_Options')->response->redirect('/');
            if(Typecho_Widget::widget('Widget_User')->hasLogin()) Typecho_Widget::widget('Widget_Options')->response->redirect('/');
        }
        exit(require __PLUGIN_ROOT__.'/lib/forget.php');
    }
    
    public function api(){
        $action = $_POST['action'];
        switch ($action) {
            case 'code' :
                $this->geetest($_POST['info']);
                break;
            case 'login':
                $username = $_POST['username'];
                $password = $_POST['password'];
                if(!isset($username)) $this->json([
                    'code' => 0,
                    'msg' => '请输入用户名/邮箱'
                ]);
                if(!isset($password)) $this->json([
                    'code' => 0,
                    'msg' => '请输入密码'
                ]);
                sleep(1);
                if($this->plugin->gt == 1 && $this->plugin->gtid && $this->plugin->gtkey){
                    $geetest_challenge = $_POST['geetest_challenge'];
                    $geetest_validate = $_POST['geetest_validate'];
                    $geetest_seccode = $_POST['geetest_seccode'];
                    if(!$this->geetest('login',1,$geetest_challenge,$geetest_validate,$geetest_seccode)){
                        $this->json([
                            'code' => 0,
                            'msg' => '未验证或验证失效'
                        ]);
                    }
                }
                $db = Typecho_Db::get();
                $user = $db->select('uid','name','password')->from('table.users')->where('name = ?', $username)->limit(1);
                $mail = $db->select('uid','mail','password')->from('table.users')->where('mail = ?', $username)->limit(1);
                if(!$result = $db->fetchAll($user)){
                    if(!$result = $db->fetchAll($mail)){
                        $this->json([
                            'code' => 0,
                            'msg' => '账户或密码错误'
                        ]);
                    }
                }
                if ('$P$' == substr($result[0]['password'], 0, 3)) {
                    $hasher = new PasswordHash(8, true);
                    $hashValidate = $hasher->CheckPassword($password, $result[0]['password']);
                } else {
                    $hashValidate = Typecho_Common::hashValidate($password, $result[0]['password']);
                }
                if($hashValidate){
                    $this->SetLogin($result[0]['uid']);
                    $this->json([
                        'code' => 1,
                        'msg' => '登录成功'
                    ]);
                }else{
                    $this->json([
                        'code' => 0,
                        'msg' => '账户或密码错误'
                    ]);
                }
                break;

            case 'register':
                $nickname = $_POST['nickname'];
                $username = $_POST['username'];
                $email = $_POST['email'];
                $code = $_POST['code'];
                $password = $_POST['password'];
                $cpassword = $_POST['cpassword'];
                if(!isset($nickname)) $this->json([
                    'code' => 0,
                    'msg' => '请输入昵称'
                ]);
                if(!isset($username)) $this->json([
                    'code' => 0,
                    'msg' => '请输入用户名'
                ]);
                if(!isset($email)) $this->json([
                    'code' => 0,
                    'msg' => '请输入邮箱'
                ]);
                if(!isset($code)) $this->json([
                    'code' => 0,
                    'msg' => '请输入验证码'
                ]);
                if(!isset($password)) $this->json([
                    'code' => 0,
                    'msg' => '请输入密码'
                ]);
                if(!isset($cpassword)) $this->json([
                    'code' => 0,
                    'msg' => '请输入确认密码'
                ]);
                if($cpassword != $password) $this->json([
                    'code' => 0,
                    'msg' => '两次密码不一致'
                ]);
                if(mb_strlen($nickname,'UTF-8') > 10) $this->json([
                    'code' => 0,
                    'msg' => '昵称不能超过10个字符'
                ]);
                if(!preg_match('/^[A-Za-z][A-Za-z0-9]{3,31}$/',$username)) $this->json([
                    'code' => 0,
                    'msg' => '用户名必须由4-30位字母和数字组成'
                ]);
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $this->json([
                    'code' => 0,
                    'msg' => '请输入正确的邮箱地址'
                ]);
                if(mb_strlen($password,'UTF-8') < 6) $this->json([
                    'code' => 0,
                    'msg' => '密码不能少于6位'
                ]);
                sleep(1);
                $db = Typecho_Db::get();
                if($db->fetchAll($db->select('uid')->from('table.users')->where('screenName = ?', $nickname)->limit(1))){
                    $this->json([
                        'code' => 0,
                        'msg' => '昵称已被其它小伙伴使用'
                    ]);
                }
                if($db->fetchAll($db->select('uid')->from('table.users')->where('name = ?', $username)->limit(1))){
                    $this->json([
                        'code' => 0,
                        'msg' => '你输入的用户名已经被注册'
                    ]);
                }
                if($db->fetchAll($db->select('uid')->from('table.users')->where('mail = ?', $email)->limit(1))){
                    $this->json([
                        'code' => 0,
                        'msg' => '你输入的邮箱已经注册账号'
                    ]);
                }
                if($_SESSION["Gm_Reg_Code"] != $code || $_SESSION["Gm_Reg_Email"] != trim($email)){
                    $this->json([
                        'code' => 0,
                        'msg' => '验证码错误或已过期'
                    ]);
                }
                $hasher = new PasswordHash(8, true);
                $data = array(
                    'name' => $username,
                    'screenName' => $nickname,
                    'mail' => $email,
                    'password' => $hasher->HashPassword($password),
                    'created' => time(),
                    'group' => 'subscriber'
                );
                $result = Typecho_Widget::widget('Widget_Abstract_Users')->insert($data);
                if($result){
                    $_SESSION['Gm_Reg_Code'] = null;
                    $_SESSION['Gm_Reg_Email'] = null;
                    $this->SetLogin($result);
                    $this->json([
                        'code' => 1,
                        'msg' => '注册成功'
                    ]);
                }else{
                    $this->json([
                        'code' => 0,
                        'msg' => '服务器异常，请稍后重试'
                    ]);
                }
                break;

            case 'forget':
                $password = $_POST['password'];
                $cpassword = $_POST['cpassword'];
                $state = $_POST['state'];
                if(!isset($state)) $this->json([
                    'code' => 0,
                    'msg' => '非法访问'
                ]);
                if(!isset($password)) $this->json([
                    'code' => 0,
                    'msg' => '请输入密码'
                ]);
                if(!isset($cpassword)) $this->json([
                    'code' => 0,
                    'msg' => '请输入确认密码'
                ]);
                if($cpassword != $password) $this->json([
                    'code' => 0,
                    'msg' => '两次密码不一致'
                ]);
                if(mb_strlen($password,'UTF-8') < 6) $this->json([
                    'code' => 0,
                    'msg' => '密码不能少于6位'
                ]);
                sleep(1);
                $db = Typecho_Db::get();
                if(!$_SESSION["Gm_Forget_state"] || $_SESSION["Gm_Forget_state"] != $state){
                    $this->json([
                        'code' => 0,
                        'msg' => '验证码错误或已过期'
                    ]);
                }else if(!$uid = $_SESSION[$state]){
                    $_SESSION['Gm_Forget_state'] = null;
                    $this->json([
                        'code' => 0,
                        'msg' => '验证已失效'
                    ]);
                }else if(!$db->fetchAll($db->select('uid')->from('table.users')->where('uid = ?', $uid)->limit(1))){
                    $_SESSION['Gm_Forget_state'] = null;
                    $this->json([
                        'code' => 0,
                        'msg' => '用户不存在'.$uid
                    ]);
                }else{
                    $hasher = new PasswordHash(8, true);
                    $update = $db->update('table.users')->rows([
                        'password'=>$hasher->HashPassword($password)
                    ])->where('uid=?',$uid);
                    $updateRows = $db->query($update);
                    if($updateRows){
                        $_SESSION[$state] = null;
                        $this->SetLogin($uid);
                        $this->json([
                            'code' => 1,
                            'msg' => '设置新密码成功'
                        ]);
                    }else{
                        $this->json([
                            'code' => 0,
                            'msg' => '服务器异常，请稍后重试'
                        ]);
                    }
                }
                break;

            case 'forget_check':
                $code = $_POST['code'];
                $email = $_POST['email'];
                if(!isset($code)) $this->json([
                    'code' => 0,
                    'msg' => '请输入验证码'
                ]);
                if(!isset($email)) $this->json([
                    'code' => 0,
                    'msg' => '请输入邮箱'
                ]);
                sleep(1);
                $db = Typecho_Db::get();
                if(!$user = $db->fetchAll($db->select('uid')->from('table.users')->where('mail = ?', $email)->limit(1))){
                    $this->json([
                        'code' => 0,
                        'msg' => '你输入的邮箱未注册账号'
                    ]);
                }
                if($_SESSION["Gm_Forget_Code"] != $code || $_SESSION["Gm_Forget_email"] != $email){
                    $_SESSION['Gm_Forget_Code'] = null;
                    $_SESSION['Gm_Forget_email'] = null;
                    $this->json([
                        'code' => 0,
                        'msg' => '验证码错误或已过期'
                    ]);
                }else{
                    $_SESSION['Gm_Forget_Code'] = null;
                    $_SESSION['Gm_Forget_email'] = null;
                    $state = md5(rand(100000,999999).time().rand(100000,999999));
                    $_SESSION["Gm_Forget_state"] = $state;
                    $_SESSION[$state] = $user[0]['uid'];
                    $this->json([
                        'code' => 1,
                        'msg' => '验证码正确',
                        'state' => $_SESSION["Gm_Forget_state"]
                    ]);
                }
                break;
    
            case 'reg_code':
                $email = $_POST['email'];
                if(!isset($email)) $this->json([
                    'code' => 0,
                    'msg' => '请输入邮箱后发送验证码'
                ]);
                sleep(1);
                if($this->plugin->gt == 1 && $this->plugin->gtid && $this->plugin->gtkey){
                    $geetest_challenge = $_POST['geetest_challenge'];
                    $geetest_validate = $_POST['geetest_validate'];
                    $geetest_seccode = $_POST['geetest_seccode'];
                    if(!$this->geetest('reg_code',1,$geetest_challenge,$geetest_validate,$geetest_seccode)){
                        $this->json([
                            'code' => 0,
                            'msg' => '未验证或验证失效'
                        ]);
                    }
                }
                $db = Typecho_Db::get();
                if($db->fetchAll($db->select('uid')->from('table.users')->where('mail = ?', $email)->limit(1))){
                    $this->json([
                        'code' => 0,
                        'msg' => '你输入的邮箱已经注册账号'
                    ]);
                }else{
                    $code = rand(100000,999999);
                    $_SESSION["Gm_Reg_Code"] = $code;
                    $_SESSION["Gm_Reg_Email"] = $email;
                    $send = $this->send(trim($email),'注册验证','您正在进行注册操作，验证码是 '.$_SESSION["Gm_Reg_Code"]);
                    if($send['code'] == 1){
                        $this->json([
                            'code' => 1,
                            'msg' => '验证码已发送到您的邮箱'
                        ]);
                    }else{
                        $this->json([
                            'code' => 0,
                            'msg' => $send['msg']
                        ]);
                    }
                }
                break;

            case 'forget_code':
                $email = $_POST['email'];
                if(!isset($email)) $this->json([
                    'code' => 0,
                    'msg' => '请输入邮箱后发送验证码'
                ]);
                sleep(1);
                if($this->plugin->gt == 1 && $this->plugin->gtid && $this->plugin->gtkey){
                    $geetest_challenge = $_POST['geetest_challenge'];
                    $geetest_validate = $_POST['geetest_validate'];
                    $geetest_seccode = $_POST['geetest_seccode'];
                    if(!$this->geetest('forget_code',1,$geetest_challenge,$geetest_validate,$geetest_seccode)){
                        $this->json([
                            'code' => 0,
                            'msg' => '未验证或验证失效'
                        ]);
                    }
                }
                $db = Typecho_Db::get();
                if(!$db->fetchAll($db->select('uid')->from('table.users')->where('mail = ?', $email)->limit(1))){
                    $this->json([
                        'code' => 0,
                        'msg' => '你输入的邮箱未注册账号'
                    ]);
                }else{
                    $code = rand(100000,999999);
                    $_SESSION["Gm_Forget_Code"] = $code;
                    $_SESSION["Gm_Forget_email"] = $email;
                    $send = $this->send(trim($email),'重置密码','您正在进行重置密码操作，验证码是 '.$_SESSION["Gm_Forget_Code"]);
                    if($send['code'] == 1){
                        $this->json([
                            'code' => 1,
                            'msg' => '验证码已发送到您的邮箱'
                        ]);
                    }else{
                        $this->json([
                            'code' => 0,
                            'msg' => $send['msg']
                        ]);
                    }
                }
                break;
            case 'url':
                $site = $_POST['site'];
                $from = $_POST['from'];
                if($site){
                    if($this->plugin->$site){
                        $iconfile = __PLUGIN_ROOT__."/icon.json";
                        if (!file_exists($iconfile)) {
                            $this->json([
                                'code' => 0,
                                'msg' => '图标文件不存在，请检查插件目录是否有icon.json文件',
                            ]);
                        }
                        $str = file_get_contents($iconfile);
                        $arr = json_decode($str,true);
                        $Is = false;
                        $num = null;
                        for ($i = 0; $i < count($arr); $i++) {
                            if($arr[$i]['site'] == $site){
                                $Is = true;
                                $num = $i;
                                break;
                            }
                        }
                        if($Is){
                            $_SESSION['from'] = urlencode($from);
                            $callback = urlencode(Typecho_Common::url('user/callback', Helper::options()->index));
                            $this->json([
                                'code' => 1,
                                'msg' => 'success',
                                'url' => 'https://sso.gmit.vip/'.$site.'/redirect?redirect_url='.$callback,
                                'width' => $arr[$num]['width'],
                                'height' => $arr[$num]['height'],
                            ]);
                        }else {
                            $this->json([
                                'code' => 0,
                                'msg' => '不存在该第三方登陆',
                            ]);
                        }
                    }else{
                        $this->json([
                            'code' => 0,
                            'msg' => '未开通此第三方登陆'
                        ]);
                    }
                }else{
                    $this->json([
                        'code' => 0,
                        'msg' => 'site null'
                    ]);
                }
                break;
            case 'icon':
                $iconfile = __PLUGIN_ROOT__."/icon.json";
                if (!file_exists($iconfile)) {
                    $this->json([
                        'code' => 0,
                        'msg' => '图标文件不存在，请检查插件目录是否有icon.json文件',
                    ]);
                }
                $str = file_get_contents($iconfile);
                $site = json_decode($str,true);
                $data = [];
                for ($i = 0; $i < count($site); $i++) {
                    $c = $site[$i]['site'];
                    $plugin = Typecho_Widget::widget('Widget_Options')->plugin('GmLogin');
                    if(!@$plugin->$c){
                        continue;
                    }
                    $data[] = $site[$i];
                }
                $this->json([
                    'code' => 1,
                    'msg' => 'success',
                    'data' => $data
                ]);
                break;
            case 'new_register':
                $nickname = $_POST['nickname'];
                $username = $_POST['username'];
                $email = $_POST['email'];
                $code = $_POST['code'];
                $password = $_POST['password'];
                $cpassword = $_POST['cpassword'];
                $state = $_POST['state'];
                if(!$state) $this->json([
                    'code' => 0,
                    'msg' => '非法访问'
                ]);
                if($state != $_SESSION['Gm_new_state']) $this->json([
                    'code' => 0,
                    'msg' => '页面已经过期，请重新授权'
                ]);
                $app = $_SESSION['Gm_new_app'];
                $openid = $_SESSION['Gm_new_openid'];
                if(!$app) $this->json([
                    'code' => 0,
                    'msg' => '获取授权方式失败，授权已过期'
                ]);
                if(!$openid) $this->json([
                    'code' => 0,
                    'msg' => '获取授权信息失败，授权已过期'
                ]);
                if(!isset($nickname)) $this->json([
                    'code' => 0,
                    'msg' => '请输入昵称'
                ]);
                if(!isset($username)) $this->json([
                    'code' => 0,
                    'msg' => '请输入用户名'
                ]);
                if(!isset($email)) $this->json([
                    'code' => 0,
                    'msg' => '请输入邮箱'
                ]);
                if(!isset($code)) $this->json([
                    'code' => 0,
                    'msg' => '请输入验证码'
                ]);
                if(!isset($password)) $this->json([
                    'code' => 0,
                    'msg' => '请输入密码'
                ]);
                if(!isset($cpassword)) $this->json([
                    'code' => 0,
                    'msg' => '请输入确认密码'
                ]);
                if($cpassword != $password) $this->json([
                    'code' => 0,
                    'msg' => '两次密码不一致'
                ]);
                if(mb_strlen($nickname,'UTF-8') > 10) $this->json([
                    'code' => 0,
                    'msg' => '昵称不能超过10个字符'
                ]);
                if(!preg_match('/^[A-Za-z][A-Za-z0-9]{3,31}$/',$username)) $this->json([
                    'code' => 0,
                    'msg' => '用户名必须由4-30位字母和数字组成'
                ]);
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $this->json([
                    'code' => 0,
                    'msg' => '请输入正确的邮箱地址'
                ]);
                if(mb_strlen($password,'UTF-8') < 6) $this->json([
                    'code' => 0,
                    'msg' => '密码不能少于6位'
                ]);
                sleep(1);
                $db = Typecho_Db::get();
                if($db->fetchAll($db->select('uid')->from('table.users')->where('screenName = ?', $nickname)->limit(1))){
                    $this->json([
                        'code' => 0,
                        'msg' => '昵称已被其它小伙伴使用'
                    ]);
                }
                if($db->fetchAll($db->select('uid')->from('table.users')->where('name = ?', $username)->limit(1))){
                    $this->json([
                        'code' => 0,
                        'msg' => '你输入的用户名已经被注册'
                    ]);
                }
                if($db->fetchAll($db->select('uid')->from('table.users')->where('mail = ?', $email)->limit(1))){
                    $this->json([
                        'code' => 0,
                        'msg' => '你输入的邮箱已经注册账号'
                    ]);
                }
                if($_SESSION["Gm_Reg_Code"] != $code || $_SESSION["Gm_Reg_Email"] != trim($email)){
                    $this->json([
                        'code' => 0,
                        'msg' => '验证码错误或已过期'
                    ]);
                }
                $hasher = new PasswordHash(8, true);
                $data = array(
                    'name' => $username,
                    'screenName' => $nickname,
                    'mail' => $email,
                    'password' => $hasher->HashPassword($password),
                    'created' => time(),
                    'group' => 'subscriber'
                );
                $result = Typecho_Widget::widget('Widget_Abstract_Users')->insert($data);
                $addGm = array(
                    'uid'=> $result,
                    'app'=> $app,
                    'openid' => $openid,
                    'time' => time(),
                );
                if($result){
                    $insert = $db->insert('table.gm_oauth')->rows($addGm);
                    $insertId = $db->query($insert);
                    if($insertId){
                        $_SESSION['Gm_Reg_Code'] = null;
                        $_SESSION['Gm_Reg_Email'] = null;
                        $_SESSION['Gm_new_state'] = null;
                        $_SESSION['Gm_new_nickname'] = null;
                        $_SESSION['Gm_new_email'] = null;
                        $_SESSION['Gm_new_app'] = null;
                        $_SESSION['Gm_new_openid'] = null;
                        $this->SetLogin($result);
                        $this->json([
                            'code' => 1,
                            'msg' => '注册并绑定成功'
                        ]);
                    }else{
                        $this->json([
                            'code' => 0,
                            'msg' => '服务器异常，请稍后重试'
                        ]);
                    }
                }else{
                    $this->json([
                        'code' => 0,
                        'msg' => '服务器异常，请稍后重试'
                    ]);
                }
                break;
            case 'new_login':
                $username = $_POST['username'];
                $password = $_POST['password'];
                $state = $_POST['state'];
                if(!$state) $this->json([
                    'code' => 0,
                    'msg' => '非法访问'
                ]);
                if($state != $_SESSION['Gm_new_state']) $this->json([
                    'code' => 0,
                    'msg' => '页面已经过期，请重新授权'
                ]);
                $app = $_SESSION['Gm_new_app'];
                $openid = $_SESSION['Gm_new_openid'];
                if(!$app) $this->json([
                    'code' => 0,
                    'msg' => '获取授权方式失败，授权已过期'
                ]);
                if(!$openid) $this->json([
                    'code' => 0,
                    'msg' => '获取授权信息失败，授权已过期'
                ]);
                if(!isset($username)) $this->json([
                    'code' => 0,
                    'msg' => '请输入用户名/邮箱'
                ]);
                if(!isset($password)) $this->json([
                    'code' => 0,
                    'msg' => '请输入密码'
                ]);
                sleep(1);
                if($this->plugin->gt == 1 && $this->plugin->gtid && $this->plugin->gtkey){
                    $geetest_challenge = $_POST['geetest_challenge'];
                    $geetest_validate = $_POST['geetest_validate'];
                    $geetest_seccode = $_POST['geetest_seccode'];
                    if(!$this->geetest('login',1,$geetest_challenge,$geetest_validate,$geetest_seccode)){
                        $this->json([
                            'code' => 0,
                            'msg' => '未验证或验证失效'
                        ]);
                    }
                }
                $db = Typecho_Db::get();
                $user = $db->select('uid','name','password')->from('table.users')->where('name = ?', $username)->limit(1);
                $mail = $db->select('uid','mail','password')->from('table.users')->where('mail = ?', $username)->limit(1);
                if(!$result = $db->fetchAll($user)){
                    if(!$result = $db->fetchAll($mail)){
                        $this->json([
                            'code' => 0,
                            'msg' => '账户或密码错误'
                        ]);
                    }
                }
                if ('$P$' == substr($result[0]['password'], 0, 3)) {
                    $hasher = new PasswordHash(8, true);
                    $hashValidate = $hasher->CheckPassword($password, $result[0]['password']);
                } else {
                    $hashValidate = Typecho_Common::hashValidate($password, $result[0]['password']);
                }
                if($hashValidate){
                    $IsUser = $db->fetchAll($db->select()->from('table.gm_oauth')->where('uid = ?',$result[0]['uid'])->where('app = ?',1));
                    if(!count($IsUser)) $this->json([
                        'code' => 0,
                        'msg' => '该账号被其它快捷授权账号绑定'
                    ]);
                    $addGm = array(
                        'uid'=> $result[0]['uid'],
                        'app'=> $app,
                        'openid' => $openid,
                        'time' => time(),
                    );
                    $insert = $db->insert('table.gm_oauth')->rows($addGm);
                    $insertId = $db->query($insert);
                    if($insertId){
                        $_SESSION['Gm_new_state'] = null;
                        $_SESSION['Gm_new_nickname'] = null;
                        $_SESSION['Gm_new_email'] = null;
                        $_SESSION['Gm_new_app'] = null;
                        $_SESSION['Gm_new_openid'] = null;
                        $this->SetLogin($result[0]['uid']);
                        $this->json([
                            'code' => 1,
                            'msg' => '注册并绑定成功'
                        ]);
                    }else{
                        $this->json([
                            'code' => 0,
                            'msg' => '服务器异常，请稍后重试'
                        ]);
                    }
                }else{
                    $this->json([
                        'code' => 0,
                        'msg' => '账户或密码错误'
                    ]);
                }
                break;
            default:
                $this->json([
                    'code' => 0,
                    'msg' => 'api error'
                ]);
                break;
        }
    }

    public function bangding(){
        $code = $_GET['code'];
        if($code){
            $db = Typecho_Db::get();
            Typecho_Widget::widget('Widget_User')->to($user);
            Typecho_Widget::widget('Widget_Options')->to($options);
            $curl = curl_init();
        	curl_setopt($curl, CURLOPT_URL, 'https://sso.gmit.vip/api');
        	curl_setopt($curl, CURLOPT_HEADER, 0);
        	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        	curl_setopt($curl, CURLOPT_POST, 1);
        	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        	curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        	curl_setopt($curl, CURLOPT_POSTFIELDS, [
        	    'code' => $code,
        	    'action' => 'info'
        	]);
        	$info = curl_exec($curl);
        	curl_close($curl);
        	$info = json_decode($info,true);
        	if($info['code'] == 1){
        	    $query = $db->select()->from('table.gm_oauth')->where('openid = ?',$info['data']['openid']); 
                $IsUser = $db->fetchAll($query);
                if(count($IsUser)){
                    echo '<script>alert("该第三方账号已被其它账号绑定");window.location.href="'.$options->adminUrl.'extending.php?panel=GmLogin/console.php";</script>';
                }else{
                    $addGm = array(
                        'uid'=> $user->uid,
                        'app'=> $info['data']['app'],
                        'openid' => $info['data']['openid'],
                        'time' => time(),
                    );
                    $insert = $db->insert('table.gm_oauth')->rows($addGm);
                    $insertId = $db->query($insert);
                    if($insertId){
                        echo '<script>alert("绑定成功");window.location.href="'.$options->adminUrl.'extending.php?panel=GmLogin/console.php";</script>';
                    }else{
                        echo '<script>alert("插件内部错误，请联系开发者");window.location.href="'.$options->adminUrl.'extending.php?panel=GmLogin/console.php";</script>';
                    }
                }
        	}else{
        	    echo '<script>alert("'.$info['msg'].'");window.location.href="'.$options->adminUrl.'extending.php?panel=GmLogin/console.php";</script>';
        	}
        }else{
            echo '<script>alert("非法访问");window.location.href="/";</script>';
        }
    }


    public function callback(){
        $db = Typecho_Db::get();
        $code = @$_GET['code'];
        if($code){
            $curl = curl_init();
        	curl_setopt($curl, CURLOPT_URL, 'https://sso.gmit.vip/api');
        	curl_setopt($curl, CURLOPT_HEADER, 0);
        	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        	curl_setopt($curl, CURLOPT_POST, 1);
        	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        	curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        	curl_setopt($curl, CURLOPT_POSTFIELDS, [
        	    'code' => $code,
        	    'action' => 'info'
        	]);
        	$info = curl_exec($curl);
        	curl_close($curl);
        	$info = json_decode(trim($info,chr(239).chr(187).chr(191)),true);
        	if(@$info['code'] == 1){
        	    $query = $db->select()->from('table.gm_oauth')->where('openid = ?',$info['data']['openid']); 
                $IsUser = $db->fetchAll($query);
                if(count($IsUser)){
                    $this->SetLogin($IsUser[0]['uid']);
                    $this->Ok();
                }else{
                    $state = date('Ymdhis').mt_rand(10000,99999);
                    $_SESSION['Gm_new_state'] = $state;
                    $_SESSION['Gm_new_nickname'] = $info['data']['nickname'];
                    $_SESSION['Gm_new_email'] = $info['data']['email'];
                    $_SESSION['Gm_new_app'] = $info['data']['app'];
                    $_SESSION['Gm_new_openid'] = $info['data']['openid'];
                    $from = urldecode($_SESSION['from']);
                    if(!$from){
                        $from = Typecho_Common::url('/', Helper::options()->index);
                    }
                    $from = urlencode($from);
                    Typecho_Widget::widget('Widget_Options')->response->redirect(Typecho_Common::url('user/new?state='.$state.'&from='.$from, Helper::options()->index));
                    /*$hasher = new PasswordHash(8, true);
                    $UserName = $this->UserName();
                    $data = array(
                        'name' => $UserName,
                        'screenName' => $info['data']['nickname'],
                        'password' => $hasher->HashPassword($UserName),
                        'created' => time(),
                        'group' => 'subscriber'
                    );
                    $add = Typecho_Widget::widget('Widget_Abstract_Users')->insert($data);
                    $addGm = array(
                        'uid'=> $add,
                        'app'=> $info['data']['app'],
                        'openid' => $info['data']['openid'],
                        'time' => time(),
                    );
                    if($add){
                        $insert = $db->insert('table.gm_oauth')->rows($addGm);
                        $insertId = $db->query($insert);
                        if($insertId){
                            $this->SetLogin($add);
                            $this->Ok();
                        }else{
                            throw new Typecho_Exception(_t('内部错误'));
                            exit();
                        }
                    }else{
                        throw new Typecho_Exception(_t('内部错误'));
                        exit();
                    }*/
                }
        	}else{
                $this->error($info['msg']);
        	}
        }else {
            $this->error('授权回调代码错误！');
        }
    }

    public function new(){
        $state = $_GET['state'];
        if(!$state) $this->error('非法访问！');
        if($state != $_SESSION['Gm_new_state']) $this->error('页面已过期！请重新授权');
        $nickname = $_SESSION['Gm_new_nickname'];
        $email = $_SESSION['Gm_new_email'];
        exit(require __PLUGIN_ROOT__.'/lib/new.php');
    }

    protected function json($arr){
        header("Content-type:application/json;charset=utf-8");
        print(json_encode($arr,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
        exit();
    }

    //设置登录
    protected function SetLogin($uid, $expire = 30243600) {
        $db = Typecho_Db::get();
        Typecho_Widget::widget('Widget_User')->simpleLogin($uid);
        $authCode = function_exists('openssl_random_pseudo_bytes') ?
                bin2hex(openssl_random_pseudo_bytes(16)) : sha1(Typecho_Common::randString(20));
        Typecho_Cookie::set('__typecho_uid', $uid, time() + $expire);
        Typecho_Cookie::set('__typecho_authCode', Typecho_Common::hash($authCode), time() + $expire);
        //更新最后登录时间以及验证码
        $db->query($db->update('table.users')->expression('logged', 'activated')->rows(array('authCode' => $authCode))->where('uid = ?', $uid));
    }

    //邮件发送
    protected function send($email,$title,$body) {
        require __PLUGIN_ROOT__.'/lib/Email/class.phpmailer.php';
        require __PLUGIN_ROOT__.'/lib/Email/class.smtp.php';
        $mail = new PHPMailer(true);// 传递“true”可启用异常
        //邮箱配置
        $mail->CharSet = 'UTF-8';
        $mail->SMTPDebug = 0;// 启用详细调试输出
        $mail->isSMTP();// 将Mailer设置为使用SMTP
        $mail->Host = $this->plugin->smtp_host;  // SMTP服务器
        $mail->SMTPAuth = true;// 启用SMTP身份验证
        $mail->Username = $this->plugin->smtp_user; // 邮箱用户名
        $mail->Password = $this->plugin->smtp_pass;//邮箱密码
        $mail->SMTPSecure = $this->plugin->smtp_secure;//加密方式
        $mail->Port = $this->plugin->smtp_port; //端口

        //接收人
        $mail->setFrom($this->plugin->smtp_user, trim(Helper::options()->title));
        $mail->addReplyTo($this->plugin->smtp_user, trim(Helper::options()->title)); //回复地址
        $mail->addAddress($email);               // 可以只传邮箱地
        //内容
        $mail->isHTML(true);                 // 设置HTML
        $mail->Subject = trim(Helper::options()->title).' - '.$title;
        $mail->Body    = $body;
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        if (!$mail->send()) {
            return ['code'=>0,'msg'=>'Mailer Error: ' . $mail->ErrorInfo];
            // echo 'Message could not be sent.';
            // echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return ['code'=>1,'msg'=>ucwords('邮件发送成功')];
            //echo ucwords('Message has been sent');
        }
    }

    //用户名生成
    private function UserName(){
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        $UserName = "";
        for ( $i = 0; $i < 6; $i++ ){
            $UserName .= @$chars[mt_rand(0, strlen($chars))];
        }
        return strtoupper(base_convert(time() - 1420070400, 10, 36)).$UserName;
    }
    //及验获取
    protected function geetest($user_id = '',$state = 0,$geetest_challenge = '', $geetest_validate = '',$geetest_seccode = ''){
        require __PLUGIN_ROOT__.'/lib/Geetest/class.geetestlib.php'; //及验
        $GtSdk = new GeetestLib($this->plugin->gtid, $this->plugin->gtkey);
        $data = array(
    		"user_id" => $user_id, # 网站用户id
    		"client_type" => "web", #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
    		"ip_address" => $_SERVER['REMOTE_ADDR'] # 请在此处传输用户请求验证时所携带的IP
    	);
        if(!$state){
            header('Content-type:application/json; charset=utf-8');
            $status = $GtSdk->pre_process($data, 1);
            $_SESSION['gtserver_'.$data['user_id'].'_'.$data['ip_address']] = $status;
            exit($GtSdk->get_response_str());
        }else{
            if ($_SESSION['gtserver_'.$data['user_id'].'_'.$data['ip_address']] == 1) {   //服务器正常
                $result = $GtSdk->success_validate($geetest_challenge, $geetest_validate,$geetest_seccode, $data);
                if ($result) {
                    return true;
                } else{
                    return false;
                }
            }else{  //服务器宕机,走failback模式
                if ($GtSdk->fail_validate($geetest_challenge, $geetest_validate,$geetest_seccode)) {
                    return true;
                }else{
                    return false;
                }
            }
        }
    }

    protected function error($msg = ''){
        exit(require __PLUGIN_ROOT__.'/lib/error.php');
    }
    //快捷登录完成界面
    protected function Ok(){
        $from = urldecode($_SESSION['from']);
        if(!$from){
            $from = Typecho_Common::url('/', Helper::options()->index);
        }
        print '
<!DOCTYPE html>
<html>
  <head>
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>加载中，请稍候…</title>
    <meta name="theme-color" content="#0C4475" />
    <link rel="shortcut icon" href="/favicon.ico">
    <style type="text/css">@charset "UTF-8";html,body{margin:0;padding:0;width:100%;height:100%;background-color:#DB4D6D;display:flex;justify-content:center;align-items:center;font-family:"微軟正黑體";}.monster{width:110px;height:110px;background-color:#E55A54;border-radius:20px;position:relative;display:flex;justify-content:center;align-items:center;flex-direction:column;cursor:pointer;margin:10px;box-shadow:0px 10px 20px rgba(0,0,0,0.2);position:relative;animation:jumping 0.8s infinite alternate;}.monster .eye{width:40%;height:40%;border-radius:50%;background-color:#fff;display:flex;justify-content:center;align-items:center;}.monster .eyeball{width:50%;height:50%;border-radius:50%;background-color:#0C4475;}.monster .mouth{width:32%;height:12px;border-radius:12px;background-color:white;margin-top:15%;}.monster:before,.monster:after{content:"";display:block;width:20%;height:10px;position:absolute;left:50%;top:-10px;background-color:#fff;border-radius:10px;}.monster:before{transform:translateX(-70%) rotate(45deg);}.monster:after{transform:translateX(-30%) rotate(-45deg);}.monster,.monster *{transition:0.5s;}@keyframes jumping{50%{top:0;box-shadow:0px 10px 20px rgba(0,0,0,0.2);}100%{top:-50px;box-shadow:0px 120px 50px rgba(0,0,0,0.2);}}@keyframes eyemove{0%,10%{transform:translate(50%);}90%,100%{transform:translate(-50%);}}.monster .eyeball{animation:eyemove 1.6s infinite alternate;}h2{color:white;font-size:20px;margin:20px 0;}.pageLoading{position:fixed;width:100%;height:100%;left:0;top:0;display:flex;justify-content:center;align-items:center;background-color:#0C4475;flex-direction:column;transition:opacity 0.5s 0.5s;}.loading{width:200px;height:8px;margin-top:0px;border-radius:5px;background-color:#fff;overflow:hidden;transition:0.5s;}.loading .bar{background-color:#E55A54;width:0%;height:100%;}</style>
  </head>
  <body>
    <div class="pageLoading">
      <div class="monster">
        <div class="eye">
          <div class="eyeball"></div>
        </div>
        <div class="mouth"></div>
      </div><h2>页面加载中...</h2>
    </div>
    <script>  
        setTimeout(function(){
            top.location = "'.$from.'";
        }, 1000);
        setTimeout(function(){
            if(window.opener.location.href){
                window.opener.location.reload(true);self.close();
            }else{
                window.location.replace="'.$from.'";
            }
        }, 500);
        setTimeout(function(){window.opener=null;window.close();}, 50000);
    </script> 
  </body>
</html>';
    }
}
