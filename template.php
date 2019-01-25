<?php include "template/variables.php"; ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title><?php echo $Brand; ?> - Responsive Web App Kit</title>
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
                                    <h4 class="page-title">Starter Page</h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#"><?php echo $Brand; ?></a>
                                        </li>
                                        <li>
                                            <a href="#">Pages</a>
                                        </li>
                                        <li class="active">
                                            Starter Page
                                        </li>
                                    </ol>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->
                        <h1>hello test</h1>
						<?php
							$dbhost = 'admin.scaninsystem.com'; //'ec2-3-17-162-211.us-east-2.compute.amazonaws.com';
							$dbUser = 'eugen_test';
							$dbPass = 'test123';
							$dbData = 'SRTS';
							$db = new PDO('mysql:host='.$dbhost.';dbname='.$dbData.';charset=utf8mb4',
								$dbUser, $dbPass);
							foreach($db->query('SELECT * FROM Scans') as $row){
								echo $row['id'];
							}
						?>
						</body>
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