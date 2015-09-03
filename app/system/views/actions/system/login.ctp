
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
