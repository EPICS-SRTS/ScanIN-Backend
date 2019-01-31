<?php include "template/variables.php"; ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title><?php echo $Brand; ?> - Temporary Members</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="<?php echo $Brand; ?>" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

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
                                    <h4 class="page-title">Temporary Members</h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#"><?php echo $Brand; ?></a>
                                        </li>
                                        <li>
                                            <a href="#">Members</a>
                                        </li>
                                        <li class="active">
                                            Temporary Members
                                        </li>
                                    </ol>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->
                        <h1>hello test</h1>

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