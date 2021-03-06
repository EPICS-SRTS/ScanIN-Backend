<?php $clearance = $database->getClearance($_SESSION["Email"]); ?>
<!-- ========== Left Sidebar Start ========== -->
<div class="left side-menu">
    <div class="slimscroll-menu" id="remove-scroll">


        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metisMenu nav" id="side-menu">
                <li class="menu-title">Navigation</li>
                <?php if ($clearance["Dashboard"]) { ?>
                    <li>
                        <a href="<?php echo $URL_PATH; ?>/home.php"><i class="fas fa-home"></i> <span> Home </span></a>
                    </li>
                <?php }
                if ($clearance["Email"]) { ?>
                    <li>
                        <a href="<?php echo $URL_PATH; ?>/email/SSO.php"><i class="far fa-envelope"></i><span> Email </span></a>
                    </li>
                <?php }
                if ($clearance["Self_Member"]) { ?>
                    <li>
                        <a href="<?php echo $URL_PATH; ?>/self.php"><i class="fas fa-home"></i>
                            <span> Status </span></a>
                    </li>
                <?php }
                if ($clearance["Members"]) { ?>
                    <li>
                        <a href="javascript: void(0);" aria-expanded="true"><i class="fas fa-users"></i>
                            <span> Members </span> <span class="menu-arrow"></span></a>
                        <ul class="nav-second-level nav" aria-expanded="true">
                            <li><a href="<?php echo $URL_PATH; ?>/ActiveMembers.php">Active Members</a></li>
                            <li><a href="<?php echo $URL_PATH; ?>/SuspendedMembers.php">Suspended Members</a></li>
                            <li><a href="<?php echo $URL_PATH; ?>/TerminatedMembers.php">Terminated Members</a></li>
                            <li><a href="<?php echo $URL_PATH; ?>/TemporaryMembers.php">Temporary Member</a></li>
                        </ul>
                    </li>
                <?php }
                if ($clearance["Support"]) { ?>
                    <li>
                        <a href="javascript: void(0);" aria-expanded="true"><i class="far fa-life-ring"></i>
                            <span> Support </span> <span class="menu-arrow"></span></a>
                        <ul class="nav-second-level nav" aria-expanded="true">
                            <li>
                                <a href="<?php echo $URL_PATH; ?>/support/login/login_action/?user=<?php echo $_SESSION["Username"]; ?>&pass=<?php echo md5($_SESSION["password"]); ?>">Ticketing</a>
                            </li>
                            <li><a href="<?php echo $URL_PATH; ?>/contact.php">Contact Us</a></li>
                        </ul>
                    </li>
                <?php }
                if ($clearance["Dashboard"]) { ?>
                    <li>
                        <a href="javascript: void(0);" aria-expanded="true"><i class="fi-briefcase"></i>
                            <span> UI Kit </span> <span class="menu-arrow"></span></a>
                        <ul class="nav-second-level nav" aria-expanded="true">
                            <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/ui-typography.html">Typography</a></li>
                            <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/ui-panels.html">Panels</a></li>
                            <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/ui-buttons.html">Buttons</a></li>
                            <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/ui-modals.html">Modals</a></li>
                            <li>
                                <a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/ui-checkbox-radio.html">Checkboxs-Radios</a>
                            </li>
                            <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/ui-spinners.html">Spinners</a></li>
                            <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/ui-ribbons.html">Ribbons</a></li>
                            <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/ui-portlets.html">Portlets</a></li>
                            <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/ui-tabs.html">Tabs</a></li>
                            <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/ui-progressbars.html">Progress Bars</a>
                            </li>
                            <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/ui-notifications.html">Notification</a>
                            </li>
                            <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/ui-carousel.html">Carousel</a>
                            <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/ui-video.html">Video</a>
                            <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/ui-tooltips-popovers.html">Tooltips &
                                    Popovers</a>
                            </li>
                            <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/ui-images.html">Images</a></li>
                            <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/ui-bootstrap.html">Bootstrap UI</a></li>
                        </ul>
                    </li>
                <?php } ?>
                <li>
                    <a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/tickets.html"><i class="fi-help"></i><span
                                class="badge badge-danger pull-right">New</span>
                        <span> Tickets </span></a>
                </li>

                <li>
                    <a href="javascript: void(0);" aria-expanded="true"><i class="fi-box"></i><span> Icons </span> <span
                                class="menu-arrow"></span></a>
                    <ul class="nav-second-level nav" aria-expanded="true">
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/icons-colored.html">Colored Icons</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/icons-materialdesign.html">Material Design</a>
                        </li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/icons-dripicons.html">Dripicons</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/icons-fontawesome.html">Font awesome</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/icons-feather.html">Feather Icons</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/icons-simple-line.html">Simple line Icons</a>
                        </li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/icons-flags.html">Flag Icons</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/icons-file.html">File Icons</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/icons-pe7.html">PE7 Icons</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/icons-typicons.html">Typicons</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" aria-expanded="true"><i
                                class="fi-bar-graph-2"></i><span> Graphs </span> <span class="menu-arrow"></span></a>
                    <ul class="nav-second-level nav" aria-expanded="true">
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/chart-flot.html">Flot Chart</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/chart-morris.html">Morris Chart</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/chart-google.html">Google Chart</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/chart-echart.html">Echarts</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/chart-chartist.html">Chartist Charts</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/chart-chartjs.html">Chartjs Chart</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/chart-c3.html">C3 Chart</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/chart-justgage.html">Justgage Charts</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/chart-sparkline.html">Sparkline Chart</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/chart-knob.html">Jquery Knob</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" aria-expanded="true"><i class="fi-mail"></i><span> Email </span>
                        <span class="menu-arrow"></span></a>
                    <ul class="nav-second-level nav" aria-expanded="true">
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/email-inbox.html">Inbox</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/email-read.html">Read Email</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/email-compose.html">Compose Email</a></li>
                    </ul>
                </li>

                <li>
                    <a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/taskboard.html"><i class="fi-paper"></i> <span> Task Board </span></a>
                </li>

                <li>
                    <a href="javascript: void(0);" aria-expanded="true"><i class="fi-disc"></i><span
                                class="badge badge-warning pull-right">12</span> <span> Forms </span></a>
                    <ul class="nav-second-level nav" aria-expanded="true">
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/form-elements.html">Form Elements</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/form-advanced.html">Form Advanced</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/form-layouts.html">Form Layouts</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/form-validation.html">Form Validation</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/form-pickers.html">Form Pickers</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/form-wizard.html">Form Wizard</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/form-mask.html">Form Masks</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/form-summernote.html">Summernote</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/form-wysiwig.html">Wysiwig Editors</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/form-typeahead.html">Typeahead</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/form-x-editable.html">X Editable</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/form-uploads.html">Multiple File Upload</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" aria-expanded="true"><i class="fi-layout"></i> <span> Tables </span>
                        <span class="menu-arrow"></span></a>
                    <ul class="nav-second-level nav" aria-expanded="true">
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/tables-basic.html">Basic Tables</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/tables-layouts.html">Tables Layouts</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/tables-datatable.html">Data Tables</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/tables-foo-tables.html">Foo Tables</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/tables-responsive.html">Responsive Table</a>
                        </li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/tables-tablesaw.html">Tablesaw Tables</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/tables-editable.html">Editable Tables</a></li>
                    </ul>
                </li>

                <li class="menu-title">More</li>

                <li>
                    <a href="javascript: void(0);" aria-expanded="true"><i class="fi-map"></i> <span> Maps </span> <span
                                class="menu-arrow"></span></a>
                    <ul class="nav-second-level nav" aria-expanded="true">
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/maps-google.html">Google Maps</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/maps-google-full.html">Full Google Map</a>
                        </li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/maps-vector.html">Vector Maps</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/maps-mapael.html">Mapael Maps</a></li>
                    </ul>
                </li>

                <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/calendar.html"><i class="fi-clock"></i>
                        <span>Calendar</span> </a>
                </li>

                <li>
                    <a href="javascript: void(0);" aria-expanded="true"><i
                                class="fi-paper-stack"></i><span> Pages </span> <span class="menu-arrow"></span></a>
                    <ul class="nav-second-level nav" aria-expanded="true">
                        <li><a href="<?php echo $URL_PATH; ?>/page-starter.html">Starter Page</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/login.php">Login</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/page-register.html">Register</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/page-logout.html">Logout</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/page-recoverpw.html">Recover Password</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/page-lock-screen.html">Lock Screen</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/page-confirm-mail.html">Confirm Mail</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/page-404.html">Error 404</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/page-404-alt.html">Error 404-alt</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/page-500.html">Error 500</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);"><i class="fi-marquee-plus"></i><span> Extra Pages </span> <span
                                class="menu-arrow"></span></a>
                    <ul class="nav-second-level nav" aria-expanded="true">
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/extras-about.html">About Us</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/contact.php">Contact</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/extras-companies.html">Companies</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/extras-members.html">Members</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/extras-members-2.html">Membars 2</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/extras-timeline.html">Timeline</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/extras-invoice.html">Invoice</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/extras-maintenance.html">Maintenance</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/extras-coming-soon.html">Coming Soon</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/extras-faq.html">FAQ</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/extras-pricing.html">Pricing</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/extras-profile.html">Profile</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/extras-email-template.html">Email
                                Templates</a></li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/extras-search-result.html">Search Results</a>
                        </li>
                        <li><a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/extras-sitemap.html">Site Map</a></li>
                    </ul>
                </li>

                <li>
                    <a href="<?php echo $URL_PATH; ?>/TEMPLATE_HTMLs/todo.html"><i class="fi-layers"></i>
                        <span> Todo </span></a>
                </li>

                <li>
                    <a href="javascript: void(0);" aria-expanded="true"><i class="fi-share"></i>
                        <span>Multi Level</span> <span class="menu-arrow"></span></a>
                    <ul class="nav-second-level nav" aria-expanded="true">
                        <li><a href="javascript: void(0);">Level 1.1</a></li>
                        <li><a href="javascript: void(0);" aria-expanded="true">Level 1.2 <span
                                        class="menu-arrow"></span></a>
                            <ul class="nav-third-level nav" aria-expanded="true">
                                <li><a href="javascript: void(0);">Level 2.1</a></li>
                                <li><a href="javascript: void(0);">Level 2.2</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

            </ul>

        </div>
        <!-- Sidebar -->
        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->