<?php include "template/variables.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title><?php echo $Brand; ?> - Responsive Web App Kit</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description"/>
    <meta content="<?php echo $Brand; ?>" name="author"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

    <?php include "template/headers.php"; ?>

</head>

<body>

<!-- Begin page -->
<div id="wrapper">

    <!-- Top Bar Start -->
    <?php include "template/Top_Bar.php"; ?>
    <!-- Top Bar End -->

    <!-- ========== Left Sidebar Start ========== -->
    <?php include "template/Left_Sidebar.php"; ?>
    <!-- Left Sidebar End -->

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Contact Us</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#"><?php echo $Brand; ?></a>
                                </li>
                                <li>
                                    <a href="#">Support</a>
                                </li>
                                <li class="active">
                                    Contact Us
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="contact-map">
                                        <iframe frameborder="0"
                                                marginheight="0" marginwidth="0" scrolling="no" src="https://www.google.com/maps/embed/v1/place?q=Purdue+University&key=AIzaSyBSFRN6WWGYwmFi498qXXsD2UwkbmD74v4"
                                                style="width: 100%; height: 360px;"></iframe>
                                    </div>
                                </div>
                            </div>

                            <div class="row m-t-50 m-b-30">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="row m-b-20">
                                        <div class="col-sm-12">
                                            <h3 class="title">Send us a Message</h3>
                                            <p class="text-muted sub-title">The clean and well commented code allows
                                                easy customization of the theme.It's <br> designed for describing your
                                                app, agency or business.</p>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <!-- Contact form -->
                                        <div class="col-sm-6">
                                            <form action="#" class="contact-form" data-parsley-validate="" method="post"
                                                  name="ajax-form" novalidate="" role="form">

                                                <div class="form-group">
                                                    <input class="form-control" id="name2" name="name"
                                                           placeholder="Your name" required="" type="text" value="">
                                                </div>
                                                <!-- /Form-name -->

                                                <div class="form-group">
                                                    <input class="form-control" id="email2" name="email" placeholder="Your email"
                                                           required="" type="email" value="">
                                                </div>
                                                <!-- /Form-email -->

                                                <div class="form-group">
                                                    <textarea class="form-control" id="message2" name="message" placeholder="Message"
                                                              required="" rows="5"></textarea>
                                                </div>
                                                <!-- /Form Msg -->

                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="">
                                                            <button class="btn btn-primary waves-effect waves-light"
                                                                    id="send"
                                                                    type="submit">Submit
                                                            </button>
                                                        </div>
                                                    </div> <!-- /col -->
                                                </div> <!-- /row -->

                                            </form> <!-- /form -->
                                        </div> <!-- end col -->

                                        <div class="col-sm-4 col-sm-offset-1">
                                            <div class="contact-box">

                                                <div class="contact-detail">
                                                    <i class="mdi mdi-account-location"></i>
                                                    <address>
                                                        610 Purdue Mall<br>
                                                        West Lafayette, IN 47907
                                                    </address>
                                                </div>

                                                <div class="contact-detail">
                                                    <i class=" mdi mdi-cellphone-iphone"></i>
                                                    <p>
                                                        (765) 494-4600
                                                    </p>
                                                </div>

                                                <div class="contact-detail">
                                                    <i class="mdi mdi-email"></i>
                                                    <p>
                                                        <a href="mailto:support@scaninsystem.com">support@scaninsystem.com</a>
                                                    </p>
                                                </div>

                                            </div>
                                        </div> <!-- end col -->
                                    </div>
                                </div>
                            </div>
                            <!-- end row -->
                        </div>
                    </div>
                </div>


            </div> <!-- container -->

        </div> <!-- content -->
        <footer class="footer text-right">
            <?php echo $copyright; ?>
        </footer>

    </div>


    <!-- ============================================================== -->
    <!-- End Right content here -->
    <!-- ============================================================== -->


</div>
<!-- END wrapper -->

<?php include "template/footers.php"; ?>

</body>
</html>