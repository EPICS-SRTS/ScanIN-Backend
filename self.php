<!-- ============================================================== -->
<!-- Header Starts Here -->
<!-- ============================================================== -->

<!DOCTYPE html>
<html>
<head>
    <?php include "template/variables.php"; ?>
    <meta charset="utf-8"/>
    <title><?php echo $Brand; ?> - Temporary Members</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description"/>
    <meta content="<?php echo $Brand; ?>" name="author"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <?php include "template/headers.php"; ?>

    <!-- ============================================================== -->
    <!-- Header Ends Here -->
    <!-- ============================================================== -->


    <!-- ============================================================== -->
    <!-- Sub-Header Starts Here -->
    <!-- ============================================================== -->

    <!-- Table Responsive css -->
    <link href="<?php echo $URL_PATH; ?>/plugins/responsive-table/css/rwd-table.min.css" rel="stylesheet"
          type="text/css" media="screen">
</head>

<!-- ============================================================== -->
<!-- Sub-Header Ends Here -->
<!-- ============================================================== -->


<body>

<!-- Begin page -->
<div id="wrapper">

    <!-- Top Bar Start -->
    <?php include "template/Top_Bar.php"; ?>
    <!-- Top Bar End -->

    <!-- ========== Left Sidebar Start ========== -->
    <?php include "template/Left_Sidebar.php"; ?>
    <!-- Left Sidebar End -->

    <!-- Start right Content here -->
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

                <?php $table = new \SRTS\Admin\Generator\table(); ?>
                <?php $table->setDatabase($database); ?>
                <?php $table->query("SELECT EID as 'Tag ID', DATE_FORMAT(Timestamp, '%M %d %Y')  as 'Date', DATE_FORMAT(Timestamp, '%h:%i %p')  as 'Time' FROM Scans"); ?>
                <?php $table->generate(); ?>

                <?php $table = new \SRTS\Admin\Generator\table(); ?>
                <?php $table->setDatabase($database); ?>
                <?php $table->query("SELECT * FROM Scans"); ?>
                <?php $table->generate(); ?>


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

<!-- responsive-table-->
<script src="<?php echo $URL_PATH; ?>/plugins/responsive-table/js/rwd-table.min.js" type="text/javascript"></script>

</body>
</html>