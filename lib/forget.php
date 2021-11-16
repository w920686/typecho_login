<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <meta charset="utf-8" />
        <title>重置密码 - <?php print trim(Helper::options()->title)?></title>
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, shrink-to-fit=no, viewport-fit=cover">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                            <!-- Logo -->
                            <div class="card-header  text-center" style="color:#FFF">
                                <a href="/">
                                    <span><img src="<?php print $this->plugin->logo ?>" alt="" height="50"></span>
                                </a>
                            </div>
                            
                            <div class="card-body p-4">
                                
                                <div class="text-center w-75 m-auto">
                                    <h4 class="text-dark-50 text-center mt-0 font-weight-bold">重置密码</h4>
                                    <p class="text-muted mb-4">输入注册时的邮箱设置新密码</p>
                                </div>

                                <div class="form-group mb-3" id="post1">
                                    <label>邮箱</label>
                                    <input class="form-control" type="text" id="email" placeholder="邮箱">
                                </div>
                                
                                <div class="form-group mb-3" id="post2">
                                    <label>验证码</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="code" placeholder="输入验证码">
                                        <div class="input-group-append">
                                            <button class="btn btn-light" id="send" type="button">获取验证码</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3" id="new1" style="display:none">
                                    <label>新密码</label>
                                    <input class="form-control" type="password" id="password" placeholder="新密码">
                                </div>

                                <div class="form-group mb-3" id="new2" style="display:none">
                                    <label>确认密码</label>
                                    <input class="form-control" type="password" id="cpassword" placeholder="确认密码">
                                </div>

                                <div class="form-group mb-0 text-center" id="post3">
                                    <button class="btn btn-primary"  id="check" type="submit">验证</button>
                                </div>

                                <div class="form-group mb-0 text-center" id="new3" style="display:none">
                                    <button class="btn btn-primary"  id="forget" type="submit">设置密码</button>
                                </div>
                            </div> <!-- end card-body-->
                        </div>
                        <!-- end card -->

                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <p class="text-muted">返回 <a href="./login?from=<?php print urlencode($_GET['from'])?>" class="text-dark ml-1"><b>登陆</b></a></p>
                            </div> <!-- end col -->
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
                        if (!result) return t.NotificationApp.send("重置提示", "请先完成验证", "top-right", "rgba(0,0,0,0.2)", "warning");
                        $.ajax({
                            url: '<?php print Typecho_Common::url('user/api', Helper::options()->index)?>',
                            type: 'post',
                            dataType: 'json',
                            async: true, 
                            data: {
                                action : 'forget_code',
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
                                t.NotificationApp.send("重置提示", "服务器繁忙", "top-right", "rgba(0,0,0,0.2)", "error");
                            }, 
                            success: function (res) {
                                if(res.code == 1){
                                    setTime();
                                    t.NotificationApp.send("重置提示", "验证码已发送到您的邮箱", "top-right", "rgba(0,0,0,0.2)", "success");
                                }else{
                                    t.NotificationApp.send("重置提示", res.msg, "top-right", "rgba(0,0,0,0.2)", "warning");
                                }
                            }
                        });
                    });
                    captchaObj.onClose(function () {
                        if(!captchaObj.getValidate()){
                            t.NotificationApp.send("重置提示", "您还未完成验证哦", "top-right", "rgba(0,0,0,0.2)", "warning");
                        }
                    });
                    captchaObj.onError(function (error) {
                        t.NotificationApp.send("重置提示", error.msg ? error.msg : '验证码加载失败', "top-right", "rgba(0,0,0,0.2)", "warning");
                    });
                    $('#send').click(function () {
                        let email = $("#email").val();
                        if(!email) return t.NotificationApp.send("重置提示", "请输入邮箱后发送验证码", "top-right", "rgba(0,0,0,0.2)", "warning");
                        captchaObj.verify();
                    });
                };
                <?php }else {?>
                $("#send").click(function(){
                    let email = $("#email").val();
                    if(!email) return t.NotificationApp.send("重置提示", "请输入邮箱后发送验证码", "top-right", "rgba(0,0,0,0.2)", "warning");
                    $.ajax({
                        url: '<?php print Typecho_Common::url('user/api', Helper::options()->index)?>',
                        type: 'post',
                        dataType: 'json',
                        async: true, 
                        data: {
                            action : 'forget_code',
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
                            t.NotificationApp.send("重置提示", "服务器繁忙", "top-right", "rgba(0,0,0,0.2)", "error");
                        }, 
                        success: function (res) {
                            if(res.code == 1){
                                setTime();
                                t.NotificationApp.send("重置提示", "验证码已发送到您的邮箱", "top-right", "rgba(0,0,0,0.2)", "success");
                            }else{
                                t.NotificationApp.send("重置提示", res.msg, "top-right", "rgba(0,0,0,0.2)", "warning");
                            }
                        }
                    });
                });
                <?php } ?>
                let state;
                $("#check").click(function(){
                    let email = $("#email").val();
                    let code = $("#code").val();
                    if(!email) return t.NotificationApp.send("重置提示", "请输入邮箱", "top-right", "rgba(0,0,0,0.2)", "warning");
                    if(!code) return t.NotificationApp.send("重置提示", "请输入验证码", "top-right", "rgba(0,0,0,0.2)", "warning");
                    $.ajax({
                        url: '<?php print Typecho_Common::url('user/api', Helper::options()->index)?>',
                        type: 'post',
                        dataType: 'json',
                        async: true, 
                        data: {
                            action : 'forget_check',
                            email : email,
                            code : code
                        },
                        beforeSend:function(){
                            btn($("#check"),"验证中",true);
                        }, 
                        complete:function(){
                            btn($("#check"),"验证",false);
                        },
                        error: function(){
                            btn($("#check"),"验证",false);
                            t.NotificationApp.send("重置提示", "服务器繁忙", "top-right", "rgba(0,0,0,0.2)", "error");
                        }, 
                        success: function (res) {
                            if(res.code == 1){
                                $("#post1").hide(200);
                                $("#post2").hide(200);
                                $("#post3").hide(200);
                                $("#new1").show(200);
                                $("#new2").show(200);
                                $("#new3").show(200);
                                t.NotificationApp.send("重置提示", "验证通过，请设置新密码", "top-right", "rgba(0,0,0,0.2)", "success");
                                t.state = res.state;
                            }else{
                                t.NotificationApp.send("重置提示", res.msg, "top-right", "rgba(0,0,0,0.2)", "warning");
                            }
                        }
                    });
                });
                $("#forget").click(function(){
                    let password = $("#password").val();
                    let cpassword = $("#cpassword").val();
                    if(!password) return t.NotificationApp.send("重置提示", "请输入密码", "top-right", "rgba(0,0,0,0.2)", "warning");
                    if(!cpassword) return t.NotificationApp.send("重置提示", "请输入确认密码", "top-right", "rgba(0,0,0,0.2)", "warning");
                    if(password != cpassword) return t.NotificationApp.send("重置提示", "两次密码不一致", "top-right", "rgba(0,0,0,0.2)", "warning");
                    $.ajax({
                        url: '<?php print Typecho_Common::url('user/api', Helper::options()->index)?>',
                        type: 'post',
                        dataType: 'json',
                        async: true, 
                        data: {
                            action : 'forget',
                            state : t.state,
                            password : password,
                            cpassword : cpassword
                        },
                        beforeSend:function(){
                            btn($("#forget"),"设置中",true);
                        }, 
                        complete:function(){
                            btn($("#forget"),"设置密码",false);
                        },
                        error: function(){
                            btn($("#forget"),"设置密码",false);
                            t.NotificationApp.send("重置提示", "服务器繁忙", "top-right", "rgba(0,0,0,0.2)", "error");
                        }, 
                        success: function (res) {
                            if(res.code == 1){
                                t.NotificationApp.send("重置提示", "密码重置成功", "top-right", "rgba(0,0,0,0.2)", "success");
                                setTimeout(function(){
                                    window.location.href = from ? from : "<?php print Typecho_Common::url('/', Helper::options()->index) ?>";
                                }, 1500);
                            }else{
                                t.NotificationApp.send("重置提示", res.msg, "top-right", "rgba(0,0,0,0.2)", "warning");
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
                        info : 'forget_code'
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
