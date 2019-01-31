<!DOCTYPE html>
<html>
<head>
    <?php include "template/variables.php"; ?>
    <meta charset="utf-8"/>
    <title><?php echo $Brand; ?> - Responsive Web App Kit</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description"/>
    <meta content="Coderthemes" name="author"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

    <?php include "template/headers.php"; ?>

</head>


<body class="bg-accpunt-pages">

<!-- HOME -->
<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center">

                <div class="wrapper-page">
                    <div class="account-pages">
                        <div class="account-box">

                            <div class="account-logo-box">
                                <h2 class="text-uppercase text-center">
                                    <a href="index.html" class="text-success">
                                        <span><img src="<?php echo $URL_PATH; ?><?php echo $logoDarkPATH;?> <!--/assets/images/ScanIN_Logo_Dark.png"-->
                                                   alt="" height="60"></span>
                                    </a>
                                </h2>
                            </div>

                            <div class="account-content">
                                <h1 class="text-error">404</h1>
                                <h2 class="text-uppercase text-danger m-t-30">Page Not Found</h2>
                                <p class="text-muted m-t-30">Sorry, the requested page could not be found. 
									Please use the button below to return to the homepage.</p>

                                <a class="btn btn-md btn-block btn-primary waves-effect waves-light m-t-20"
                                   href="<?php echo $URL_PATH; ?>/index.php"> Return Home</a>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</section>
<!-- END HOME -->


<script>
    var resizefunc = [];
</script>

<?php include "template/footers.php"; ?>


<script src="<?php echo $URL_PATH; ?>/plugins/bootstrap-select/js/bootstrap-select.min.js"
        type="text/javascript"></script>

</body>
</html>