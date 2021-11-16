<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
define('__PLUGIN_ROOT__', __DIR__);
/**
 * <strong style="color:#000000;">故梦登陆注册插件</strong>
 * 
 * @package GmLogin
 * @author Gm
 * @version 1.0
 * @update: 2021-11-9
 * @link //www.gmit.vip
 */
class GmLogin_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Helper::addRoute('Gm_login', '/user/login', 'GmLogin_Action', 'login');
        Helper::addRoute('Gm_register', '/user/register', 'GmLogin_Action', 'register');
        Helper::addRoute('Gm_forget', '/user/forget', 'GmLogin_Action', 'forget');
        Helper::addRoute('Gm_api', '/user/api', 'GmLogin_Action', 'api');
        return '插件安装成功!';
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
        Helper::removeRoute('Gm_login');
        Helper::removeRoute('Gm_register');
        Helper::removeRoute('Gm_forget');
        Helper::removeRoute('Gm_api');
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
        $oauth = new Typecho_Widget_Helper_Form_Element_Radio('oauth', array('1' => _t('开启'), '0' => _t('关闭')), '0', _t('第三方登陆按钮'),_t('此第三方登陆免申请开发者应用，无需配置开发者应用 了解详细:<a target="_blank" href="https://auth.gmit.vip/">https://auth.gmit.vip/</a><br/>此功能需要配合typecho_Oauth插件使用，否则无效 插件下载：<a href="https://github.com/AIYMC/typecho_Oauth" target="_blank">GitHub</a> <a href="https://gitee.com/isgm/typecho_Oauth" target="_blank">Gitee</a>'));
        $title->html('登陆功能管理 <a href="'.Typecho_Widget::widget('Widget_Options')->adminUrl.'options-plugin.php?config=GmOauth" target="_blank">点我前往</a>');
        $form->addItem($title);
        $title = new Typecho_Widget_Helper_Layout('div', array('class=' => 'typecho-page-title'));
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
    /*public static function header()
    {
        
    }*/
    
        /**
     *为footer添加js文件
     * @return void
     */
    /*public static function footer(){
        
    }*/
    
    public static function url($action)
    {
        switch ($action) {
            case 'register':
                echo Typecho_Common::url('user/register', Helper::options()->index);
                break;
            case 'login':
                echo Typecho_Common::url('user/login', Helper::options()->index);
                break;
            case 'forget':
                echo Typecho_Common::url('user/forget', Helper::options()->index);
                break;
        }
    }
}
