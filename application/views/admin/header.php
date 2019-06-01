<!DOCTYPE html>
<html lang="zxx" class="js">
<head>
	<meta charset="utf-8">
	<meta name="author" content="Shopily">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Legit Shop| Promoting Legitimate and Genuine Online businesses, DON'T FALL VICTIM TO ONLINE SCAMS - BUY FROM GENIUNE AND VERIFIED SELLERS" />
    <meta name="keywords" content="LegitShop.com.ng, Verified Nigerian online businesses, Genuine Nigerian online businesses, how to identify nigerian online scam businesses, get 100% refund if you are scammed online  " />
	<!-- Fav Icon  -->
	<link rel="shortcut icon" href="<?php echo base_url()?>static-content/user/images/favicon.png">
	<!-- Site Title  -->

	<!-- Vendor Bundle CSS -->
	<link rel="stylesheet" href="<?php echo base_url()?>static-content/user/css/vendor.bundle.css?ver=101">
	<!-- Custom styles for this template -->
	<link rel="stylesheet" href="<?php echo base_url()?>static-content/user/css/style.css?ver=101">
	
</head>

<body class="user-dashboard">
    
    
    <div class="topbar">
        <div class="topbar-md d-lg-none">
            <div class="container">
                <div class="d-flex align-items-center justify-content-between">
                    <a href="#" class="toggle-nav">
                        <div class="toggle-icon">
                            <span class="toggle-line"></span>
                            <span class="toggle-line"></span>
                            <span class="toggle-line"></span>
                            <span class="toggle-line"></span>
                        </div>
                    </a><!-- .toggle-nav -->

                    <div class="site-logo">
                        <a href="<?php echo base_url()?>" class="site-brand">
                            <img src="<?php echo base_url()?>static-content/user/images/logo-s2-white2x.png" alt="Legit Shop logo" srcset="<?php echo base_url()?>static-content/user/images/logo-s2-white.png">
                        </a>
                    </div><!-- .site-logo -->
                    
                    <div class="dropdown topbar-action-item topbar-action-user">
                        <a href="#" data-toggle="dropdown"> <img class="icon" src="<?php echo base_url()?>static-content/user/images/user-thumb-sm.png" alt="thumb"> </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="user-dropdown">
                                <div class="user-dropdown-head">
                                    <h6 class="user-dropdown-name"><?php echo $duser->name;?></h6>
                                </div>
                                <ul class="user-dropdown-links">
                                    <li><a href="logout"><i class="ti ti-power-off"></i>Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- .toggle-action -->
                </div><!-- .container -->
            </div><!-- .container -->
        </div><!-- .topbar-md -->
        <div class="container">
            <div class="d-lg-flex align-items-center justify-content-between">
                <div class="topbar-lg d-none d-lg-block">
                    <div class="site-logo">
                        <a href="<?php echo base_url()?>" class="site-brand">
                            <img src="<?php echo base_url()?>static-content/user/images/logo-s2-white2x.png" alt="Legit Shop Logo" srcset="<?php echo base_url()?>static-content/user/images/logo-s2-white.png">
                        </a>
                    </div><!-- .site-logo -->
                </div><!-- .topbar-lg -->

                <div class="topbar-action d-none d-lg-block">
                    <ul class="topbar-action-list">
                        <li class="topbar-action-item topbar-action-link">
                            <a href="<?php echo base_url()?>"><em class="ti ti-home"></em> Go to main site</a>
                        </li><!-- .topbar-action-item -->
                    </ul><!-- .topbar-action-list -->
                </div><!-- .topbar-action -->
            </div><!-- .d-flex -->
        </div><!-- .container -->
    </div>
    <!-- TopBar End -->
    
    
    <div class="user-wraper">
        <div class="container">
            <div class="d-flex">
                <div class="user-sidebar">
                    <div class="user-sidebar-overlay"></div>
                    <div class="user-box d-none d-lg-block">
                        <div class="user-image">
                            <img src="<?php echo base_url()?>static-content/user/images/user-thumb-lg.png" alt="thumb">
                        </div>
                        <h6 class="user-name"><?php echo $duser->name?></h6>
                    </div><!-- .user-box -->
                    <ul class="user-icon-nav">
                        <li><a href="dashboard"><em class="ti ti-dashboard"></em>Dashboard</a></li>
                        <li><a href="users"><em class="ti ti-files"></em>Users  </a></li>

                        <li><a href="application-status"><em class="ti ti-files"></em> Vendors  </a></li>
                        <li><a href="loan-application"><em class="ti ti-pie-chart"></em></a></li>
                        <!-- <li><a href="transactions"><em class="ti ti-control-shuffle"></em>Transactions</a></li> -->

                        <li><a href="referrals"><em class="ti ti-infinite"></em>Referrals</a></li>
                        <!-- <li><a href="accoun"><em class="ti ti-user"></em>Account</a></li> -->
                        <!-- <li><a href="security.html"><em class="ti ti-lock"></em>Security</a></li> -->
                        <li><a href="logout"><em class="ti ti-power-off"></em>Logout</a></li>
                    </ul><!-- .user-icon-nav -->
                    <div class="user-sidebar-sap"></div><!-- .user-sidebar-sap -->
                   
                    <div class="d-lg-none">
                        <div class="user-sidebar-sap"></div>
                        <div class="gaps-1x"></div>
                        <ul class="topbar-action-list">
                            <li class="topbar-action-item topbar-action-link">
                                <a href="<?php echo base_url()?>"><em class="ti ti-home"></em> Go to main site</a>
                            </li><!-- .topbar-action-item -->
                           
                        </ul><!-- .topbar-action-list -->
                    </div>
                </div><!-- .user-sidebar -->