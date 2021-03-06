<?php include "template/variables.php";
$_SESSION['tickerr_logged'][0] = md5("ka7640");
$_SESSION['tickerr_logged'][1] = md5("Khalifa@1764");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title><?php echo $Brand; ?> - Homepage</title>
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
                            <h4 class="page-title">Home</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#"><?php echo $Brand; ?></a>
                                </li>
                                <li class="active">
                                    Home
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
                <h1>Welcome, <?php echo $_SESSION["First_Name"]; ?> </h1>
				You are currently signed in with the role:  <?php
				switch ($_SESSION["Clearance_Level"])
				{
					case('1'): echo "Student (1)"; break;
					case('2'): echo "General Administrator (2)"; break;
					default: echo "Systems Administrator (N/A)"; break;
				}
				?>
				<br>
				Let's get started. What would you like to do?
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