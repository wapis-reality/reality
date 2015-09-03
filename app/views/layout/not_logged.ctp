
<!--    <body class="logged-out">-->
<!--<div class=""-->
<?//= $main_content; ?>

<?= $this->renderElement('layout/head');?>
<body class="fixed-header   ">
<!-- START PAGE-CONTAINER -->
<div class="login-wrapper ">
    <!-- START Login Background Pic Wrapper-->
    <div class="bg-pic">
        <!-- START Background Pic-->
        <img src="/assets/img/admin_login_screen.jpg" alt="" class="lazy" style="opacity: 1">
        <!-- END Background Pic-->
        <!-- START Background Caption-->
        <div class="bg-caption text-white p-l-20 m-b-20 " style="top:20%; left:7%; position: absolute; font-size:25px; width: 50%">
            <h2 class="semi-bold text-white" style="font-size: 40px; font-weight: bold !important;">FirstGroup Reward & Pension </h2>
            <h3 class="semi-bold text-white">Administration portal</h3>



        </div>
        <!-- END Background Caption-->
    </div>
    <!-- END Login Background Pic Wrapper-->
    <!-- START Login Right Container-->
    <div class="login-container bg-white">
        <div class="p-l-50 m-l-20 p-r-50 m-r-20 p-t-50 m-t-30 sm-p-l-15 sm-p-r-15 sm-p-t-40" ng-view>
            <img src="assets/img/logo.png" alt="logo" data-src="assets/img/logo.png" data-src-retina="assets/img/logo.png" height="45" >
            <p class="p-t-35">User login</p>
            <!-- START Login Form -->
<!--            <form id="form-login" class="p-t-15" role="form" method="post" action="/login/">-->
                <!-- START Form Control-->
                <div class="form-group form-group-default">
                    <label>Email</label>
                    <div class="controls">
                        <input type="text" ng-model="login.email" class="form-control" placeholder="Your email" required="true" />
                    </div>
                </div>
                <!-- END Form Control-->
                <!-- START Form Control-->
                <div class="form-group form-group-default">
                    <label>Password</label>
                    <div class="controls">
                        <input type="password" ng-model="login.password" class="form-control" placeholder="Credentials" required="true" />
                    </div>
                </div>
                <!-- START Form Control-->
                <div class="row">
                    <!--div class="col-md-6 no-padding">
                        <div class="checkbox ">
                            <input type="checkbox" value="1" id="checkbox1">
                            <label for="checkbox1">Stay logged</label>
                        </div>
                    </div-->
                    <!--div class="col-md-6 text-right">
                        Forgotten your password?<br>
                        <a href="#" class="text-info small">Click here </a></a>
                    </div>
                    <div class="col-md-6 text-right">
                        Need help?<br>
                        Please call or email  your Rewardr support team 
                    </div-->
                </div>

                <!-- END Form Control-->
                <a href="javascript: void(0);" class="btn btn-primary btn-cons m-t-10" id="login" type="submit" ng-click="editWindow_login('-1','login')">Sign in</a>
<!--            </form>-->

            <!--END Login Form-->
            <div class="pull-bottom sm-pull-bottom">
                <div class="m-b-30 p-r-80 sm-m-t-20 sm-p-r-15 sm-p-b-20 clearfix">
                    <i class="facebook_btn facebook_api"></i>
                    <i class="linkedin_btn linkedin_api"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- END Login Right Container-->
</div>
<!-- END PAGE CONTAINER -->
<!-- BEGIN VENDOR JS -->
<!--<script src="assets/plugins/pace/pace.min.js" type="text/javascript"></script>-->
<!--<script src="assets/plugins/jquery/jquery-1.8.3.min.js" type="text/javascript"></script>-->
<!--<script src="assets/plugins/modernizr.custom.js" type="text/javascript"></script>-->
<!--<script src="assets/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>-->
<!--<script src="assets/plugins/boostrapv3/js/bootstrap.min.js" type="text/javascript"></script>-->
<!--<script src="assets/plugins/jquery/jquery-easy.js" type="text/javascript"></script>-->
<!--<script src="assets/plugins/jquery-unveil/jquery.unveil.min.js" type="text/javascript"></script>-->
<!--<script src="assets/plugins/jquery-bez/jquery.bez.min.js"></script>-->
<!--<script src="assets/plugins/jquery-ios-list/jquery.ioslist.min.js" type="text/javascript"></script>-->
<!--<script src="assets/plugins/jquery-actual/jquery.actual.min.js"></script>-->
<!--<script src="assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js"></script>-->
<!--<script type="text/javascript" src="assets/plugins/bootstrap-select2/select2.min.js"></script>-->
<!--<script type="text/javascript" src="assets/plugins/classie/classie.js"></script>-->
<!--<script src="assets/plugins/switchery/js/switchery.min.js" type="text/javascript"></script>-->
<!--<script src="assets/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>-->
<!-- END VENDOR JS -->
<!-- BEGIN CORE TEMPLATE JS -->
<!--<script src="pages/js/pages.min.js"></script>-->
<!-- END CORE TEMPLATE JS -->
<!--<script type="text/javascript" src="/assets/js/fb_script.js"></script>-->
<!-- BEGIN PAGE LEVEL JS -->
<!--<script src="assets/js/scripts.js" type="text/javascript"></script>-->
<!-- END PAGE LEVEL JS -->
<script>
//    $(function()
//    {
//        $('#form-login').validate()
//    })
</script>
<?= $this->renderElement('layout/scripts');?>
</body>
</html>