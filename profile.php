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
                            <h4 class="page-title"><?php echo $_SESSION['First_Name']; ?>'s Profile</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#"><?php echo $Brand; ?></a>
                                </li>
                                <li>
                                    <a href="#"><?php echo $_SESSION['First_Name']; ?> <?php echo $_SESSION['Last_Name']?></a>
                                </li>
                                <li class="active">
                                    Profile
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-sm-12">
                        <div class="profile-bg-picture" style="background-image:url('assets/images/bg-profile.jpg')">
                            <span class="picture-bg-overlay"></span><!-- overlay -->
                        </div>
                        <!-- meta -->
                        <div class="profile-user-box">
                            <div class="row">
                                <div class="col-sm-6">
                                    <span class="pull-left m-r-15"><img alt="" class="thumb-lg img-circle"
                                                                        src="<?php echo $URL_PATH; ?>/assets/images/users/<?php echo md5($_SESSION['Email']); ?>.png"></span>
                                    <div class="media-body">
                                        <h4 class="m-t-5 m-b-5 ellipsis"><?php echo $_SESSION['First_Name']; ?> <?php echo $_SESSION['Last_Name']?></h4>
                                        <p class="font-13"> User Experience Specialist</p>
                                        <p class="text-muted m-b-0">
                                            <small>California, United States</small>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="text-right">
                                        <button class="btn btn-success waves-effect waves-light" type="button">
                                            <i class="mdi mdi-account-settings-variant m-r-5"></i> Edit Profile
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ meta -->
                    </div>
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-md-4">
                        <!-- Personal-Information -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Personal Information</h3>
                            </div>
                            <div class="panel-body">
                                <p class="text-muted font-13">
                                    Hye, I’m Johnathan Doe residing in this beautiful world. I create websites and
                                    mobile apps with great UX and UI design. I have done work with big companies like
                                    Nokia, Google and Yahoo. Meet me or Contact me for any queries. One Extra line for
                                    filling space. Fill as many you want.
                                </p>

                                <hr/>

                                <div class="text-left">
                                    <p class="text-muted font-13"><strong>Full Name :</strong> <span class="m-l-15">Johnathan Deo</span>
                                    </p>

                                    <p class="text-muted font-13"><strong>Mobile :</strong><span class="m-l-15">(+12) 123 1234 567</span>
                                    </p>

                                    <p class="text-muted font-13"><strong>Email :</strong> <span class="m-l-15">coderthemes@gmail.com</span>
                                    </p>

                                    <p class="text-muted font-13"><strong>Location :</strong> <span
                                                class="m-l-15">USA</span></p>

                                    <p class="text-muted font-13"><strong>Languages :</strong>
                                        <span class="m-l-5">
                                                    <span class="flag-icon flag-icon-us m-r-5 m-t-0" title="us"></span>
                                                    <span>English</span>
                                                </span>
                                        <span class="m-l-5">
                                                    <span class="flag-icon flag-icon-de m-r-5" title="de"></span>
                                                    <span>German</span>
                                                </span>
                                        <span class="m-l-5">
                                                    <span class="flag-icon flag-icon-es m-r-5" title="es"></span>
                                                    <span>Spanish</span>
                                                </span>
                                        <span class="m-l-5">
                                                    <span class="flag-icon flag-icon-fr m-r-5" title="fr"></span>
                                                    <span>French</span>
                                                </span>
                                    </p>

                                </div>

                                <ul class="social-links list-inline m-t-20 m-b-0">
                                    <li>
                                        <a class="tooltips" data-original-title="Facebook" data-placement="top"
                                           data-toggle="tooltip" href=""
                                           title=""><i class="fa fa-facebook"></i></a>
                                    </li>
                                    <li>
                                        <a class="tooltips" data-original-title="Twitter" data-placement="top"
                                           data-toggle="tooltip" href=""
                                           title=""><i class="fa fa-twitter"></i></a>
                                    </li>
                                    <li>
                                        <a class="tooltips" data-original-title="Skype" data-placement="top"
                                           data-toggle="tooltip" href=""
                                           title=""><i class="fa fa-skype"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- Personal-Information -->

                        <div class="card-box ribbon-box">
                            <div class="ribbon ribbon-primary">Messages</div>
                            <div class="clearfix"></div>
                            <div class="inbox-widget">
                                <a href="#">
                                    <div class="inbox-item">
                                        <div class="inbox-item-img"><img alt=""
                                                                         class="img-circle"
                                                                         src="assets/images/users/avatar-2.jpg"></div>
                                        <p class="inbox-item-author">Tomaslau</p>
                                        <p class="inbox-item-text">I've finished it! See you so...</p>
                                        <p class="inbox-item-date m-t-10">
                                            <button class="btn btn-icon btn-xs waves-effect waves-light btn-success"
                                                    type="button">
                                                Reply
                                            </button>
                                        </p>
                                    </div>
                                </a>
                                <a href="#">
                                    <div class="inbox-item">
                                        <div class="inbox-item-img"><img alt=""
                                                                         class="img-circle"
                                                                         src="assets/images/users/avatar-3.jpg"></div>
                                        <p class="inbox-item-author">Stillnotdavid</p>
                                        <p class="inbox-item-text">This theme is awesome!</p>
                                        <p class="inbox-item-date m-t-10">
                                            <button class="btn btn-icon btn-xs waves-effect waves-light btn-success"
                                                    type="button">
                                                Reply
                                            </button>
                                        </p>
                                    </div>
                                </a>
                                <a href="#">
                                    <div class="inbox-item">
                                        <div class="inbox-item-img"><img alt=""
                                                                         class="img-circle"
                                                                         src="assets/images/users/avatar-4.jpg"></div>
                                        <p class="inbox-item-author">Kurafire</p>
                                        <p class="inbox-item-text">Nice to meet you</p>
                                        <p class="inbox-item-date m-t-10">
                                            <button class="btn btn-icon btn-xs waves-effect waves-light btn-success"
                                                    type="button">
                                                Reply
                                            </button>
                                        </p>
                                    </div>
                                </a>

                                <a href="#">
                                    <div class="inbox-item">
                                        <div class="inbox-item-img"><img alt=""
                                                                         class="img-circle"
                                                                         src="assets/images/users/avatar-5.jpg"></div>
                                        <p class="inbox-item-author">Shahedk</p>
                                        <p class="inbox-item-text">Hey! there I'm available...</p>
                                        <p class="inbox-item-date m-t-10">
                                            <button class="btn btn-icon btn-xs waves-effect waves-light btn-success"
                                                    type="button">
                                                Reply
                                            </button>
                                        </p>
                                    </div>
                                </a>
                                <a href="#">
                                    <div class="inbox-item">
                                        <div class="inbox-item-img"><img alt=""
                                                                         class="img-circle"
                                                                         src="assets/images/users/avatar-6.jpg"></div>
                                        <p class="inbox-item-author">Adhamdannaway</p>
                                        <p class="inbox-item-text">This theme is awesome!</p>
                                        <p class="inbox-item-date m-t-10">
                                            <button class="btn btn-icon btn-xs waves-effect waves-light btn-success"
                                                    type="button">
                                                Reply
                                            </button>
                                        </p>
                                    </div>
                                </a>
                            </div>
                        </div>

                    </div>


                    <div class="col-md-8">

                        <div class="row">

                            <div class="col-sm-4">
                                <div class="card-box widget-box-four">
                                    <div class="widget-box-four-chart" id="dashboard-1"></div>
                                    <div class="wigdet-four-content pull-left">
                                        <h4 class="m-t-0 font-16 m-b-5 text-overflow" title="Total Revenue">Total
                                            Revenue</h4>
                                        <p class="font-secondary text-muted">Jan - Apr 2017</p>
                                        <h3 class="m-b-0 m-t-20"><span>$</span> <span
                                                    data-plugin="counterup">1,28,5960</span></h3>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div><!-- end col -->

                            <div class="col-sm-4">
                                <div class="card-box widget-box-four">
                                    <div class="widget-box-four-chart" id="dashboard-2"></div>
                                    <div class="wigdet-four-content pull-left">
                                        <h4 class="m-t-0 font-16 m-b-5 text-overflow" title="Total Unique Visitors">
                                            Total Unique Visitors</h4>
                                        <p class="font-secondary text-muted">Jan - Apr 2017</p>
                                        <h3 class="m-b-0 m-t-20"><span>$</span> <span
                                                    data-plugin="counterup">1,28,5960</span></h3>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div><!-- end col -->

                            <div class="col-sm-4">
                                <div class="card-box widget-box-four">
                                    <div class="widget-box-four-chart" id="dashboard-3"></div>
                                    <div class="wigdet-four-content pull-left">
                                        <h4 class="m-t-0 font-16 m-b-5 text-overflow" title="Number of Transactions">
                                            Number of Transactions</h4>
                                        <p class="font-secondary text-muted">Jan - Apr 2017</p>
                                        <h3 class="m-b-0 m-t-20"><span>$</span> <span
                                                    data-plugin="counterup">1,28,5960</span></h3>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div><!-- end col -->

                        </div>
                        <!-- end row -->


                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Experience</h3>
                            </div>
                            <div class="panel-body">
                                <div class="">
                                    <h5 class="text-custom m-b-5">Lead designer / Developer</h5>
                                    <p class="m-b-0">websitename.com</p>
                                    <p><b>2010-2015</b></p>

                                    <p class="text-muted font-13 m-b-0">Lorem Ipsum is simply dummy text
                                        of the printing and typesetting industry. Lorem Ipsum has
                                        been the industry's standard dummy text ever since the
                                        1500s, when an unknown printer took a galley of type and
                                        scrambled it to make a type specimen book.
                                    </p>
                                </div>

                                <hr>

                                <div class="">
                                    <h5 class="text-custom m-b-5">Senior Graphic Designer</h5>
                                    <p class="m-b-0">coderthemes.com</p>
                                    <p><b>2007-2009</b></p>

                                    <p class="text-muted font-13">Lorem Ipsum is simply dummy text
                                        of the printing and typesetting industry. Lorem Ipsum has
                                        been the industry's standard dummy text ever since the
                                        1500s, when an unknown printer took a galley of type and
                                        scrambled it to make a type specimen book.
                                    </p>
                                </div>

                            </div>
                        </div>

                        <div class="card-box">
                            <div class="table-responsive">
                                <table class="table m-b-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Project Name</th>
                                        <th>Start Date</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Assign</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Adminox Admin</td>
                                        <td>01/01/2015</td>
                                        <td>07/05/2015</td>
                                        <td><span class="label label-info">Work in Progress</span></td>
                                        <td>Coderthemes</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Adminox Frontend</td>
                                        <td>01/01/2015</td>
                                        <td>07/05/2015</td>
                                        <td><span class="label label-success">Pending</span></td>
                                        <td>Coderthemes</td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Adminox Admin</td>
                                        <td>01/01/2015</td>
                                        <td>07/05/2015</td>
                                        <td><span class="label label-pink">Done</span></td>
                                        <td>Coderthemes</td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>Adminox Frontend</td>
                                        <td>01/01/2015</td>
                                        <td>07/05/2015</td>
                                        <td><span class="label label-purple">Work in Progress</span></td>
                                        <td>Coderthemes</td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>Adminox Admin</td>
                                        <td>01/01/2015</td>
                                        <td>07/05/2015</td>
                                        <td><span class="label label-warning">Coming soon</span></td>
                                        <td>Coderthemes</td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <!-- end col -->

                </div>
                <!-- end row -->

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