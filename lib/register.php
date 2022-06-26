<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <meta charset="utf-8" />
        <title>账号注册 - <?php print trim(Helper::options()->title)?></title>
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, shrink-to-fit=no, viewport-fit=cover">
        <!-- App favicon -->
        <link rel="shortcut icon" href="<?php print $this->plugin->ico ?>">
        <link rel="apple-touch-icon" href="<?php print $this->plugin->ico ?>">
        <link rel="icon" href="<?php print $this->plugin->ico ?>">
        <!-- App css -->
        <!-- build:css -->
        <link href="<?php print $this->dir ?>/assets/css/app.min.css" rel="stylesheet" type="text/css" />
        <!-- endbuild -->
        <style>
            body.enlarged {
                min-height: 0;
            }
        </style>
    </head>

    <body class="authentication-bg">
        <div class="account-pages mt-5 mb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5">
                        <div class="card">
                            <!-- Logo-->
                            <div class="card-header  text-center" style="color:#FFF">
                                <a href="/">
                                    <span><img src="<?php print $this->plugin->logo ?>" alt="" height="50"></span>
                                </a>
                            </div>

                            <div class="card-body p-4">
                                
                                <div class="text-center w-75 m-auto">
                                    <h4 class="text-dark-50 text-center mt-0 font-weight-bold">账号注册</h4>
                                    <p class="text-muted mb-4">创建您的帐号，只需不到一分钟</p>
                                </div>

                                <div class="form-group mb-3">
                                    <label>昵称(用于显示)</label>
                                    <input class="form-control" type="text" id="nickname" placeholder="输入昵称">
                                </div>

                                <div class="form-group mb-3">
                                    <label>用户名(用于登录)</label>
                                    <input class="form-control" type="text" id="username" placeholder="输入用户名">
                                </div>

                                <div class="form-group mb-3">
                                    <label>邮箱</label>
                                    <input class="form-control" type="text" id="email" placeholder="邮箱">
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label>验证码</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="code" placeholder="输入验证码">
                                        <div class="input-group-append">
                                            <button class="btn btn-light" id="send" type="button">获取验证码</button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label>密码</label>
                                    <input class="form-control" type="password" id="password" placeholder="输入密码">
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label>确认密码</label>
                                    <input class="form-control" type="password" id="cpassword" placeholder="输入确认密码">
                                </div>
                                <div class="form-group mb-0 text-center">
                                    <button class="btn btn-light" id="register"> 注册 </button>
                                </div>
                                
                            </div> <!-- end card-body -->
                        </div>
                        <!-- end card -->

                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <p class="text-muted">已有账号? <a href="./login?from=<?php print urlencode($_GET['from'])?>" class="text-dark ml-1"><b>登陆</b></a></p>
                            </div> <!-- end col-->
                        </div>
                        <!-- end row -->

                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end page -->
        <footer class="footer footer-alt">
        <?php print date("Y")?> © <?php print trim(Helper::options()->title)?>
        </footer>
        <!-- App js -->
        <script src="<?php print $this->dir ?>/assets/javascript/app.min.js"></script>
        <?php if($this->plugin->gt == 1 && $this->plugin->gtid && $this->plugin->gtkey) {?>
        <script src="<?php print $this->dir ?>/assets/javascript/gt.js"></script>
        <?php }?>
        <script>
            !function (t) {
                let from = '<?php print $_GET['from']?>';
                let btn = function(obj,msg,code){
                    if(code == true){
                        obj.html('<i class="mdi mdi-loading mdi-spin"></i> '+msg+' ');
                        obj.attr("disabled",true);
                    }else{
                        obj.html(' '+msg+' ');
                        obj.attr("disabled",false);
                    }
                }
                let countdown = 60;
                let setTime = function() {
                    if (countdown == 0) {
                        $("#send").html(' 获取验证码 ');
                        $("#send").attr("disabled",false);
                        $("#email").attr("disabled",false);
                        countdown = 60;
                        return;
                    } else {
                        $("#send").html(" ("+countdown+"秒)后重可发 ");
                        $("#email").attr("disabled",true);
                        $("#send").attr("disabled",true);
                        countdown--;
                    }
                    setTimeout(function() { setTime() },1000);
                }
                <?php if($this->plugin->gt == 1 && $this->plugin->gtid && $this->plugin->gtkey) {?>
                var sendcode = function (captchaObj) {
                    captchaObj.onReady(function () {
                        $("#wait").hide();
                    }).onSuccess(function () {
                        var result = captchaObj.getValidate();
                        if (!result) return t.NotificationApp.send("注册提示", "请先完成验证", "top-right", "rgba(0,0,0,0.2)", "warning");
                        $.ajax({
                            url: '<?php print Typecho_Common::url('user/api', Helper::options()->index)?>',
                            type: 'post',
                            dataType: 'json',
                            async: true, 
                            data: {
                                action : 'reg_code',
                                email : $("#email").val(),
                                geetest_challenge: result.geetest_challenge,
                                geetest_validate: result.geetest_validate,
                                geetest_seccode: result.geetest_seccode,
                            },
                            beforeSend:function(){
                                btn($("#send"),"发送中",true);
                            }, 
                            complete:function(){
                                captchaObj.reset();
                                btn($("#send"),"获取验证码",false);
                            },
                            error: function(){
                                captchaObj.reset();
                                btn($("#send"),"获取验证码",false);
                                t.NotificationApp.send("注册提示", "服务器繁忙", "top-right", "rgba(0,0,0,0.2)", "error");
                            }, 
                            success: function (res) {
                                if(res.code == 1){
                                    setTime();
                                    t.NotificationApp.send("注册提示", "验证码已发送到您的邮箱", "top-right", "rgba(0,0,0,0.2)", "success");
                                }else{
                                    t.NotificationApp.send("注册提示", res.msg, "top-right", "rgba(0,0,0,0.2)", "warning");
                                }
                            }
                        });
                    });
                    captchaObj.onClose(function () {
                        if(!captchaObj.getValidate()){
                            t.NotificationApp.send("注册提示", "您还未完成验证哦", "top-right", "rgba(0,0,0,0.2)", "warning");
                        }
                    });
                    captchaObj.onError(function (error) {
                        t.NotificationApp.send("注册提示", error.msg ? error.msg : '验证码加载失败', "top-right", "rgba(0,0,0,0.2)", "warning");
                    });
                    $('#send').click(function () {
                        let email = $("#email").val();
                        if(!email) return t.NotificationApp.send("注册提示", "请输入邮箱后发送验证码", "top-right", "rgba(0,0,0,0.2)", "warning");
                        captchaObj.verify();
                    });
                };
                <?php }else {?>
                $("#send").click(function(){
                    let email = $("#email").val();
                    if(!email) return t.NotificationApp.send("注册提示", "请输入邮箱后发送验证码", "top-right", "rgba(0,0,0,0.2)", "warning");
                    $.ajax({
                        url: '<?php print Typecho_Common::url('user/api', Helper::options()->index)?>',
                        type: 'post',
                        dataType: 'json',
                        async: true, 
                        data: {
                            action : 'reg_code',
                            email : email
                        },
                        beforeSend:function(){
                            btn($("#send"),"发送中",true);
                        }, 
                        complete:function(){
                            btn($("#send"),"获取验证码",false);
                        },
                        error: function(){
                            btn($("#send"),"获取验证码",false);
                            t.NotificationApp.send("注册提示", "服务器繁忙", "top-right", "rgba(0,0,0,0.2)", "error");
                        }, 
                        success: function (res) {
                            if(res.code == 1){
                                setTime();
                                t.NotificationApp.send("注册提示", "验证码已发送到您的邮箱", "top-right", "rgba(0,0,0,0.2)", "success");
                            }else{
                                t.NotificationApp.send("注册提示", res.msg, "top-right", "rgba(0,0,0,0.2)", "warning");
                            }
                        }
                    });
                });
                <?php } ?>
                $("#register").click(function(){
                    let nickname = $("#nickname").val();
                    let username = $("#username").val();
                    let email = $("#email").val();
                    let code = $("#code").val();
                    let password = $("#password").val();
                    let cpassword = $("#cpassword").val();
                    if(!nickname) return t.NotificationApp.send("注册提示", "请输入昵称", "top-right", "rgba(0,0,0,0.2)", "warning");
                    if(!username) return t.NotificationApp.send("注册提示", "请输入用户名", "top-right", "rgba(0,0,0,0.2)", "warning");
                    if(!email) return t.NotificationApp.send("注册提示", "请输入邮箱", "top-right", "rgba(0,0,0,0.2)", "warning");
                    if(!code) return t.NotificationApp.send("注册提示", "请输入验证码", "top-right", "rgba(0,0,0,0.2)", "warning");
                    if(!password) return t.NotificationApp.send("注册提示", "请输入密码", "top-right", "rgba(0,0,0,0.2)", "warning");
                    if(!cpassword) return t.NotificationApp.send("注册提示", "请输入确认密码", "top-right", "rgba(0,0,0,0.2)", "warning");
                    if(cpassword != password) return t.NotificationApp.send("注册提示", "两次密码不一致", "top-right", "rgba(0,0,0,0.2)", "warning");
                    $.ajax({
                        url: '<?php print Typecho_Common::url('user/api', Helper::options()->index)?>',
                        type: 'post',
                        dataType: 'json',
                        async: true, 
                        data: {
                            action : 'register',
                            nickname : nickname,
                            username : username,
                            email : email,
                            code : code,
                            password : password,
                            cpassword : cpassword
                        },
                        beforeSend:function(){
                            btn($("#register"),"注册中",true);
                        }, 
                        complete:function(){
                            btn($("#register"),"注册",false);
                        },
                        error: function(){
                            btn($("#register"),"注册",false);
                            t.NotificationApp.send("注册提示", "服务器繁忙", "top-right", "rgba(0,0,0,0.2)", "error");
                        }, 
                        success: function (res) {
                            if(res.code == 1){
                                t.NotificationApp.send("注册提示", "注册成功", "top-right", "rgba(0,0,0,0.2)", "success");
                                setTimeout(function(){
                                    window.location.href = from ? from : "<?php print Typecho_Common::url('/', Helper::options()->index) ?>";
                                }, 1500);
                            }else{
                                t.NotificationApp.send("注册提示", res.msg, "top-right", "rgba(0,0,0,0.2)", "warning");
                            }
                        }
                    });
                });
                <?php if($this->plugin->gt == 1 && $this->plugin->gtid && $this->plugin->gtkey) {?>
                $.ajax({
                    url: "<?php print Typecho_Common::url('user/api', Helper::options()->index)?>?t=" + (new Date()).getTime(), // 加随机数防止缓存
                    type: "post",
                    dataType: "json",
                    data: {
                        action : 'code',
                        info : 'reg_code'
                    },
                    success: function (res) {
                        initGeetest({
                            gt: res.gt,
                            challenge: res.challenge,
                            offline: !res.success,
                            new_captcha: res.new_captcha,

                            product: "bind",
                            width: "300px",
                            https: true,
                            api_server: "apiv6.geetest.com"
                        }, sendcode);
                    }
                });
                <?php }?>
            }(window.jQuery)
        </script>
    </body>
</html>
