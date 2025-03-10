<!DOCTYPE html>

<html lang="en" class=" sidebar-dark sidebar-left-big-icons">

<head>
    <base href="<?= URLROOT ?>">
    <!-- BASIC -->
    <meta charset="UTF-8">
    <meta name="keywords" content="School Management Software" />
    <meta name="description" content="School Management System (ERP)">
    <meta name="author" content="BFTech | Beyond Future Technologies.">
    <!-- MOBILE METAS -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <!-- WEB FONTS  -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

    <!-- VENDOR CSS -->
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/vendor/bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/vendor/font-awesome/css/font-awesome.css" />
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/vendor/magnific-popup/magnific-popup.css" />
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/vendor/bootstrap-switch/css/bootstrap-switch.min.css" />

    <!-- SPECIFIC PAGE VENDOR CSS -->
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/vendor/jquery-ui/jquery-ui.css" />
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/vendor/jquery-ui/jquery-ui.theme.css" />
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/vendor/select2/css/select2.css" />
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/vendor/select2-bootstrap-theme/select2-bootstrap.min.css" />
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css" />
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/vendor/bootstrap-tagsinput/bootstrap-tagsinput.css" />
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/vendor/bootstrap-colorpicker/css/bootstrap-colorpicker.css" />
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/vendor/bootstrap-timepicker/css/bootstrap-timepicker.css" />
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/vendor/dropzone/basic.css" />
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/vendor/dropzone/dropzone.css" />
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/vendor/bootstrap-markdown/css/bootstrap-markdown.min.css" />
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/vendor/summernote/summernote.css" />
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/vendor/elusive-icons/css/elusive-icons.min.css" />

    <!-- SWEETALERT JS/CSS -->
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/sweetalert/sweetalert_custom.css">
    <script src="<?= PARENTROOT ?>/assets/sweetalert/sweetalert.min.js"></script>

    <!-- PNOTIFY NOTIFICATIONS CSS -->
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/vendor/pnotify/pnotify.custom.css" />

    <!-- DATATABLES PAGE CSS -->
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />

    <!-- FILEUPLOAD PAGE CSS -->
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/vendor/bootstrap-fileupload/bootstrap-fileupload.min.css" />

    <!-- FULLCALENDAR CSS -->
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/vendor/fullcalendar/fullcalendar.css" />

    <!-- THEME CSS -->
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/stylesheets/theme.css" />

    <!-- SKIN CSS -->
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/stylesheets/skins/default.css" />

    <!-- THEME CUSTOM CSS -->
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/stylesheets/theme-custom.css">

    <!-- PVS SYSTEMS CSS -->
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/stylesheets/pvs-systems.css">

    <!-- HEAD LIBS -->
    <script src="<?= PARENTROOT ?>/assets/vendor/modernizr/modernizr.js"></script>

    <!-- JQUERY LIBS -->
    <script src="<?= PARENTROOT ?>/assets/vendor/jquery/jquery.js"></script>

    <!--WEB ICON-->
    <link rel="shortcut icon" href="<?= PARENTROOT ?>/assets/images/favicon.png">

    <!--Print Stylesheet C    -->
    <link rel="stylesheet" media="print" href="<?= PARENTROOT ?>/assets/stylesheets/print.css" />

    <!-- DISABLE SQUARE BORDERS -->

    <!--HIGHCHARTS-->
    <script src="<?= PARENTROOT ?>/assets/vendor/highcharts/-highcharts.js" type="text/javascript"></script>

    <!-- Axios the HTTP library -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- PNOTIFY NOTIFICATIONS JS -->
    <script src="<?= PARENTROOT ?>/assets/vendor/pnotify/pnotify.custom.js"></script>
    <!-- CKEditor JS -->
    <script src="//cdn.ckeditor.com/4.16.2/full/ckeditor.js"></script>

    <script>
        const BASEURL = "<?php echo URLROOT ?>";
        const OBJECTIVE_MARKS = <?php echo $_ENV['MCQ_MARKS']?>;
        const SUBJECTIVE_MARKS = <?php echo $_ENV['LONG_MARKS']?>;
    </script>

    <!-- NUMBER SPINNERS DISABLE -->

</head>

<body>


    <section class="body">
        <!-- INCLUDEING HEADER -->

        <?php if (Auth::isLogin()): ?>
        <!-- START: HEADER -->
        <header class="header">
            <div class="logo-container">
                <a href="https://mgs.gptech.pk/dashboard.php" class="logo">
                    <img src="<?= $_SESSION['userlogininfo']['LOGINCAMPUSLOGO'] ?>" height="40" /> 
                    <?= $_SESSION['userlogininfo']['LOGINCAMPUSNAME'] ?>
                    
                </a>
                <div class="visible-xs toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
                    <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
                </div>
            </div>

            <!-- SEARCH & USER BOX -->
            <div class="header-right">

                <span class="separator"></span>
                <ul class="notifications">
                    <!-- SESSION CHANGER -->
                    <li>
                        <a href="#modalAnim" class="modal-with-move-anim notification-icon"><i class="fa fa-calendar"></i></a>
                        <div id="modalAnim" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
                            <section class="panel panel-featured panel-featured-primary">
                                <form action="#" class="validate" method="post" accept-charset="utf-8">
                                    <header class="panel-heading">
                                        <h4 class="panel-title">Running Session : 2020-2021</h4>
                                    </header>
                                    <footer class="panel-footer">
                                        <div class="row">
                                            <div class="col-md-12 text-right">
                                                <button class="btn btn-default modal-dismiss">Cancel</button>
                                            </div>
                                        </div>
                                    </footer>
                                </form>
                            </section>
                        </div>
                    </li>
                    <!-- MESSAGE NOTIFICATIONS -->
                    <li>
                        <a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown"><i class="fa fa-bell"></i></a>
                    </li>
                </ul>

                <span class="separator"></span>
                <div id="userbox" class="userbox">
                    <a href="#" data-toggle="dropdown">
                        <figure class="profile-picture">
                            <img src="<?= URLROOT ?>/uploads/admin_image/default.jpg" alt="user-image" class="img-circle" data-lock-picture="assets/images/logo.png" />
                        </figure>
                        <div class="profile-info" data-lock-name="Admin" >
                            <span class="name"><?= Auth::username()?></span>
                        </div>
                        <i class="fa custom-caret"></i>
                    </a>
                    <div class="dropdown-menu">
                        <ul class="list-unstyled">
                            <li class="divider"></li>
                            <li><a role="menuitem" tabindex="-1" href="<?=
                            URLROOT ?>"><i class="fa fa-wrench"></i> Settings</a></li>
                            <li><a role="menuitem" tabindex="-1" href="https://mgs.gptech.pk/profile.php"><i class="fa fa-user"></i> Edit Profile</a>
                            </li>
                            <li><a role="menuitem" tabindex="-1" href="https://mgs.gptech.pk/index.php?logout"><i class="fa fa-power-off"></i>Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>
        <!-- END: HEADER -->
        <?php endif; ?>


        <div class="inner-wrapper">
            <!-- INCLUDEING NAVIGATION -->

            <?php if (Auth::isLogin()): ?>
            <?php Utils::include("/views/inc/sidebar.php") ?>
            <?php endif; ?>

            <title> Dashboard | School Management System</title>
            <!-- <section role="main" class="content-body-box"> -->

                <?php
                $flash  = Utils::getFlash();

                if (!$flash) return;

                $type = $flash['type'];
                $message = $flash['message'];

                $className = 'alert alert-message ';

                if ($flash['type'] == 'error')
                    $title = 'Error';
                    $className .= "alert-danger";

                if ($flash['type'] == 'info')
                    $title = 'Info';
                    $className .= "alert-info";

                if ($flash['type'] == 'success')
                    $title = 'Success';
                    $className .= "alert-success";
                ?>
                <script>
                    $('ready', function (){
                        new PNotify({
                            title   : "<?=$title?>"   ,
                            text    : "<?=$message?>"    ,
                            type    : "<?=$type?>"    ,
                            hide    : true  ,
                            buttons: {
                                closer  : true  ,
                                sticker : false
                            }
                        });
                    })
                </script>
<!--                <div class="--><?//= $className ?><!--">--><?//= $flash['message'] ?><!--</div>-->


