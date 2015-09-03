<ul class="notification-list no-margin hidden-sm hidden-xs b-grey b-l b-r no-style p-l-30 p-r-20">
    <li class="p-r-15 inline">
        <div class="dropdown">
            <a href="javascript:;" id="notification-center" class="icon-set globe-fill" data-toggle="dropdown"><span class="bubble"><span class="notification-number" style="left: 4px; display: block; position: absolute; font-size: 10px; top: -5px;"><?= @$notifications_number; ?></span></span></a>
            <div class="notification-dropdown none" aria-labelledby="notification-center">
                <form class="notification-form">
                    <div class="notification-panel">
                        <div class="notificaitons-items-container"><?= @$notification; ?></div>
                        <div class="notification-footer text-center">
                            <a href="/notification/all" class="">Read all notifications</a>
                            <a data-toggle="refresh" class="portlet-refresh text-black pull-right" href="#"><i class="pg-refresh_new"></i></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </li>
</ul>