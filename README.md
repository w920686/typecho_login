# typecho_Oauth
typecho登陆注册插件支持免申请第三方应用

# 演示站
https://blog.gmit.vip/


# 使用方法
----
下载之后把插件丢到 `plugins` 目录 目录名改成 `GmLogin`
下面代码可以输入登陆注册以及找回密码链接


    <?php GmLogin_Plugin::url('login'); //输出登陆url ?>
    <?php GmLogin_Plugin::url('register'); //输出注册url ?>
    <?php GmLogin_Plugin::url('forget'); //输出找回密码url ?>


# 支持第三方登陆

此第三方登陆免申请开发者应用，无需配置开发者应用 了解详细:<a target="_blank" href="https://auth.gmit.vip/">https://auth.gmit.vip/</a><br/>此功能需要配合typecho_Oauth插件使用，否则无效 插件下载：<a href="https://github.com/AIYMC/typecho_Oauth" target="_blank">GitHub</a> <a href="https://gitee.com/isgm/typecho_Oauth" target="_blank">Gitee</a>


# 版本
v1.0
1. 新增注册
2. 新增登陆
3. 新增找回密码
4. 新增支持极验
2. 新增第三方登陆
