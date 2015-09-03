<?php include('classes/pages.php');?>
<?= $this->renderElement('layout/head');?>

<body class="fixed-header">
<!-- BEGIN SIDEBAR -->
<div class="page-sidebar" data-pages="sidebar">
    <div id="appMenu" class="sidebar-overlay-slide from-top">
    </div>
    <!-- BEGIN SIDEBAR HEADER -->
    <div class="sidebar-header">
        <!--img src="/assets/img/logo_white.png" alt="logo" class="brand" data-src="" data-src-retina="" style="max-height: 45px"-->
        <div class="sidebar-header-controls">
            <!--button data-pages-toggle="#appMenu" class="btn btn-xs sidebar-slide-toggle btn-link m-l-20" type="button"><i class="fa fa-angle-down fs-16"></i></button-->
            <button data-toggle-pin="sidebar" class="btn btn-link visible-lg-inline" type="button"><i class="fa fs-12"></i></button>
        </div>
    </div>
    <!-- END SIDEBAR HEADER -->
    <!-- BEGIN SIDEBAR MENU -->
    <?= $this->renderElement('layout/menu');?>
    <!-- END SIDEBAR MENU -->
</div>
<!-- END SIDEBAR -->
<!-- START PAGE-CONTAINER -->
<div class="page-container">
    <?= $this->renderElement('layout/header');?>
    <!-- START PAGE CONTENT WRAPPER -->
    <div class="page-content-wrapper">
        <!-- START PAGE CONTENT -->
        <div class="content" >
            <!-- START JUMBOTRON -->
            <div class="jumbotron" data-pages="parallax">
                <div class="container-fluid container-fixed-lg sm-p-l-20 sm-p-r-20">
                    <div class="inner">
                        <!-- START BREADCRUMB -->
                        <ul class="breadcrumb">
                            <li>
                                <p>TITLE</p>
                            </li>
                            <li><a href="#" class="active"></a>
                            </li>
                        </ul>
                        <!-- END BREADCRUMB -->
                    </div>
                </div>
            </div>
            <!-- END JUMBOTRON -->

            <!-- START CONTAINER FLUID -->
            <div class="container-fluid full-width" data-ng-cloak ng-view>
<!--                asda-->
                <!-- BEGIN PlACE PAGE CONTENT HERE -->
<!--                --><?php //echo $main_content;?>
                <!-- END PLACE PAGE CONTENT HERE -->
            </div>
            <!-- END CONTAINER FLUID -->

        </div>
        <!-- END PAGE CONTENT -->
        <!-- START FOOTER -->
        <?= $this->renderElement('layout/footer');?>
        <!-- END FOOTER -->
    </div>
    <!-- END PAGE CONTENT WRAPPER -->
</div>
<!-- END PAGE CONTAINER -->
<!--START QUICKVIEW -->
<?= $this->renderElement('layout/quickview');?>
<!-- END QUICKVIEW-->
<!-- START OVERLAY -->
<?= $this->renderElement('layout/overlay');?>
<!-- END OVERLAY -->
<?= $this->renderElement('layout/scripts');?>

</body>
</html>