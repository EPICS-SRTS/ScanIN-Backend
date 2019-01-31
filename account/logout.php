<?php include "../template/variables.php"; ?>
<?php session_destroy(); ?>
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
    <link rel="shortcut icon" href="<?php echo $URL_PATH; ?>/assets/images/favicon.ico">

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
                                        <span><img src="<?php echo $URL_PATH; ?>/assets/images/ScanIN_Logo_Dark.png" alt="" height="60"></span>
                                    </a>
                                </h2>
                                <!--<h4 class="text-uppercase font-bold m-b-0">Sign In</h4>-->
                            </div>
                            <div class="account-content">
                                <div class="text-center m-b-20">
                                    <div class="m-b-20">
                                        <div class="checkmark">
                                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                                                 x="0px" y="0px"
                                                 viewBox="0 0 161.2 161.2" enable-background="new 0 0 161.2 161.2"
                                                 xml:space="preserve">
                                                    <path class="path" fill="none" stroke="#32c861"
                                                          stroke-miterlimit="10" d="M425.9,52.1L425.9,52.1c-2.2-2.6-6-2.6-8.3-0.1l-42.7,46.2l-14.3-16.4
                                                        c-2.3-2.7-6.2-2.7-8.6-0.1c-1.9,2.1-2,5.6-0.1,7.7l17.6,20.3c0.2,0.3,0.4,0.6,0.6,0.9c1.8,2,4.4,2.5,6.6,1.4c0.7-0.3,1.4-0.8,2-1.5
                                                        c0.3-0.3,0.5-0.6,0.7-0.9l46.3-50.1C427.7,57.5,427.7,54.2,425.9,52.1z"/>
                                                <circle class="path" fill="none" stroke="#32c861" stroke-width="4"
                                                        stroke-miterlimit="10" cx="80.6" cy="80.6" r="62.1"/>
                                                <polyline class="path" fill="none" stroke="#32c861" stroke-width="6"
                                                          stroke-linecap="round" stroke-miterlimit="10" points="113,52.8
                                                        74.1,108.4 48.2,86.4 "/>

                                                <circle class="spin" fill="none" stroke="#32c861" stroke-width="4"
                                                        stroke-miterlimit="10" stroke-dasharray="12.2175,12.2175"
                                                        cx="80.6" cy="80.6" r="73.9"/>

                                                </svg>

                                        </div>
                                    </div>

                                    <h3>See You Again !</h3>

                                    <p class="text-muted font-13 m-t-10"> You are now successfully sign out. Back to <a
                                                href="login.php" class="text-primary m-r-5"><b>Sign In</b></a></p>
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