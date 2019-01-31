<?php include "../template/variables.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>ScanIN Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description"/>
    <meta content="Coderthemes" name="author"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

    <!-- App favicon -->
    <link rel="shortcut icon" href="../assets/images/favicon.ico">

    <!-- App css -->
    <link href="../plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet"/>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="../assets/css/core.css" rel="stylesheet" type="text/css"/>
    <link href="../assets/css/components.css" rel="stylesheet" type="text/css"/>
    <link href="../assets/css/icons.css" rel="stylesheet" type="text/css"/>
    <link href="../assets/css/pages.css" rel="stylesheet" type="text/css"/>
    <link href="../assets/css/menu.css" rel="stylesheet" type="text/css"/>
    <link href="../assets/css/responsive.css" rel="stylesheet" type="text/css"/>

    <script src="../assets/js/modernizr.min.js"></script>

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
                            <div class="account-logo-box">
                                <h2 class="text-uppercase text-center">
                                    <a href="../index.html" class="text-success">
                                        <span><img src="../<?php echo $logoDarkPATH; ?>" alt=""
                                                   height="60"></span>
                                    </a>
                                </h2>
                                <h5 class="text-uppercase font-bold m-b-5 m-t-50">Register</h5>
                                <p class="m-b-0">Get access to our admin panel</p>
                            </div>
                            <div class="account-content">
                                <form class="form-horizontal" action="#">

                                    <div class="form-group m-b-20">
                                        <div class="col-xs-12">
                                            <label for="username">Full Name</label>
                                            <input class="form-control" type="text" id="username" required=""
                                                   placeholder="Matt Boston" required>
                                        </div>
                                    </div>

                                    <div class="form-group m-b-20">
                                        <div class="col-xs-12">
                                            <label for="emailaddress">Email address</label>
                                            <input class="form-control" type="email" id="emailaddress" required=""
                                                   placeholder="dmboston@purdue.edu" required>
                                        </div>
                                    </div>

                                    <div class="form-group m-b-20">
                                        <div class="col-xs-12">
                                            <label for="password">Password</label>
                                            <input class="form-control" type="password" required="" id="password"
                                                   placeholder="Matt's secret password" required>
                                        </div>
                                    </div>

                                    <div class="form-group m-b-20">
                                        <div class="col-xs-12">

                                            <div class="checkbox checkbox-success">
                                                <input id="remember" type="checkbox" required>
                                                <label for="remember">
                                                    I accept <a href="#">Terms and Conditions</a>
                                                </label>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="form-group text-center m-t-10">
                                        <div class="col-xs-12">
                                            <button class="btn btn-md btn-block btn-primary waves-effect waves-light"
                                                    type="submit">Sign Up Free
                                            </button>
                                        </div>
                                    </div>

                                </form>

                                <div class="row m-t-50">
                                    <div class="col-sm-12 text-center">
                                        <p class="text-muted">Already have an account? <a href="login.php"
                                                                                          class="text-dark m-l-5"><b>Sign
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

<?php include "template/footers.php"; ?>


</body>
</html>