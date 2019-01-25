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
                <div class="row">
                    <div class="col-sm-4">
                        <a href="#custom-modal" class="btn btn-custom waves-effect waves-light m-b-20"
                           data-animation="fadein" data-plugin="custommodal"
                           data-overlaySpeed="200" data-overlayColor="#36404a"><i class="md md-add"></i> Add Member</a>
                    </div><!-- end col -->
                    <div class="col-sm-8">
                        <div class="text-right">
                            <ul class="pagination pagination-split m-t-0">
                                <li class="disabled">
                                    <a href="#"><i class="fa fa-angle-left"></i></a>
                                </li>
                                <li class="active">
                                    <a href="#">1</a>
                                </li>
                                <li>
                                    <a href="#">2</a>
                                </li>
                                <li>
                                    <a href="#">3</a>
                                </li>
                                <li>
                                    <a href="#">4</a>
                                </li>
                                <li>
                                    <a href="#">5</a>
                                </li>
                                <li>
                                    <a href="#"><i class="fa fa-angle-right"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- end row -->


                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center card-box">
                            <div class="dropdown pull-right">
                                <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown"
                                   aria-expanded="false">
                                    <h3 class="m-0 text-muted"><i class="mdi mdi-dots-horizontal"></i></h3>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#">Edit</a></li>
                                    <li><a href="#">Delete</a></li>
                                    <li><a href="#">Block</a></li>
                                </ul>
                            </div>
                            <div class="clearfix"></div>
                            <div class="member-card">
                                <div class="thumb-xl member-thumb m-b-10 center-block">
                                    <img src="assets/images/users/avatar-3.jpg" class="img-circle img-thumbnail"
                                         alt="profile-image">
                                    <i class="mdi mdi-star-circle member-star text-success" title="verified user"></i>
                                </div>

                                <div class="">
                                    <h4 class="m-b-5">Julie L. Arsenault</h4>
                                    <p class="text-muted">@Founder <span> | </span> <span> <a href="#"
                                                                                              class="text-pink">websitename.com</a> </span>
                                    </p>
                                </div>

                                <p class="text-muted font-13">
                                    Hi I'm Johnathn Deo,has been the industry's standard dummy text ever since the
                                    1500s, when an unknown printer took a galley of type.
                                </p>

                                <ul class="social-links list-inline m-t-20">
                                    <li>
                                        <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href=""
                                           data-original-title="Facebook"><i class="fa fa-facebook"></i></a>
                                    </li>
                                    <li>
                                        <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href=""
                                           data-original-title="Twitter"><i class="fa fa-twitter"></i></a>
                                    </li>
                                    <li>
                                        <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href=""
                                           data-original-title="Skype"><i class="fa fa-skype"></i></a>
                                    </li>
                                </ul>

                                <button type="button"
                                        class="btn btn-primary m-t-20 btn-rounded btn-bordered waves-effect w-md waves-light">
                                    Follow
                                </button>

                                <div class="m-t-20">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <div class="m-t-20 m-b-10">
                                                <h4 class="m-b-5">2563</h4>
                                                <p class="m-b-0 text-muted">Lifetime total sales</p>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="m-t-20">
                                                <h4 class="m-b-5">6952</h4>
                                                <p class="m-b-0 text-muted">Income amounts</p>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="m-t-20">
                                                <h4 class="m-b-5">1125</h4>
                                                <p class="m-b-0 text-muted">Total visits</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div> <!-- end col -->


                    <div class="col-md-4">
                        <div class="text-center card-box">
                            <div class="dropdown pull-right">
                                <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown"
                                   aria-expanded="false">
                                    <h3 class="m-0 text-muted"><i class="mdi mdi-dots-horizontal"></i></h3>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#">Edit</a></li>
                                    <li><a href="#">Delete</a></li>
                                    <li><a href="#">Block</a></li>
                                </ul>
                            </div>
                            <div class="clearfix"></div>
                            <div class="member-card">
                                <div class="thumb-xl member-thumb m-b-10 center-block">
                                    <img src="assets/images/users/avatar-5.jpg" class="img-circle img-thumbnail"
                                         alt="profile-image">
                                    <i class="mdi mdi-star-circle member-star text-success" title="verified user"></i>
                                </div>

                                <div class="">
                                    <h4 class="m-b-5">Freddie J. Plourde</h4>
                                    <p class="text-muted">@Programmer <span> | </span> <span> <a href="#"
                                                                                                 class="text-pink">websitename.com</a> </span>
                                    </p>
                                </div>

                                <p class="text-muted font-13">
                                    Hi I'm Johnathn Deo,has been the industry's standard dummy text ever since the
                                    1500s, when an unknown printer took a galley of type.
                                </p>

                                <ul class="social-links list-inline m-t-20">
                                    <li>
                                        <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href=""
                                           data-original-title="Facebook"><i class="fa fa-facebook"></i></a>
                                    </li>
                                    <li>
                                        <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href=""
                                           data-original-title="Twitter"><i class="fa fa-twitter"></i></a>
                                    </li>
                                    <li>
                                        <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href=""
                                           data-original-title="Skype"><i class="fa fa-skype"></i></a>
                                    </li>
                                </ul>

                                <button type="button"
                                        class="btn btn-primary m-t-20 btn-rounded btn-bordered waves-effect w-md waves-light">
                                    Follow
                                </button>

                                <div class="m-t-20">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <div class="m-t-20 m-b-10">
                                                <h4 class="m-b-5">265</h4>
                                                <p class="m-b-0 text-muted">Lifetime total sales</p>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="m-t-20">
                                                <h4 class="m-b-5">48847</h4>
                                                <p class="m-b-0 text-muted">Income amounts</p>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="m-t-20">
                                                <h4 class="m-b-5">89484</h4>
                                                <p class="m-b-0 text-muted">Total visits</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div> <!-- end col -->

                    <div class="col-md-4">
                        <div class="text-center card-box">
                            <div class="dropdown pull-right">
                                <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown"
                                   aria-expanded="false">
                                    <h3 class="m-0 text-muted"><i class="mdi mdi-dots-horizontal"></i></h3>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#">Edit</a></li>
                                    <li><a href="#">Delete</a></li>
                                    <li><a href="#">Block</a></li>
                                </ul>
                            </div>
                            <div class="clearfix"></div>
                            <div class="member-card">
                                <div class="thumb-xl member-thumb m-b-10 center-block">
                                    <img src="assets/images/users/avatar-2.jpg" class="img-circle img-thumbnail"
                                         alt="profile-image">
                                    <i class="mdi mdi-star-circle member-star text-success" title="verified user"></i>
                                </div>

                                <div class="">
                                    <h4 class="m-b-5">Christopher Gallardo</h4>
                                    <p class="text-muted">@Webdesigner <span> | </span> <span> <a href="#"
                                                                                                  class="text-pink">websitename.com</a> </span>
                                    </p>
                                </div>

                                <p class="text-muted font-13">
                                    Hi I'm Johnathn Deo,has been the industry's standard dummy text ever since the
                                    1500s, when an unknown printer took a galley of type.
                                </p>

                                <ul class="social-links list-inline m-t-20">
                                    <li>
                                        <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href=""
                                           data-original-title="Facebook"><i class="fa fa-facebook"></i></a>
                                    </li>
                                    <li>
                                        <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href=""
                                           data-original-title="Twitter"><i class="fa fa-twitter"></i></a>
                                    </li>
                                    <li>
                                        <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href=""
                                           data-original-title="Skype"><i class="fa fa-skype"></i></a>
                                    </li>
                                </ul>

                                <button type="button"
                                        class="btn btn-primary m-t-20 btn-rounded btn-bordered waves-effect w-md waves-light">
                                    Follow
                                </button>

                                <div class="m-t-20">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <div class="m-t-20 m-b-10">
                                                <h4 class="m-b-5">9849</h4>
                                                <p class="m-b-0 text-muted">Lifetime total sales</p>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="m-t-20">
                                                <h4 class="m-b-5">94984</h4>
                                                <p class="m-b-0 text-muted">Income amounts</p>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="m-t-20">
                                                <h4 class="m-b-5">7825</h4>
                                                <p class="m-b-0 text-muted">Total visits</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div> <!-- end col -->
                </div>
                <!-- end row -->


                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center card-box">
                            <div class="dropdown pull-right">
                                <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown"
                                   aria-expanded="false">
                                    <h3 class="m-0 text-muted"><i class="mdi mdi-dots-horizontal"></i></h3>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#">Edit</a></li>
                                    <li><a href="#">Delete</a></li>
                                    <li><a href="#">Block</a></li>
                                </ul>
                            </div>
                            <div class="clearfix"></div>
                            <div class="member-card">
                                <div class="thumb-xl member-thumb m-b-10 center-block">
                                    <img src="assets/images/users/avatar-4.jpg" class="img-circle img-thumbnail"
                                         alt="profile-image">
                                    <i class="mdi mdi-star-circle member-star text-success" title="verified user"></i>
                                </div>

                                <div class="">
                                    <h4 class="m-b-5">Joseph M. Rohr</h4>
                                    <p class="text-muted">@Webdesigner <span> | </span> <span> <a href="#"
                                                                                                  class="text-pink">websitename.com</a> </span>
                                    </p>
                                </div>

                                <p class="text-muted font-13">
                                    Hi I'm Johnathn Deo,has been the industry's standard dummy text ever since the
                                    1500s, when an unknown printer took a galley of type.
                                </p>

                                <ul class="social-links list-inline m-t-20">
                                    <li>
                                        <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href=""
                                           data-original-title="Facebook"><i class="fa fa-facebook"></i></a>
                                    </li>
                                    <li>
                                        <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href=""
                                           data-original-title="Twitter"><i class="fa fa-twitter"></i></a>
                                    </li>
                                    <li>
                                        <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href=""
                                           data-original-title="Skype"><i class="fa fa-skype"></i></a>
                                    </li>
                                </ul>

                                <button type="button"
                                        class="btn btn-primary m-t-20 btn-rounded btn-bordered waves-effect w-md waves-light">
                                    Follow
                                </button>

                                <div class="m-t-20">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <div class="m-t-20 m-b-10">
                                                <h4 class="m-b-5">2562</h4>
                                                <p class="m-b-0 text-muted">Lifetime total sales</p>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="m-t-20">
                                                <h4 class="m-b-5">4848</h4>
                                                <p class="m-b-0 text-muted">Income amounts</p>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="m-t-20">
                                                <h4 class="m-b-5">978</h4>
                                                <p class="m-b-0 text-muted">Total visits</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div> <!-- end col -->

                    <div class="col-md-4">
                        <div class="text-center card-box">
                            <div class="dropdown pull-right">
                                <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown"
                                   aria-expanded="false">
                                    <h3 class="m-0 text-muted"><i class="mdi mdi-dots-horizontal"></i></h3>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#">Edit</a></li>
                                    <li><a href="#">Delete</a></li>
                                    <li><a href="#">Block</a></li>
                                </ul>
                            </div>
                            <div class="clearfix"></div>
                            <div class="member-card">
                                <div class="thumb-xl member-thumb m-b-10 center-block">
                                    <img src="assets/images/users/avatar-6.jpg" class="img-circle img-thumbnail"
                                         alt="profile-image">
                                    <i class="mdi mdi-star-circle member-star text-success" title="verified user"></i>
                                </div>

                                <div class="">
                                    <h4 class="m-b-5">Mark K. Horne</h4>
                                    <p class="text-muted">@Director <span> | </span> <span> <a href="#"
                                                                                               class="text-pink">websitename.com</a> </span>
                                    </p>
                                </div>

                                <p class="text-muted font-13">
                                    Hi I'm Johnathn Deo,has been the industry's standard dummy text ever since the
                                    1500s, when an unknown printer took a galley of type.
                                </p>

                                <ul class="social-links list-inline m-t-20">
                                    <li>
                                        <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href=""
                                           data-original-title="Facebook"><i class="fa fa-facebook"></i></a>
                                    </li>
                                    <li>
                                        <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href=""
                                           data-original-title="Twitter"><i class="fa fa-twitter"></i></a>
                                    </li>
                                    <li>
                                        <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href=""
                                           data-original-title="Skype"><i class="fa fa-skype"></i></a>
                                    </li>
                                </ul>

                                <button type="button"
                                        class="btn btn-primary m-t-20 btn-rounded btn-bordered waves-effect w-md waves-light">
                                    Follow
                                </button>

                                <div class="m-t-20">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <div class="m-t-20 m-b-10">
                                                <h4 class="m-b-5">635</h4>
                                                <p class="m-b-0 text-muted">Lifetime total sales</p>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="m-t-20">
                                                <h4 class="m-b-5">59987</h4>
                                                <p class="m-b-0 text-muted">Income amounts</p>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="m-t-20">
                                                <h4 class="m-b-5">49858</h4>
                                                <p class="m-b-0 text-muted">Total visits</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div> <!-- end col -->

                    <div class="col-md-4">
                        <div class="text-center card-box">
                            <div class="dropdown pull-right">
                                <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown"
                                   aria-expanded="false">
                                    <h3 class="m-0 text-muted"><i class="mdi mdi-dots-horizontal"></i></h3>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#">Edit</a></li>
                                    <li><a href="#">Delete</a></li>
                                    <li><a href="#">Block</a></li>
                                </ul>
                            </div>
                            <div class="clearfix"></div>
                            <div class="member-card">
                                <div class="thumb-xl member-thumb m-b-10 center-block">
                                    <img src="assets/images/users/avatar-7.jpg" class="img-circle img-thumbnail"
                                         alt="profile-image">
                                    <i class="mdi mdi-star-circle member-star text-success" title="verified user"></i>
                                </div>

                                <div class="">
                                    <h4 class="m-b-5">James M. Fonville</h4>
                                    <p class="text-muted">@Manager <span> | </span> <span> <a href="#"
                                                                                              class="text-pink">websitename.com</a> </span>
                                    </p>
                                </div>

                                <p class="text-muted font-13">
                                    Hi I'm Johnathn Deo,has been the industry's standard dummy text ever since the
                                    1500s, when an unknown printer took a galley of type.
                                </p>

                                <ul class="social-links list-inline m-t-20">
                                    <li>
                                        <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href=""
                                           data-original-title="Facebook"><i class="fa fa-facebook"></i></a>
                                    </li>
                                    <li>
                                        <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href=""
                                           data-original-title="Twitter"><i class="fa fa-twitter"></i></a>
                                    </li>
                                    <li>
                                        <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href=""
                                           data-original-title="Skype"><i class="fa fa-skype"></i></a>
                                    </li>
                                </ul>

                                <button type="button"
                                        class="btn btn-primary m-t-20 btn-rounded btn-bordered waves-effect w-md waves-light">
                                    Follow
                                </button>

                                <div class="m-t-20">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <div class="m-t-20 m-b-10">
                                                <h4 class="m-b-5">5487</h4>
                                                <p class="m-b-0 text-muted">Lifetime total sales</p>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="m-t-20">
                                                <h4 class="m-b-5">1254</h4>
                                                <p class="m-b-0 text-muted">Income amounts</p>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="m-t-20">
                                                <h4 class="m-b-5">9958</h4>
                                                <p class="m-b-0 text-muted">Total visits</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div> <!-- end col -->
                </div>
                <!-- end row -->


                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center card-box">
                            <div class="dropdown pull-right">
                                <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown"
                                   aria-expanded="false">
                                    <h3 class="m-0 text-muted"><i class="mdi mdi-dots-horizontal"></i></h3>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#">Edit</a></li>
                                    <li><a href="#">Delete</a></li>
                                    <li><a href="#">Block</a></li>
                                </ul>
                            </div>
                            <div class="clearfix"></div>
                            <div class="member-card">
                                <div class="thumb-xl member-thumb m-b-10 center-block">
                                    <img src="assets/images/users/avatar-8.jpg" class="img-circle img-thumbnail"
                                         alt="profile-image">
                                    <i class="mdi mdi-star-circle member-star text-success" title="verified user"></i>
                                </div>

                                <div class="">
                                    <h4 class="m-b-5">Jade M. Walker</h4>
                                    <p class="text-muted">@Webdeveloper <span> | </span> <span> <a href="#"
                                                                                                   class="text-pink">websitename.com</a> </span>
                                    </p>
                                </div>

                                <p class="text-muted font-13">
                                    Hi I'm Johnathn Deo,has been the industry's standard dummy text ever since the
                                    1500s, when an unknown printer took a galley of type.
                                </p>

                                <ul class="social-links list-inline m-t-20">
                                    <li>
                                        <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href=""
                                           data-original-title="Facebook"><i class="fa fa-facebook"></i></a>
                                    </li>
                                    <li>
                                        <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href=""
                                           data-original-title="Twitter"><i class="fa fa-twitter"></i></a>
                                    </li>
                                    <li>
                                        <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href=""
                                           data-original-title="Skype"><i class="fa fa-skype"></i></a>
                                    </li>
                                </ul>

                                <button type="button"
                                        class="btn btn-primary m-t-20 btn-rounded btn-bordered waves-effect w-md waves-light">
                                    Follow
                                </button>

                                <div class="m-t-20">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <div class="m-t-20 m-b-10">
                                                <h4 class="m-b-5">484</h4>
                                                <p class="m-b-0 text-muted">Lifetime total sales</p>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="m-t-20">
                                                <h4 class="m-b-5">5965</h4>
                                                <p class="m-b-0 text-muted">Income amounts</p>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="m-t-20">
                                                <h4 class="m-b-5">2021</h4>
                                                <p class="m-b-0 text-muted">Total visits</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div> <!-- end col -->

                    <div class="col-md-4">
                        <div class="text-center card-box">
                            <div class="dropdown pull-right">
                                <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown"
                                   aria-expanded="false">
                                    <h3 class="m-0 text-muted"><i class="mdi mdi-dots-horizontal"></i></h3>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#">Edit</a></li>
                                    <li><a href="#">Delete</a></li>
                                    <li><a href="#">Block</a></li>
                                </ul>
                            </div>
                            <div class="clearfix"></div>
                            <div class="member-card">
                                <div class="thumb-xl member-thumb m-b-10 center-block">
                                    <img src="assets/images/users/avatar-9.jpg" class="img-circle img-thumbnail"
                                         alt="profile-image">
                                    <i class="mdi mdi-star-circle member-star text-success" title="verified user"></i>
                                </div>

                                <div class="">
                                    <h4 class="m-b-5">Mathias L. Lassen</h4>
                                    <p class="text-muted">@Webdesigner <span> | </span> <span> <a href="#"
                                                                                                  class="text-pink">websitename.com</a> </span>
                                    </p>
                                </div>

                                <p class="text-muted font-13">
                                    Hi I'm Johnathn Deo,has been the industry's standard dummy text ever since the
                                    1500s, when an unknown printer took a galley of type.
                                </p>

                                <ul class="social-links list-inline m-t-20">
                                    <li>
                                        <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href=""
                                           data-original-title="Facebook"><i class="fa fa-facebook"></i></a>
                                    </li>
                                    <li>
                                        <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href=""
                                           data-original-title="Twitter"><i class="fa fa-twitter"></i></a>
                                    </li>
                                    <li>
                                        <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href=""
                                           data-original-title="Skype"><i class="fa fa-skype"></i></a>
                                    </li>
                                </ul>

                                <button type="button"
                                        class="btn btn-primary m-t-20 btn-rounded btn-bordered waves-effect w-md waves-light">
                                    Follow
                                </button>

                                <div class="m-t-20">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <div class="m-t-20 m-b-10">
                                                <h4 class="m-b-5">33625</h4>
                                                <p class="m-b-0 text-muted">Lifetime total sales</p>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="m-t-20">
                                                <h4 class="m-b-5">95995</h4>
                                                <p class="m-b-0 text-muted">Income amounts</p>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="m-t-20">
                                                <h4 class="m-b-5">4414</h4>
                                                <p class="m-b-0 text-muted">Total visits</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div> <!-- end col -->

                    <div class="col-md-4">
                        <div class="text-center card-box">
                            <div class="dropdown pull-right">
                                <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown"
                                   aria-expanded="false">
                                    <h3 class="m-0 text-muted"><i class="mdi mdi-dots-horizontal"></i></h3>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#">Edit</a></li>
                                    <li><a href="#">Delete</a></li>
                                    <li><a href="#">Block</a></li>
                                </ul>
                            </div>
                            <div class="clearfix"></div>
                            <div class="member-card">
                                <div class="thumb-xl member-thumb m-b-10 center-block">
                                    <img src="assets/images/users/avatar-10.jpg" class="img-circle img-thumbnail"
                                         alt="profile-image">
                                    <i class="mdi mdi-star-circle member-star text-success" title="verified user"></i>
                                </div>

                                <div class="">
                                    <h4 class="m-b-5">Alfred M. Bach</h4>
                                    <p class="text-muted">@Manager <span> | </span> <span> <a href="#"
                                                                                              class="text-pink">websitename.com</a> </span>
                                    </p>
                                </div>

                                <p class="text-muted font-13">
                                    Hi I'm Johnathn Deo,has been the industry's standard dummy text ever since the
                                    1500s, when an unknown printer took a galley of type.
                                </p>

                                <ul class="social-links list-inline m-t-20">
                                    <li>
                                        <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href=""
                                           data-original-title="Facebook"><i class="fa fa-facebook"></i></a>
                                    </li>
                                    <li>
                                        <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href=""
                                           data-original-title="Twitter"><i class="fa fa-twitter"></i></a>
                                    </li>
                                    <li>
                                        <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href=""
                                           data-original-title="Skype"><i class="fa fa-skype"></i></a>
                                    </li>
                                </ul>

                                <button type="button"
                                        class="btn btn-primary m-t-20 btn-rounded btn-bordered waves-effect w-md waves-light">
                                    Follow
                                </button>

                                <div class="m-t-20">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <div class="m-t-20 m-b-10">
                                                <h4 class="m-b-5">2563</h4>
                                                <p class="m-b-0 text-muted">Lifetime total sales</p>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="m-t-20">
                                                <h4 class="m-b-5">6952</h4>
                                                <p class="m-b-0 text-muted">Income amounts</p>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="m-t-20">
                                                <h4 class="m-b-5">1125</h4>
                                                <p class="m-b-0 text-muted">Total visits</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div> <!-- end col -->
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