<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
define('__PLUGIN_ROOT__', __DIR__);
/**
 * <strong style="color:#000000;">故梦登陆注册插件</strong>
 * 
 * @package GmLogin
 * @author Gm
 * @version 1.1.1
 * @update: 2021-11-17
 * @link //www.gmit.vip
 */
class GmLogin_Plugin implements Typecho_Plugin_Interface
{
    public static $panel = 'GmLogin/console.php';
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Helper::addPanel(1, self::$panel, _t('快捷登录绑定'), _t('账号快捷登录绑定'), 'subscriber');
        Typecho_Plugin::factory('Widget_Archive')->header = array(__CLASS__, 'header');
        Typecho_Plugin::factory('Widget_Archive')->footer = array(__CLASS__, 'footer');
        Helper::addRoute('Gm_login', '/user/login', 'GmLogin_Action', 'login');
        Helper::addRoute('Gm_register', '/user/register', 'GmLogin_Action', 'register');
        Helper::addRoute('Gm_forget', '/user/forget', 'GmLogin_Action', 'forget');
        Helper::addRoute('Gm_api', '/user/api', 'GmLogin_Action', 'api');
        Helper::addRoute('Gm_callback', '/user/callback', 'GmLogin_Action', 'callback');
        Helper::addRoute('Gm_bangding', '/user/bangding', 'GmLogin_Action', 'bangding');
        Helper::addRoute('Gm_new', '/user/new', 'GmLogin_Action', 'new');
        try {
            $db = Typecho_Db::get();
            $prefix = $db->getPrefix();
            $sql = "CREATE TABLE `{$prefix}gm_oauth` (
  `id` int(100) NOT NULL,
  `app` text NOT NULL,
  `uid` int(255) NOT NULL,
  `openid` text NOT NULL,
  `time` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
ALTER TABLE `{$prefix}gm_oauth` CHANGE `id` `id` INT(100) NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);";
            $db->query($sql);
            return '插件安装成功!数据库安装成功';
        } catch (Typecho_Db_Exception $e) {
            if ('42S01' == $e->getCode()) {
                return '插件安装成功!数据库已存在!';
            }
        }
    }
    
    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){
        Helper::removePanel(1, self::$panel);
        Helper::removeRoute('Gm_login');
        Helper::removeRoute('Gm_register');
        Helper::removeRoute('Gm_forget');
        Helper::removeRoute('Gm_api');
        Helper::removeRoute('Gm_callback');
        Helper::removeRoute('Gm_bangding');
        Helper::removeRoute('Gm_new');
        return '插件卸载成功';
    }
    
    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        $title = new Typecho_Widget_Helper_Layout('div', array('class=' => 'typecho-page-title'));
        $title->html('<h2>功能开关</h2>');
        $form->addItem($title);
        $logo = new Typecho_Widget_Helper_Form_Element_Text('logo', null, 'https://cdn.gmit.vip/logo.png', _t('logo地址'), '登录注册界面的logo链接');
        $form->addInput($logo);
        $ico = new Typecho_Widget_Helper_Form_Element_Text('ico', null, 'https://cdn.gmit.vip/ico.png', _t('ico地址'), '登录注册界面的ico链接');
        $form->addInput($ico);
        $register = new Typecho_Widget_Helper_Form_Element_Radio('register', array('1' => _t('开启'), '0' => _t('关闭')), '1',_t('注册'), _t('是否开启注册功能 访问地址 <a target="_blank" href="'.Typecho_Common::url('user/register', Helper::options()->index).'">'.Typecho_Common::url('user/register', Helper::options()->index).'</a>'));
            $form->addInput($register);
        $login = new Typecho_Widget_Helper_Form_Element_Radio('login', array('1' => _t('开启'), '0' => _t('关闭')), '1', _t('登陆'),_t('是否开启登陆功能 访问地址 <a target="_blank" href="'.Typecho_Common::url('user/login', Helper::options()->index).'">'.Typecho_Common::url('user/login', Helper::options()->index).'</a>'));
            $form->addInput($login);
        $forget = new Typecho_Widget_Helper_Form_Element_Radio('forget', array('1' => _t('开启'), '0' => _t('关闭')), '0', _t('找回密码'),_t('需要配置邮箱（未配置无法发送验证码） 访问地址 <a target="_blank" href="'.Typecho_Common::url('user/forget', Helper::options()->index).'">'.Typecho_Common::url('user/forget', Helper::options()->index).'</a>'));
            $form->addInput($forget);
        $title = new Typecho_Widget_Helper_Layout('div', array('class=' => 'typecho-page-title'));
        $title->html('<h2>第三方登陆</h2>');
        $form->addItem($title);
        $title = new Typecho_Widget_Helper_Layout('div', array('class=' => 'typecho-page-title'));
        $oauth = new Typecho_Widget_Helper_Form_Element_Radio('oauth', array('1' => _t('开启'), '0' => _t('关闭')), '0', _t('第三方登陆'),_t('此第三方登陆免申请开发者应用，无需配置开发者应用 了解详细:<a target="_blank" href="https://auth.gmit.vip/">https://auth.gmit.vip/</a>'));
        $form->addInput($oauth);
        $iconfile = __PLUGIN_ROOT__."/icon.json";
        $icon = @fopen($iconfile, "r") or die("登陆按钮图标文件丢失!");
        $site = json_decode(fread($icon,filesize($iconfile)),true);
        fclose($icon);
        for ($i = 0; $i < count($site); $i++) {
            $radio = new Typecho_Widget_Helper_Form_Element_Radio($site[$i]['site'], array('1' => _t('开启'), '0' => _t('关闭')), '0', _t($site[$i]['name']));
            $form->addInput($radio);
        }
        $title->html('<h2>极验配置</h2>');
        $form->addItem($title);
        $gt = new Typecho_Widget_Helper_Form_Element_Radio('gt', array('1' => _t('开启'), '0' => _t('关闭')), '0', _t('极验验证'), _t('是否开启极验证码'));
            $form->addInput($gt);
        $gtid = new Typecho_Widget_Helper_Form_Element_Text('gtid', NULL, NULL,
            _t('极验id'),
            _t('极验注册地址 <a target="_blank" href="https://www.geetest.com/">https://www.geetest.com/</a>'));
        $form->addInput($gtid);
        $gtkey = new Typecho_Widget_Helper_Form_Element_Text('gtkey', NULL, NULL,
            _t('极验key'),
            _t('此页面获得 <a target="_blank" href="https://gtaccount.geetest.com/sensebot/overview">https://gtaccount.geetest.com/sensebot/overview</a>'));
        $form->addInput($gtkey);
        
        $title = new Typecho_Widget_Helper_Layout('div', array('class=' => 'typecho-page-title'));
        $title->html('<h2>SMTP邮件发送设置</h2>');
        $form->addItem($title);
        // SMTP地址
        $smtp_host = new Typecho_Widget_Helper_Form_Element_Text('smtp_host', NULL, NULL, _t('SMTP地址'), _t('SMTP服务器连接地址'));
        $form->addInput($smtp_host);
        // SMTP端口
        $smtp_port = new Typecho_Widget_Helper_Form_Element_Text('smtp_port', NULL, NULL, _t('SMTP端口'), _t('SMTP服务器连接端口'));
        $form->addInput($smtp_port);
        // SMTP用户名
        $smtp_user = new Typecho_Widget_Helper_Form_Element_Text('smtp_user', NULL, NULL, _t('SMTP登录用户'), _t('SMTP登录用户名，一般为邮箱地址'));
        $form->addInput($smtp_user);
        // SMTP密码
        $smtp_pass = new Typecho_Widget_Helper_Form_Element_Text('smtp_pass', NULL, NULL, _t('SMTP登录密码'), _t('一般为邮箱密码，但某些服务商需要生成特定密码'));
        $form->addInput($smtp_pass);
        // 服务器安全模式
        $smtp_secure = new Typecho_Widget_Helper_Form_Element_Radio('smtp_secure', array('ssl' => _t('SSL加密'), 'tls' => _t('TLS加密')), 'ssl', _t('SMTP加密模式'));
        $form->addInput($smtp_secure);
    }

    /**
     * 个人用户的配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}
     
    /**
     *为header添加css文件
     * @return void
     */
    public static function header()
    {
        print <<<HTML
<style>
    .icon{
        margin-top: 5px; 
    }
</style>
HTML;
    }
    
        /**
     *为footer添加js文件
     * @return void
     */
    public static function footer(){
        $api = Typecho_Common::url('user/api', Helper::options()->index);
        if(!Typecho_Widget::widget('Widget_User')->hasLogin()){
        print <<<HTML
<script>
    function GetOauthUrl(site){
        let xhr = new XMLHttpRequest();
        xhr.open('post', '{$api}');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                let res = JSON.parse(xhr.responseText);
                if(res.code == 1){
                    let height = res.height;
                    let width = res.width;
                    let url = res.url;
                    let iTop = (window.screen.availHeight - 30 - height) / 2;
                    let iLeft = (window.screen.availWidth - 10 - width) / 2;
                    let open = window.open(url, '_blank', 'height=' + height + ',innerHeight=' + height + ',width=' + width + ',innerWidth=' + width + ',top=' + iTop + ',left=' + iLeft + ',status=no,toolbar=no,menubar=no,location=no,resizable=no,scrollbars=0,titlebar=no');
                    if(!open){
                        window.location.href = url;
                    }
                }else{
                    
                }
            }
        }
        let from = encodeURIComponent(window.location.href);
        xhr.send('action=url&site='+site+'&from='+from);
    }
    function GetOauthIcon(){
        let obj = '#OauthIcon';
        if(window.OauthIconData){
            let ico = window.OauthIconData;
            let html = '';
            for(let i = 0; i < ico.length; i++){
                html += '<a onclick="GetOauthUrl(\''+ico[i].site+'\')" class="btn btn-rounded btn-sm btn-icon btn-default" data-toggle="tooltip" data-placement="bottom" data-original-title="'+ico[i].name+'账号登陆">'+ico[i].ico+'</a>';
            }
            document.querySelectorAll(obj).forEach(e => {
                e.innerHTML = html;
            });
            console.log('第三方登录按钮加载完成');
            return;
        }
        let xhr = new XMLHttpRequest();
        xhr.open('post', '{$api}');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                let res = JSON.parse(xhr.responseText);
                if(res.code == 1){
                    window.OauthIconData = res.data;
                    let html = '';
                    for(let i = 0; i < res.data.length; i++){
                        html += '<a onclick="GetOauthUrl(\''+res.data[i].site+'\')" class="btn btn-rounded btn-sm btn-icon btn-default" data-toggle="tooltip" data-placement="bottom" data-original-title="'+res.data[i].name+'账号登陆">'+res.data[i].ico+'</a>';
                    }
                    document.querySelectorAll(obj).forEach(e => {
                        e.innerHTML = html;
                    });
                    console.log('第三方登录按钮加载完成');
                }else{
                    console.log('第三方登录按钮加载失败');
                }
            }
        }
        let from = encodeURIComponent(window.location.href);
        xhr.send('action=icon');
    }GetOauthIcon();
</script>
HTML;
        }else{
            print <<<HTML
<script>
    function GetOauthIcon(){
        console.log('已登录');
    }
</script>
HTML;
        }
    }
    
    public static function url($action, $param = NULL)
    {
        $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
        $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
        $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
        $relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);
        $url = urlencode($sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url);
        switch ($action) {
            case 'register':
                $url = Typecho_Common::url('user/register', Helper::options()->index).'?from='.$url;
                break;
            case 'login':
                $url = Typecho_Common::url('user/login', Helper::options()->index).'?from='.$url;
                break;
            case 'forget':
                $url = Typecho_Common::url('user/forget', Helper::options()->index).'?from='.$url;
                break;
        }
        if($param){
            return $url;
        }else{
            print $url;
        }
    }

    public static function oauth(){
        $login = self::url('login',1);
        $register = self::url('register',1);
        if(!Typecho_Widget::widget('Widget_User')->hasLogin()){
            print <<<HTML
<div class="row text-center" style="margin-top:10px;">
    <p class="text-muted letterspacing indexWords">第三方登陆</p>
</div>
<div class="row text-center" style="margin-top:-5px;" id="OauthIcon"><p class="infinite-scroll-request"><i class="animate-spin fontello fontello-refresh"></i>Loading...</p></div>
<p id="social-buttons" style="display: flex;margin-top: 8px;justify-content: center;">
    <a no-pjax href="{$login}" class="btn btn-rounded btn-sm btn-info"><i class="fa fa-fw fa-key"></i> 登录</a>&nbsp;&nbsp;
    <a no-pjax href="{$register}" class="btn btn-rounded btn-sm btn-danger"><i class="fa fa-fw fa-user"></i> 注册+</a>
</p>
HTML;
        }
    }
}
