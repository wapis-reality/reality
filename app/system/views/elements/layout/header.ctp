<div id="loader" class="pre_loader"></div>

<!-- START PAGE HEADER WRAPPER -->
<!-- START HEADER -->
<div class="header ">
    <!-- START MOBILE CONTROLS -->
    <!-- LEFT SIDE -->
    <div class="pull-left full-height visible-sm visible-xs">
        <!-- START ACTION BAR -->
        <div class="sm-action-bar">
            <a href="#" class="btn-link toggle-sidebar" data-toggle="sidebar">
                <span class="icon-set menu-hambuger"></span>
            </a>
        </div>
        <!-- END ACTION BAR -->
    </div>
    <!-- RIGHT SIDE -->
    <div class="pull-right full-height visible-sm visible-xs">
        <!-- START ACTION BAR -->
        <div class="sm-action-bar">
            <a href="#" class="btn-link" data-toggle="quickview" data-toggle-element="#quickview">
                <span class="icon-set menu-hambuger-plus"></span>
            </a>
        </div>
        <!-- END ACTION BAR -->
    </div>
    <!-- END MOBILE CONTROLS -->
    <div class=" pull-left sm-table">
        <div class="header-inner">
            <div class="brand inline">
                <a href="/"><img alt="" src="/assets/img/logo.png"  style="margin-left: 40px; max-height:45px"></a>
            </div>
            <!-- BEGIN NOTIFICATION DROPDOWN -->
            <?//= $this->renderElement('layout/notification');?>
            <!-- END NOTIFICATION DROPDOWN -->
<!--            <a href="#" class="search-link" data-toggle="search"><i class="pg-search"></i>Type anywhere to <span class="bold">search</span></a>-->
        </div>
    </div>
    <div class=" pull-right">
<!--        <div class="header-inner">-->
<!--            <a href="#" class="btn-link icon-set menu-hambuger-plus m-l-20 sm-no-margin hidden-sm hidden-xs" data-toggle="quickview" data-toggle-element="#quickview" id="load_contact_list"></a>-->
<!--        </div>-->
    </div>
    <div class=" pull-right">
        <!-- START User Info-->
        <?= $this->renderElement('layout/user');?>
        <!-- END User Info-->
    </div>
</div>
<!-- END HEADER -->
<!-- END PAGE HEADER WRAPPER -->