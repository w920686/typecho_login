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

1. 钉钉
2. QQ
3. 百度
4. gitee码云
5. github
6. 微博
7. 华为
8. Gitlab
9. 阿里云
10. 支付宝
11. 小米
12. 开源中国
13. 领英
14. 微信
15. 企业微信
16. 微软
17. 飞书

会陆续新增其他站点的支持


# 版本
v1.1
1. 修复来源链接获取失败跳转到首页bug
2. 把集成登录写到一个插件 无需typecho_Oauth插件即可开启第三方登录
3. 新增设置logo选项
4. 新增设置icon图标选项
5. 修改电脑版使用弹窗打开授权页面

v1.0
1. 新增注册
2. 新增登陆
3. 新增找回密码
4. 新增支持极验
2. 新增第三方登陆