<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <meta charset="utf-8" />
        <title>系统提示 - <?php print trim(Helper::options()->title)?></title>
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
        </style>
    </head>
    <body class="authentication-bg">

        <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xxl-4 col-lg-5">
                        <div class="card">
                            <!-- Logo -->
                            <div class="card-header text-center" style="color:#FFF">
                                <a href="/">
                                    <span><img src="<?php print $this->plugin->logo ?>" alt="" height="50"></span>
                                </a>
                            </div>

                            <div class="card-body p-4">
                                <div class="text-center">
                                    <h1 class="text-error"><i class="mdi mdi-emoticon-sad"></i></h1>
                                    <h4 class="text-uppercase text-danger mt-3">系统提示</h4>
                                    <p class="text-muted mt-3"><?php echo $msg;?></p>
                                    <a class="btn btn-light mt-3" style="display:none;" href="/"><i class="mdi mdi-reply"></i>返回首页</a>
                                </div>
                            </div> <!-- end card-body-->
                        </div>
                        <!-- end card -->
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
        <script src="<?php print $this->dir ?>/assets/javascript/app.min.js"></script>
    </body>
</html>