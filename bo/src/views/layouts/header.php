<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title><?=empty($this->subTitle) ? $this->mainTitle : $this->subTitle ?></title>
    <link rel="icon" type="image/x-icon" href="<?=WEB_PATH?>assets/img/favicon.ico"/>
    <link href="<?=WEB_PATH?>assets/css/loader.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="<?=WEB_PATH?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?=WEB_PATH?>assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <link href="<?=WEB_PATH?>fonts/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?=WEB_PATH?>plugins/sweetalerts/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <link href="<?=WEB_PATH?>plugins/sweetalerts/sweetalert.css" rel="stylesheet" type="text/css" />
    <link href="<?=WEB_PATH?>assets/css/components/custom-sweetalert.css" rel="stylesheet" type="text/css" />
    <link href="<?=WEB_PATH?>assets/css/dashboard/dash_1.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="<?=WEB_PATH?>plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="<?=WEB_PATH?>plugins/table/datatable/dt-global_style.css">
    <link rel="stylesheet" type="text/css" href="<?=WEB_PATH?>plugins/table/datatable/custom_dt_multiple_tables.css">
    <link rel="stylesheet" type="text/css" href="<?=WEB_PATH?>assets/css/forms/switches.css">
    <link href="<?=WEB_PATH?>plugins/flatpickr/flatpickr.css" rel="stylesheet" type="text/css">
    <link href="<?=WEB_PATH?>assets/css/elements/breadcrumb.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="<?=WEB_PATH?>assets/css/forms/theme-checkbox-radio.css">
    <link href="<?=WEB_PATH?>assets/css/elements/search.css" rel="stylesheet" type="text/css" />

    <link href="<?=WEB_PATH?>plugins/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">
    <link href="<?=WEB_PATH?>plugins/animate/animate.css" rel="stylesheet" type="text/css" />
    <link href="<?=WEB_PATH?>assets/css/components/custom-modal.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="<?=WEB_PATH?>plugins/select2/select2.min.css">
    <link href="<?=WEB_PATH?>assets/css/apps/mailing-chat.css" rel="stylesheet" type="text/css" />


</head>
<style type="text/css">
    #DataTables_Table_0_length,#DataTables_Table_0_filter{
       display: none;
    }
    a{
        cursor: pointer;
    }
    tr.expired, tr.expired:hover, tr.expired:hover > td{
background-color: red;
}
.table > tbody > tr.expired > td{
color:white;
}
.table > tbody > tr.expired:hover > td{
color: #888ea8;
}
</style>
<body>
    <!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center">SPCI</div>
    </div></div></div>
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  -->
    <div class="header-container fixed-top">
        <header class="header navbar navbar-expand-sm">

            <ul class="navbar-item theme-brand flex-row  text-center">
                <li class="nav-item theme-logo">
                    <a href="<?=BASEURL?>">
                        <img src="<?=WEB_PATH?>assets/img/90x90.jpg" class="navbar-logo" alt="logo">
                    </a>
                </li>
                <li class="nav-item theme-text">
                    <a href="<?=BASEURL?>" class="nav-link"> SPCI </a>
                </li>
            </ul>

            <ul class="navbar-item flex-row ml-md-auto">

                <li class="nav-item dropdown language-dropdown">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="language-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="<?=WEB_PATH?>assets/img/ca.png" class="flag-width" alt="flag">
                    </a>
                    <div class="dropdown-menu position-absolute" aria-labelledby="language-dropdown">
                        <a class="dropdown-item d-flex" href="javascript:void(0);"><span class="align-self-center">&nbsp;English</span></a>
                    </div>
                </li>

                <li class="nav-item dropdown notification-dropdown">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="notificationDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg><span class="badge badge-success"></span>
                    </a>
                    <div class="dropdown-menu position-absolute" aria-labelledby="notificationDropdown">
                        <div class="notification-scroll">

                            <!-- <div class="dropdown-item">
                                <div class="media">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                    <div class="media-body">
                                        <div class="notification-para"><span class="user-name">Shaun Park</span> likes your photo.</div>
                                    </div>
                                </div>
                            </div> -->

                            <div class="dropdown-item">
                                <div class="media">
                                    <div class="media-body">
                                        <div class="notification-para"><span class="user-name">See All Notications..</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="nav-item dropdown user-profile-dropdown">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <img src="<?=WEB_PATH?>assets/img/90x90.jpg" alt="avatar">
                    </a>
                    <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                        <div class="">
                            <div class="dropdown-item">
                                <a class="" href="<?=BASEURL?>Admin/Profile/?admin=<?= base64_encode($_SESSION['INF_adminID']) ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> My Profile</a>
                            </div>
                            <div class="dropdown-item">
                                <a onclick="logOut()" style="cursor: pointer;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> Sign Out</a>
                            </div>
                        </div>
                    </div>
                </li>

            </ul>
        </header>
    </div>
    <!--  END NAVBAR  -->

    <!--  BEGIN NAVBAR  -->
    <div class="sub-header-container">
        <header class="header navbar navbar-expand-sm">
            <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></a>

            <ul class="navbar-nav flex-row">
                <li>
                    <div class="page-header">

                        <nav class="breadcrumb-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><?=$this->mainTitle?></li>
                                <?php if(!empty($this->subTitle)){ ?>
                                <li class="breadcrumb-item active" aria-current="page"><span><?=$this->subTitle?></span></li>
                                <?php } ?>
                            </ol>
                        </nav>

                    </div>
                </li>
            </ul>
        </header>
    </div>