<style>
    .submenu {
        left: auto;
        right: 120px;;
        top:50px;
        position: absolute;
    }
    .submenu.open {
        display: block !important;
    }

    .dropdown-menu .user-buttons {
        display: block;
        line-height: 35px;
        color: #626262;
        padding: 0 20px;
        border-radius: 3px;
        text-align: left;
        white-space: nowrap;
        cursor: pointer;
    }
</style>
<div class="visible-lg visible-md m-t-10 relative">

    <div class="pull-left p-r-10 p-t-10 fs-14 font-heading">
        <a href="#user_menu"  data-toggle="dropdown">

            <span class="semi-bold"><?=@$_SESSION['user']['first_name']?>&nbsp;<?=@$_SESSION['user']['last_name']?></span>
        </a>
    </div>
    <div class="thumbnail-wrapper d32 circular inline m-t-5">
        <a href="#user_menu"  data-toggle="dropdown">
            <img src="<?=@$_SESSION['user']['image']?>" alt="" data-src="<?=@$_SESSION['user']['image']?>" data-src-retina="<?=@$_SESSION['user']['image']?>" width="38" height="32">
        </a>
    </div>
    <div class="submenu" id="user_menu" role="menu" aria-labelledby="notification-center">
        <ul class="dropdown-menu">
<!--            <li><a href="/users/editModal" data-action="domwin" title="My profile">My profile</a> </li>-->
            <li>
                <edit-button class="user-buttons" data-domwin-icon="" title="My profile" class="text-black" data-placement="top" ng-click="editAuto(<?=@$_SESSION['user']['id']?>,'myprofile','-1')">My profile</edit-button>
            </li>

            <li>
                <edit-button class="user-buttons" data-domwin-icon="" title="My profile" class="text-black" data-placement="top" ng-click="editAuto(<?=@$_SESSION['user']['id']?>,'change_password','-1')">Change password</edit-button>
<!--                <a href="/profile/change_password" data-action="domwin" title="Change password">Change password</a>-->
            </li>

            <li>
                <a href="#" onclick="location.href = '/system/logout'">Logout</a>
            </li>
        </ul>
    </div>


</div>