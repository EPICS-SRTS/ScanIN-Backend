<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Adminox - Responsive Web App Kit</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description"/>
    <meta content="Coderthemes" name="author"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

    <!-- App favicon -->
    <link rel="shortcut icon" href="../assets/images/favicon.ico">

    <!-- App css -->
    <link href="<?php echo $URL_PATH; ?>/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet"/>
    <link href="<?php echo $URL_PATH; ?>/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo $URL_PATH; ?>/assets/css/core.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo $URL_PATH; ?>/assets/css/components.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo $URL_PATH; ?>/assets/css/icons.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo $URL_PATH; ?>/assets/css/pages.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo $URL_PATH; ?>/assets/css/menu.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo $URL_PATH; ?>/assets/css/responsive.css" rel="stylesheet" type="text/css"/>

    <script src="<?php echo $URL_PATH; ?>/assets/js/modernizr.min.js"></script>

</head>


<body class="bg-accpunt-pages">

<!-- HOME -->
<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">

                <div class="wrapper-page">

                    <div class="account-pages">
                        <div class="account-box">
                            <div class="text-center account-logo-box">
                                <h2 class="text-uppercase">
                                    <a href="<?php echo $URL_PATH; ?>/index.php" class="text-success">
                                        <span><img src="<?php echo $URL_PATH; ?>/assets/images/logo_dark.png" alt="" height="30"></span>
                                    </a>
                                </h2>
                                <!--<h4 class="text-uppercase font-bold m-b-0">Sign In</h4>-->
                            </div>
                            <div class="account-content">
                                <div class="text-center m-b-20">
                                    <p class="text-muted m-b-0">Enter your email address and we'll send you an email
                                        with instructions to reset your password. </p>
                                </div>
                                <form class="form-horizontal" action="#">

                                    <div class="form-group m-b-20">
                                        <div class="col-xs-12">
                                            <label for="emailaddress">Email address</label>
                                            <input class="form-control" type="email" id="emailaddress" required=""
                                                   placeholder="john@deo.com">
                                        </div>
                                    </div>

                                    <div class="form-group text-center m-t-10">
                                        <div class="col-xs-12">
                                            <button class="btn btn-md btn-block btn-primary waves-effect waves-light"
                                                    type="submit">Reset Password
                                            </button>
                                        </div>
                                    </div>

                                </form>

                                <div class="clearfix"></div>

                                <div class="row m-t-40">
                                    <div class="col-sm-12 text-center">
                                        <p class="text-muted">Back to <a href="login.php" class="text-dark m-l-5"><b>Sign
                                                    In</b></a></p>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <!-- end card-box-->
                    </div>


                </div>
                <!-- end wrapper -->

            </div>
        </div>
    </div>
</section>
<!-- END HOME -->


<script>
    var resizefunc = [];
</script>

<!-- jQuery  -->
<script src="<?php echo $URL_PATH; ?>/assets/js/jquery.min.js"></script>
<script src="<?php echo $URL_PATH; ?>/assets/js/bootstrap.min.js"></script>
<script src="<?php echo $URL_PATH; ?>/assets/js/metisMenu.min.js"></script>
<script src="<?php echo $URL_PATH; ?>/assets/js/waves.js"></script>
<script src="<?php echo $URL_PATH; ?>/assets/js/jquery.slimscroll.js"></script>
<script src="<?php echo $URL_PATH; ?>/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>

<!-- App js -->
<script src="<?php echo $URL_PATH; ?>/assets/js/jquery.core.js"></script>
<script src="<?php echo $URL_PATH; ?>/assets/js/jquery.app.js"></script>

</body>
</html>