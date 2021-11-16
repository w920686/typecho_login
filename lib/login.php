<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <meta charset="utf-8" />
        <title>账号登陆 - <?php print trim(Helper::options()->title)?></title>
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, shrink-to-fit=no, viewport-fit=cover">
        <!-- App favicon -->
        <link rel="shortcut icon" href="<?php print $this->plugin->ico ?>">
        <link rel="apple-touch-icon" href="<?php print $this->plugin->ico ?>">
        <link rel="icon" href="<?php print $this->plugin->ico ?>">
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

                            <!-- Logo -->
                            <div class="card-header  text-center" style="color:#FFF">
                                <a href="/">
                                    <span><img src="<?php print $this->plugin->logo ?>" alt="" height="50"></span>
                                </a>
                            </div>

                            <div class="card-body p-4">
                                
                                <div class="text-center w-75 m-auto">
                                    <h4 class="text-dark-50 text-center mt-0 font-weight-bold">密码登录</h4>
                                    <p class="text-muted mb-4">请输入帐号密码进行登录</p>
                                </div>
                                <div class="form-group mb-3">
                                    <label>邮箱/用户名</label>
                                    <input class="form-control" type="text" id="username" placeholder="输入手机/邮箱/用户名">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="float-left" for="password">密码</label>
                                    <a href="./forget?from=<?php print urlencode($_GET['from'])?>" class="text-muted float-right"><small>忘记密码?</small></a>
                                    <input class="form-control" type="password" id="password" placeholder="输入密码">
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
                                    <button class="btn btn-light" id="login">登录 </button>
                                </div>

                                <div class="text-center mt-3">
                                    <p class="text-muted mb-1">快捷登录</p>
                                    <?php 
                                    if($this->plugin->oauth){
                                    $iconfile = __PLUGIN_ROOT__."/icon.json";
                                    $icon = @fopen($iconfile, "r") or die("登陆按钮图标文件丢失!");
                                    $site = json_decode(fread($icon,filesize($iconfile)),true);
                                    fclose($icon);
                                    if($this->plugin->qq){
                                    for ($i = 0; $i < count($site); $i++) {
                                        if($site[$i]['site'] == 'qq') {
                                    ?>
                                    <button onclick="GetUrl('qq')" type="button" class="btn btn-light"> <svg t="1607251153785" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="6106" width="19" height="19"><path d="M511.09761 957.257c-80.159 0-153.737-25.019-201.11-62.386-24.057 6.702-54.831 17.489-74.252 30.864-16.617 11.439-14.546 23.106-11.55 27.816 13.15 20.689 225.583 13.211 286.912 6.767v-3.061z" fill="#FAAD08" p-id="6107"></path><path d="M496.65061 957.257c80.157 0 153.737-25.019 201.11-62.386 24.057 6.702 54.83 17.489 74.253 30.864 16.616 11.439 14.543 23.106 11.55 27.816-13.15 20.689-225.584 13.211-286.914 6.767v-3.061z" fill="#FAAD08" p-id="6108"></path><path d="M497.12861 474.524c131.934-0.876 237.669-25.783 273.497-35.34 8.541-2.28 13.11-6.364 13.11-6.364 0.03-1.172 0.542-20.952 0.542-31.155C784.27761 229.833 701.12561 57.173 496.64061 57.162 292.15661 57.173 209.00061 229.832 209.00061 401.665c0 10.203 0.516 29.983 0.547 31.155 0 0 3.717 3.821 10.529 5.67 33.078 8.98 140.803 35.139 276.08 36.034h0.972z" fill="#000000" p-id="6109"></path><path d="M860.28261 619.782c-8.12-26.086-19.204-56.506-30.427-85.72 0 0-6.456-0.795-9.718 0.148-100.71 29.205-222.773 47.818-315.792 46.695h-0.962C410.88561 582.017 289.65061 563.617 189.27961 534.698 185.44461 533.595 177.87261 534.063 177.87261 534.063 166.64961 563.276 155.56661 593.696 147.44761 619.782 108.72961 744.168 121.27261 795.644 130.82461 796.798c20.496 2.474 79.78-93.637 79.78-93.637 0 97.66 88.324 247.617 290.576 248.996a718.01 718.01 0 0 1 5.367 0C708.80161 950.778 797.12261 800.822 797.12261 703.162c0 0 59.284 96.111 79.783 93.637 9.55-1.154 22.093-52.63-16.623-177.017" fill="#000000" p-id="6110"></path><path d="M434.38261 316.917c-27.9 1.24-51.745-30.106-53.24-69.956-1.518-39.877 19.858-73.207 47.764-74.454 27.875-1.224 51.703 30.109 53.218 69.974 1.527 39.877-19.853 73.2-47.742 74.436m206.67-69.956c-1.494 39.85-25.34 71.194-53.24 69.956-27.888-1.238-49.269-34.559-47.742-74.435 1.513-39.868 25.341-71.201 53.216-69.974 27.909 1.247 49.285 34.576 47.767 74.453" fill="#FFFFFF" p-id="6111"></path><path d="M683.94261 368.627c-7.323-17.609-81.062-37.227-172.353-37.227h-0.98c-91.29 0-165.031 19.618-172.352 37.227a6.244 6.244 0 0 0-0.535 2.505c0 1.269 0.393 2.414 1.006 3.386 6.168 9.765 88.054 58.018 171.882 58.018h0.98c83.827 0 165.71-48.25 171.881-58.016a6.352 6.352 0 0 0 1.002-3.395c0-0.897-0.2-1.736-0.531-2.498" fill="#FAAD08" p-id="6112"></path><path d="M467.63161 256.377c1.26 15.886-7.377 30-19.266 31.542-11.907 1.544-22.569-10.083-23.836-25.978-1.243-15.895 7.381-30.008 19.25-31.538 11.927-1.549 22.607 10.088 23.852 25.974m73.097 7.935c2.533-4.118 19.827-25.77 55.62-17.886 9.401 2.07 13.75 5.116 14.668 6.316 1.355 1.77 1.726 4.29 0.352 7.684-2.722 6.725-8.338 6.542-11.454 5.226-2.01-0.85-26.94-15.889-49.905 6.553-1.579 1.545-4.405 2.074-7.085 0.242-2.678-1.834-3.786-5.553-2.196-8.135" fill="#000000" p-id="6113"></path><path d="M504.33261 584.495h-0.967c-63.568 0.752-140.646-7.504-215.286-21.92-6.391 36.262-10.25 81.838-6.936 136.196 8.37 137.384 91.62 223.736 220.118 224.996H506.48461c128.498-1.26 211.748-87.612 220.12-224.996 3.314-54.362-0.547-99.938-6.94-136.203-74.654 14.423-151.745 22.684-215.332 21.927" fill="#FFFFFF" p-id="6114"></path><path d="M323.27461 577.016v137.468s64.957 12.705 130.031 3.91V591.59c-41.225-2.262-85.688-7.304-130.031-14.574" fill="#EB1C26" p-id="6115"></path><path d="M788.09761 432.536s-121.98 40.387-283.743 41.539h-0.962c-161.497-1.147-283.328-41.401-283.744-41.539l-40.854 106.952c102.186 32.31 228.837 53.135 324.598 51.926l0.96-0.002c95.768 1.216 222.4-19.61 324.6-51.924l-40.855-106.952z" fill="#EB1C26" p-id="6116"></path></svg> <span>QQ</span> </button><?php }}}?>
                                    <?php 
                                    if($this->plugin->qq){
                                    for ($i = 0; $i < count($site); $i++) {
                                        if($site[$i]['site'] == 'qq') {
                                    ?>
                                    <button onclick="GetUrl('weixins')" type="button" class="btn btn-light"> <svg t="1607251153785" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="4188" width="19" height="19"><path d="M337.387283 341.82659c-17.757225 0-35.514451 11.83815-35.514451 29.595375s17.757225 29.595376 35.514451 29.595376 29.595376-11.83815 29.595376-29.595376c0-18.49711-11.83815-29.595376-29.595376-29.595375zM577.849711 513.479769c-11.83815 0-22.936416 12.578035-22.936416 23.6763 0 12.578035 11.83815 23.676301 22.936416 23.676301 17.757225 0 29.595376-11.83815 29.595376-23.676301s-11.83815-23.676301-29.595376-23.6763zM501.641618 401.017341c17.757225 0 29.595376-12.578035 29.595376-29.595376 0-17.757225-11.83815-29.595376-29.595376-29.595375s-35.514451 11.83815-35.51445 29.595375 17.757225 29.595376 35.51445 29.595376zM706.589595 513.479769c-11.83815 0-22.936416 12.578035-22.936416 23.6763 0 12.578035 11.83815 23.676301 22.936416 23.676301 17.757225 0 29.595376-11.83815 29.595376-23.676301s-11.83815-23.676301-29.595376-23.6763z" fill="#28C445" p-id="4189"></path><path d="M510.520231 2.959538C228.624277 2.959538 0 231.583815 0 513.479769s228.624277 510.520231 510.520231 510.520231 510.520231-228.624277 510.520231-510.520231-228.624277-510.520231-510.520231-510.520231zM413.595376 644.439306c-29.595376 0-53.271676-5.919075-81.387284-12.578034l-81.387283 41.433526 22.936416-71.768786c-58.450867-41.433526-93.965318-95.445087-93.965317-159.815029 0-113.202312 105.803468-201.988439 233.803468-201.98844 114.682081 0 216.046243 71.028902 236.023121 166.473989-7.398844-0.739884-14.797688-1.479769-22.196532-1.479769-110.982659 1.479769-198.289017 85.086705-198.289017 188.67052 0 17.017341 2.959538 33.294798 7.398844 49.572255-7.398844 0.739884-15.537572 1.479769-22.936416 1.479768z m346.265896 82.867052l17.757225 59.190752-63.630058-35.514451c-22.936416 5.919075-46.612717 11.83815-70.289017 11.83815-111.722543 0-199.768786-76.947977-199.768786-172.393063-0.739884-94.705202 87.306358-171.653179 198.289017-171.65318 105.803468 0 199.028902 77.687861 199.028902 172.393064 0 53.271676-34.774566 100.624277-81.387283 136.138728z" fill="#28C445" p-id="4190"></path></svg> <span>微信</span> </button><?php }}}}?>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            更多
                                        </button>
                                        <div class="dropdown-menu" style="min-width: 151.4px; padding: 0px; position: absolute; transform: translate3d(0px, -190px, 0px); top: 0px; left: 0px; will-change: transform;" x-placement="top-start">
                                        <?php 
                                        $html = '';
                                        for ($i = 0; $i < count($site); $i++) {
                                            if($site[$i]['site'] == 'qq') {
                                                continue;
                                            }
                                            if($site[$i]['site'] == 'weixins') {
                                                continue;
                                            }
                                            $html .= '<button type="button" onclick="GetUrl(\''.$site[$i]['site'].'\')" class="btn btn-icon btn-light" style="border-radius: 0;margin-left:0px;"> '.$site[$i]['ico'].' </button>';
                                        }
                                        ?>
                                        
                                        <?php print $html ?>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end card-body -->
                        </div>
                        <!-- end card -->

                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <p class="text-muted">没有账号吗? <a href="./register?from=<?php print urlencode($_GET['from'])?>" class="text-dark ml-1"><b>注册</b></a></p>
                            </div> 
                            <!-- end col -->
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
            let from = '<?php print $_GET['from']?>';
            !function (t) {
                let btn = function(obj,msg,code){
                    if(code == true){
                        obj.html('<i class="mdi mdi-loading mdi-spin"></i> '+msg+' ');
                        obj.attr("disabled",true);
                    }else{
                        obj.html(' '+msg+' ');
                        obj.attr("disabled",false);
                    }
                }
                <?php if($this->plugin->gt == 1 && $this->plugin->gtid && $this->plugin->gtkey) {?>
                let login = function (captchaObj) {
                    captchaObj.appendTo('#captcha');
                    captchaObj.onReady(function () {
                        $("#wait").hide();
                    });
                    captchaObj.onClose(function () {
                        if(!captchaObj.getValidate()){
                            t.NotificationApp.send("登录提示", "您还未完成验证哦", "top-right", "rgba(0,0,0,0.2)", "warning");
                        }
                    });
                    captchaObj.onError(function (error) {
                        t.NotificationApp.send("登录提示", error.msg ? error.msg : '验证码加载失败', "top-right", "rgba(0,0,0,0.2)", "warning");
                    });
                    t.gt = captchaObj;
                };
                <?php }?>
                $("#login").click(function(){
                    let username = $("#username").val();
                    let password = $("#password").val();
                    if(!username) return t.NotificationApp.send("登录提示", "请输入邮箱/用户名", "top-right", "rgba(0,0,0,0.2)", "warning");
                    if(!password) return t.NotificationApp.send("登录提示", "请输入密码", "top-right", "rgba(0,0,0,0.2)", "warning");
                    <?php if($this->plugin->gt == 1 && $this->plugin->gtid && $this->plugin->gtkey) {?>
                    let result = t.gt.getValidate();
                    if (!result) return t.NotificationApp.send("登录提示", "请先完成验证", "top-right", "rgba(0,0,0,0.2)", "warning");
                    <?php }?>
                    $.ajax({
                        url: '<?php print Typecho_Common::url('user/api', Helper::options()->index)?>',
                        type: 'post',
                        dataType: 'json',
                        async: true, 
                        data: {
                            action : 'login',
                            username : username,
                            password : password,
                            <?php if($this->plugin->gt == 1 && $this->plugin->gtid && $this->plugin->gtkey) {?>
                            geetest_challenge: result.geetest_challenge,
                            geetest_validate: result.geetest_validate,
                            geetest_seccode: result.geetest_seccode
                            <?php }?>
                        },
                        beforeSend:function(){
                            btn($("#login"),"登录中",true);
                        }, 
                        complete:function(){
                            <?php if($this->plugin->gt == 1 && $this->plugin->gtid && $this->plugin->gtkey) {?>
                            t.gt.reset();
                            <?php }?>
                            btn($("#login"),"登录",false);
                        },
                        error: function(){
                            <?php if($this->plugin->gt == 1 && $this->plugin->gtid && $this->plugin->gtkey) {?>
                            t.gt.reset();
                            <?php }?>
                            btn($("#login"),"登录",false);
                            t.NotificationApp.send("登录提示", "服务器繁忙", "top-right", "rgba(0,0,0,0.2)", "error");
                        }, 
                        success: function (res) {
                            if(res.code == 1){
                                t.NotificationApp.send("登录提示", "登录成功", "top-right", "rgba(0,0,0,0.2)", "success");
                                setTimeout(function(){
                                    window.location.href = from ? from : "<?php print Typecho_Common::url('/', Helper::options()->index) ?>";
                                }, 1500);
                            }else{
                                t.NotificationApp.send("登录提示", res.msg, "top-right", "rgba(0,0,0,0.2)", "warning");
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
                        info : 'login'
                    },
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
            let OpenUrl = function(url,iWidth,iHeight){
                let iTop = (window.screen.availHeight - 30 - iHeight) / 2;
                let iLeft = (window.screen.availWidth - 10 - iWidth) / 2;
                let open = window.open(url, '_blank', 'height=' + iHeight + ',innerHeight=' + iHeight + ',width=' + iWidth + ',innerWidth=' + iWidth + ',top=' + iTop + ',left=' + iLeft + ',status=no,toolbar=no,menubar=no,location=no,resizable=no,scrollbars=0,titlebar=no');
                if(!open){
                    window.location.href = url;
                }
            }
            function GetUrl(site){
                $.ajax({
                    url: '<?php print Typecho_Common::url('user/api', Helper::options()->index)?>',
                    type: 'post',
                    dataType: 'json',
                    async: true, 
                    data: {
                        action : 'url',
                        site : site,
                        from : from ? from : '<?php print Typecho_Common::url('/', Helper::options()->index) ?>'
                    },
                    beforeSend:function(){
                        
                    }, 
                    complete:function(){
                        
                    },
                    error: function(){
                    
                    }, 
                    success: function (res) {
                        if(res.code == 1){
                            OpenUrl(res.url,res.width,res.height);
                        }else{

                        }
                    }
                });
            }
        </script>
    </body>
</html>