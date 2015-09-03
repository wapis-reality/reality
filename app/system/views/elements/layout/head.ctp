<!DOCTYPE html>
<!--<html ng-app="angularRoute" ng-cloak>-->
<html >

<head>

    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <title>Rewardr - Administration portal</title>
    <base href="http://<?= $_SERVER["HTTP_HOST"]; ?>/" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="apple-touch-icon" href="pages/ico/60.png">
    <link rel="apple-touch-icon" sizes="76x76" href="pages/ico/76.png">
    <link rel="apple-touch-icon" sizes="120x120" href="pages/ico/120.png">
    <link rel="apple-touch-icon" sizes="152x152" href="pages/ico/152.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- BEGIN Vendor CSS-->
    <link href="assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/boostrapv3/css/bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/jquery-scrollbar/jquery.scrollbar.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="assets/plugins/bootstrap-select2/select2.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="assets/plugins/switchery/css/switchery.min.css" rel="stylesheet" type="text/css" media="screen" />
    <link media="screen" type="text/css" rel="stylesheet" href="assets/plugins/bootstrap-select2/select2.css"/>
    <link type="text/css" rel="stylesheet" href="assets/plugins/bootstrap3-wysihtml5/bootstrap3-wysihtml5.min.css">
<!--    <link href="assets/css/penkom-font-style.css" rel="stylesheet" type="text/css">-->


    <link media="screen" type="text/css" rel="stylesheet" href="assets/plugins/ion-slider/css/ion.rangeSlider.css">
    <link media="screen" type="text/css" rel="stylesheet" href="assets/plugins/ion-slider/css/ion.rangeSlider.skinFlat.css">

    <!-- BEGIN Pages CSS-->
    <link href="pages/css/pages-icons.css" rel="stylesheet" type="text/css">
    <link class="main-stylesheet" href="pages/css/pages.css" rel="stylesheet" type="text/css" />

    <!-- FILES -->
    <link type="text/css" rel="stylesheet" href="assets/plugins/dropzone/css/dropzone.css">

    <!-- CALENDAR -->
    <link media="screen" type="text/css" rel="stylesheet" href="assets/plugins/bootstrap-datepicker/css/datepicker3.css">

    <!-- SELECT2 -->
    <link media="screen" type="text/css" rel="stylesheet" href="assets/plugins/bootstrap-select2/select2.css">
    <link href="pages/css/library/library.css" rel="stylesheet" type="text/css" />

    <!--[if lte IE 9]>
    <link href="pages/css/ie9.css" rel="stylesheet" type="text/css" />
    <![endif]-->

    <!-- CMS -->
    <link href="assets/css/style.css" rel="stylesheet" type="text/css" />


    <!--<link class="main-stylesheet" href="pages/css/themes/corporate.css" rel="stylesheet" type="text/css" />-->
    <!--<link class="main-stylesheet" href="pages/css/themes/retro.css" rel="stylesheet" type="text/css" />-->
    <!--<link class="main-stylesheet" href="pages/css/themes/unlax.css" rel="stylesheet" type="text/css" />-->
    <link class="main-stylesheet" href="pages/css/themes/first_group.css" rel="stylesheet" type="text/css" />

    <script type="text/javascript">
        window.onload = function() {
            // fix for windows 8
            if (navigator.appVersion.indexOf("Windows NT 6.2") != -1)
                document.head.innerHTML += '<link rel="stylesheet" type="text/css" href="pages/css/windows.chrome.fix.css" />'
        }
    </script>
    <style>
        .pre_loader{
            position: absolute;
            width: 100%;
            height: 100%;
            background: url("images/preloader.gif") no-repeat center;
            z-index: 5000;
            display: none;
        }
        .dropdown-menu li table{
            outline: none;
        }
    </style>
</head>