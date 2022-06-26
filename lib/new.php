<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <meta charset="utf-8" />
        <title>完善账号信息 - <?php print trim(Helper::options()->title)?></title>
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
            #text {
                height: 42px;
                width: 100%;
                text-align: center;
                border-radius: 2px;
                background-color: #F3F3F3;
                color: #BBBBBB;
                font-size: 14px;
                letter-spacing: 0.1px;
                line-height: 42px;
            }

            #wait {
                display: none;
                height: 42px;
                width: 100%;
                text-align: center;
                border-radius: 2px;
                background-color: #F3F3F3;
            }

            .loading {
                margin: auto;
                width: 70px;
                height: 20px;
            }

            .loading-dot {
                float: left;
                width: 8px;
                height: 8px;
                margin: 18px 4px;
                background: #ccc;

                -webkit-border-radius: 50%;
                -moz-border-radius: 50%;
                border-radius: 50%;

                opacity: 0;

                -webkit-box-shadow: 0 0 2px black;
                -moz-box-shadow: 0 0 2px black;
                -ms-box-shadow: 0 0 2px black;
                -o-box-shadow: 0 0 2px black;
                box-shadow: 0 0 2px black;

                -webkit-animation: loadingFade 1s infinite;
                -moz-animation: loadingFade 1s infinite;
                animation: loadingFade 1s infinite;
            }

            .loading-dot:nth-child(1) {
                -webkit-animation-delay: 0s;
                -moz-animation-delay: 0s;
                animation-delay: 0s;
            }

            .loading-dot:nth-child(2) {
                -webkit-animation-delay: 0.1s;
                -moz-animation-delay: 0.1s;
                animation-delay: 0.1s;
            }

            .loading-dot:nth-child(3) {
                -webkit-animation-delay: 0.2s;
                -moz-animation-delay: 0.2s;
                animation-delay: 0.2s;
            }

            .loading-dot:nth-child(4) {
                -webkit-animation-delay: 0.3s;
                -moz-animation-delay: 0.3s;
                animation-delay: 0.3s;
            }

            @-webkit-keyframes loadingFade {
                0% { opacity: 0; }
                50% { opacity: 0.8; }
                100% { opacity: 0; }
            }

            @-moz-keyframes loadingFade {
                0% { opacity: 0; }
                50% { opacity: 0.8; }
                100% { opacity: 0; }
            }

            @keyframes loadingFade {
                0% { opacity: 0; }
                50% { opacity: 0.8; }
                100% { opacity: 0; }
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
                                <a href="javascript:;">
                                    <span><img src="<?php print $this->plugin->logo ?>" alt="" height="50"></span>
                                </a>
                            </div>

                            <div class="card-body p-4">
                                
                                <div class="text-center w-75 m-auto">
                                    <h4 class="text-dark-50 text-center mt-0 font-weight-bold">完善账号信息</h4>
                                    <p class="text-muted mb-4">您可以创建新的账号或绑定已有账号</p>
                                </div>
                                <ul class="nav nav-pills bg-light nav-justified mb-3">
                                    <li class="nav-item">
                                        <a href="#new_account" data-toggle="tab" aria-expanded="false" class="nav-link rounded-0">
                                            <span class="d-lg-block">绑定新账号</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#check_account" data-toggle="tab" aria-expanded="true" class="nav-link rounded-0 active">
                                            <span class="d-lg-block">绑定已有账号</span>
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane" id="new_account">
                                        <div class="form-group mb-3">
                                            <label>昵称(用于显示)</label>
                                            <input class="form-control" type="text" id="nickname" placeholder="输入昵称" value="<?php echo $nickname;?>">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label>用户名(用于登录)</label>
                                            <input class="form-control" type="text" id="username" placeholder="输入用户名">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label>邮箱</label>
                                            <input class="form-control" type="text" id="email" placeholder="邮箱" value="<?php echo $email;?>">
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
                                            <button class="btn btn-light" id="register"> 注册并绑定 </button>
                                        </div>
                                    </div>
                                    <div class="tab-pane show active" id="check_account">
                                        <div class="form-group mb-3">
                                            <label>邮箱/用户名</label>
                                            <input class="form-control" type="text" id="user" placeholder="输入手机/邮箱/用户名">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="float-left">密码</label>
                                            <input class="form-control" type="password" id="pass" placeholder="输入密码">
                                        </div>
                                        <?php if($this->plugin->gt == 1 && $this->plugin->gtid && $this->plugin->gtkey) {?>
                                        <div class="form-group mb-3">
                                            <div id="captcha">
                                                <div id="text">
                                                    行为验证™ 安全组件加载中
                                                </div>
                                                <div id="wait" class="show">
                                                    <div class="loading">
                                                        <div class="loading-dot"></div>
                                                        <div class="loading-dot"></div>
                                                        <div class="loading-dot"></div>
                                                        <div class="loading-dot"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php }?>
                                        <div class="form-group mb-0 text-center">
                                            <button class="btn btn-light" id="login"> 绑定 </button>
                                        </div>
                                    </div>
                                </div>
                                
                            </div> <!-- end card-body -->
                        </div>
                        <!-- end card -->

                        <div class="row mt-3" style="display: none;">
                            <div class="col-12 text-center">
                                <p class="text-muted">使用其它账号登录? <a href="./login?from=<?php print urlencode($_GET['from'])?>" class="text-dark ml-1"><b>登陆</b></a></p>
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
            if(window.resizeTo){
                window.resizeTo(250,700);
            }
            if(window.resizeBy){
                window.resizeBy(250,700);
            }
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
                        if (!result) return t.NotificationApp.send("注册绑定提示", "请先完成验证", "top-right", "rgba(0,0,0,0.2)", "warning");
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
                                t.NotificationApp.send("注册绑定提示", "服务器繁忙", "top-right", "rgba(0,0,0,0.2)", "error");
                            }, 
                            success: function (res) {
                                if(res.code == 1){
                                    setTime();
                                    t.NotificationApp.send("注册绑定提示", "验证码已发送到您的邮箱", "top-right", "rgba(0,0,0,0.2)", "success");
                                }else{
                                    t.NotificationApp.send("注册绑定提示", res.msg, "top-right", "rgba(0,0,0,0.2)", "warning");
                                }
                            }
                        });
                    });
                    captchaObj.onClose(function () {
                        if(!captchaObj.getValidate()){
                            t.NotificationApp.send("注册绑定提示", "您还未完成验证哦", "top-right", "rgba(0,0,0,0.2)", "warning");
                        }
                    });
                    captchaObj.onError(function (error) {
                        t.NotificationApp.send("注册绑定提示", error.msg ? error.msg : '验证码加载失败', "top-right", "rgba(0,0,0,0.2)", "warning");
                    });
                    $('#send').click(function () {
                        let email = $("#email").val();
                        if(!email) return t.NotificationApp.send("注册绑定提示", "请输入邮箱后发送验证码", "top-right", "rgba(0,0,0,0.2)", "warning");
                        captchaObj.verify();
                    });
                };
                let login = function (captchaObj) {
                    captchaObj.appendTo('#captcha');
                    captchaObj.onReady(function () {
                        $("#wait").hide();
                    });
                    captchaObj.onClose(function () {
                        if(!captchaObj.getValidate()){
                            t.NotificationApp.send("绑定提示", "您还未完成验证哦", "top-right", "rgba(0,0,0,0.2)", "warning");
                        }
                    });
                    captchaObj.onError(function (error) {
                        t.NotificationApp.send("绑定提示", error.msg ? error.msg : '验证码加载失败', "top-right", "rgba(0,0,0,0.2)", "warning");
                    });
                    t.gt = captchaObj;
                };
                <?php }else {?>
                $("#send").click(function(){
                    let email = $("#email").val();
                    if(!email) return t.NotificationApp.send("注册绑定提示", "请输入邮箱后发送验证码", "top-right", "rgba(0,0,0,0.2)", "warning");
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
                            t.NotificationApp.send("注册绑定提示", "服务器繁忙", "top-right", "rgba(0,0,0,0.2)", "error");
                        }, 
                        success: function (res) {
                            if(res.code == 1){
                                setTime();
                                t.NotificationApp.send("注册绑定提示", "验证码已发送到您的邮箱", "top-right", "rgba(0,0,0,0.2)", "success");
                            }else{
                                t.NotificationApp.send("注册绑定提示", res.msg, "top-right", "rgba(0,0,0,0.2)", "warning");
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
                    if(!nickname) return t.NotificationApp.send("注册绑定提示", "请输入昵称", "top-right", "rgba(0,0,0,0.2)", "warning");
                    if(!username) return t.NotificationApp.send("注册绑定提示", "请输入用户名", "top-right", "rgba(0,0,0,0.2)", "warning");
                    if(!email) return t.NotificationApp.send("注册绑定提示", "请输入邮箱", "top-right", "rgba(0,0,0,0.2)", "warning");
                    if(!code) return t.NotificationApp.send("注册绑定提示", "请输入验证码", "top-right", "rgba(0,0,0,0.2)", "warning");
                    if(!password) return t.NotificationApp.send("注册绑定提示", "请输入密码", "top-right", "rgba(0,0,0,0.2)", "warning");
                    if(!cpassword) return t.NotificationApp.send("注册绑定提示", "请输入确认密码", "top-right", "rgba(0,0,0,0.2)", "warning");
                    if(cpassword != password) return t.NotificationApp.send("注册绑定提示", "两次密码不一致", "top-right", "rgba(0,0,0,0.2)", "warning");
                    $.ajax({
                        url: '<?php print Typecho_Common::url('user/api', Helper::options()->index)?>',
                        type: 'post',
                        dataType: 'json',
                        async: true, 
                        data: {
                            action : 'new_register',
                            state : '<?php echo $state;?>',
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
                            btn($("#register"),"注册并绑定",false);
                        },
                        error: function(){
                            btn($("#register"),"注册并绑定",false);
                            t.NotificationApp.send("注册绑定提示", "服务器繁忙", "top-right", "rgba(0,0,0,0.2)", "error");
                        }, 
                        success: function (res) {
                            if(res.code == 1){
                                t.NotificationApp.send("注册绑定提示", "注册并绑定成功", "top-right", "rgba(0,0,0,0.2)", "success");
                                setTimeout(function(){
                                    top.location =  from ? from : "<?php print Typecho_Common::url('/', Helper::options()->index) ?>";
                                }, 1000);
                                setTimeout(function(){
                                    if(window.opener.location.href){
                                        window.opener.location.reload(true);self.close();
                                    }else{
                                        window.location.replace= from ? from : "<?php print Typecho_Common::url('/', Helper::options()->index) ?>";
                                    }
                                }, 500);
                                setTimeout(function(){window.opener=null;window.close();}, 50000);
                            }else{
                                t.NotificationApp.send("注册绑定提示", res.msg, "top-right", "rgba(0,0,0,0.2)", "warning");
                            }
                        }
                    });
                });
                $("#login").click(function(){
                    let username = $("#user").val();
                    let password = $("#pass").val();
                    if(!username) return t.NotificationApp.send("绑定提示", "请输入邮箱/用户名", "top-right", "rgba(0,0,0,0.2)", "warning");
                    if(!password) return t.NotificationApp.send("绑定提示", "请输入密码", "top-right", "rgba(0,0,0,0.2)", "warning");
                    <?php if($this->plugin->gt == 1 && $this->plugin->gtid && $this->plugin->gtkey) {?>
                    let result = t.gt.getValidate();
                    if (!result) return t.NotificationApp.send("绑定提示", "请先完成验证", "top-right", "rgba(0,0,0,0.2)", "warning");
                    <?php }?>
                    $.ajax({
                        url: '<?php print Typecho_Common::url('user/api', Helper::options()->index)?>',
                        type: 'post',
                        dataType: 'json',
                        async: true, 
                        data: {
                            action : 'new_login',
                            state : '<?php echo $state;?>',
                            username : username,
                            password : password,
                            <?php if($this->plugin->gt == 1 && $this->plugin->gtid && $this->plugin->gtkey) {?>
                            geetest_challenge: result.geetest_challenge,
                            geetest_validate: result.geetest_validate,
                            geetest_seccode: result.geetest_seccode
                            <?php }?>
                        },
                        beforeSend:function(){
                            btn($("#login"),"绑定中",true);
                        }, 
                        complete:function(){
                            <?php if($this->plugin->gt == 1 && $this->plugin->gtid && $this->plugin->gtkey) {?>
                            t.gt.reset();
                            <?php }?>
                            btn($("#login"),"绑定",false);
                        },
                        error: function(){
                            <?php if($this->plugin->gt == 1 && $this->plugin->gtid && $this->plugin->gtkey) {?>
                            t.gt.reset();
                            <?php }?>
                            btn($("#login"),"绑定",false);
                            t.NotificationApp.send("绑定提示", "服务器繁忙", "top-right", "rgba(0,0,0,0.2)", "error");
                        }, 
                        success: function (res) {
                            if(res.code == 1){
                                t.NotificationApp.send("绑定提示", "绑定成功", "top-right", "rgba(0,0,0,0.2)", "success");
                                setTimeout(function(){
                                    top.location =  from ? from : "<?php print Typecho_Common::url('/', Helper::options()->index) ?>";
                                }, 1000);
                                setTimeout(function(){
                                    if(window.opener.location.href){
                                        window.opener.location.reload(true);self.close();
                                    }else{
                                        window.location.replace= from ? from : "<?php print Typecho_Common::url('/', Helper::options()->index) ?>";
                                    }
                                }, 500);
                                setTimeout(function(){window.opener=null;window.close();}, 50000);
                            }else{
                                t.NotificationApp.send("绑定提示", res.msg, "top-right", "rgba(0,0,0,0.2)", "warning");
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
                    async: true,
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
                $.ajax({
                    url: "<?php print Typecho_Common::url('user/api', Helper::options()->index)?>?t=" + (new Date()).getTime(), // 加随机数防止缓存
                    type: "post",
                    dataType: "json",
                    data: {
                        action : 'code',
                        info : 'login'
                    },
                    async: true,
                    success: function (res) {
                        $('#text').hide();
                        $('#wait').show();
                        initGeetest({
                            gt: res.gt,
                            challenge: res.challenge,
                            offline: !res.success, // 表示用户后台检测极验服务器是否宕机
                            new_captcha: res.new_captcha, // 用于宕机时表示是新验证码的宕机

                            product: "float",
                            width: "100%",
                            https: true,
                            api_server: "apiv6.geetest.com"
                        }, login);
                    }
                });
                <?php }?>
            }(window.jQuery)
        </script>
    </body>
</html>
