<?php



//Navigation elements in array
//$navigations = array(
//"HR" => array(
//"/" => array("name" => "Dashboard", "icon_class" => "fa fa-dashboard", "icon" => ""),
//"/employees" => array("name" => "Employees", "icon_class" => "fa fa-male", "icon" => ""),
//"/reports" => array("name" => "Reports", "icon_class" => "fa fa-th-list", "icon" => ""),
//
//),
//
//"Tools" => array(
//"/tasks" => array("name" => "Tasks", "icon_class" => "fa fa-sliders", "icon" => "", "details" => $task_number . " " . $task_text),
//"/calendar" => array("name" => "Calendar", "icon_class" => "fa fa-calendar", "icon" => ""),
//"/messages" => array("name" => "Messages", "icon_class" => "fa  fa-envelope-o", "icon" => "", "details" => $message_number . " " . $message_text),
//"/support" => array("name" => "Support", "icon_class" => "fa fa-life-ring", "icon" => ""),
//"0" => array("name" => "HTML broadcast", "icon_class" => "fa fa-newspaper-o", "icon" => "", "child" => array(
//"/newsletter_campaigns" => array("name" => "Campaigns", "icon_class" => "icon-thumbnail", "icon" => "c"),
//"/newsletter_user_lists" => array("name" => "Recipients lists", "icon_class" => "icon-thumbnail", "icon" => "u"),
//"/newsletter_templates" => array("name" => "Templates", "icon_class" => "icon-thumbnail", "icon" => "t")/*,
//"/newsletter_history"=>array("name"=>"History","icon_class"=>"icon-thumbnail", "icon"=>"h")*/
//)),
//"1" => array("name" => "Nudge Communication", "icon_class" => "pg-social", "icon" => "", "child" => array(
//"/nudge_communications" => array("name" => "Nudge Communication", "icon_class" => "icon-thumbnail", "icon" => "c"),
//"/nudge_communication_history" => array("name" => "History", "icon_class" => "icon-thumbnail", "icon" => "r")
//)),
//"/absence_planners" => array("name" => "Absence Planner", "icon_class" => "fa fa-calendar-o", "icon" => ""),
//),
//
//"Sys" => array(
//"/users" => array("name" => "Users", "icon_class" => "fa fa-user", "icon" => ""),
//"/system_sso_connections" => array("name" => "SSO", "icon_class" => "fa fa-bug", "icon" => ""),
//"/system_ftp_connections" => array("name" => "Connections", "icon_class" => "fa fa-inr", "icon" => ""),
//"/notification" => array("name" => "Notification", "icon_class" => "fa fa-hand-o-right", "icon" => ""),
//"/groups" => array("name" => "Groups", "icon_class" => "fa fa-group", "icon" => ""),
//"/process_communications" => array("name" => "Process Communications", "icon_class" => "fa fa-bolt", "icon" => ""),
//)
//);
//
//if ($this->logged_user['User']['id'] == 3){
//$navigations['SIP'] = array(
//"0" => array("name" => "Benefits", "icon_class" => "fa fa-renren", "icon" => "", "child" => array(
//"/benefits" => array("name" => "Benefits", "icon_class" => "fa fa-th-list", "icon" => "b"),
//"/benefit_events" => array("name" => "Benefit Events", "icon_class" => "icon-thumbnail", "icon" => "e"),
//"/benefit_event_matrices" => array("name" => "Life Events Matrix", "icon_class" => "icon-thumbnail", "icon" => "m"),
//)),
//"/payday" => array("name" => "Payroll calendar", "icon_class" => "fa fa-calendar", "icon" => ""),
//"/cms" => array("name" => "CMS", "icon_class" => "fa fa-th-large", "icon" => ""),
//"1" => array("name" => "Newsroom", "icon_class" => "fa fa-language", "icon" => "", "child" => array(
//"/newsrooms" => array("name" => "Newsroom", "icon_class" => "pg-social", "icon" => "n"),
//"/newsroom_categories" => array("name" => "Newsroom Categories", "icon_class" => "pg-social", "icon" => "c"),
//)),
//"2" => array("name" => "FAQ", "icon_class" => "fa fa-question-circle", "icon" => "", "child" => array(
//"/faq_groups" => array("name" => "FAQ Groups", "icon_class" => "pg-social", "icon" => "c"),
//"/faq_items" => array("name" => "FAQ Items", "icon_class" => "pg-social", "icon" => "r"),
//"/faq_support" => array("name" => "Support", "icon_class" => "pg-social", "icon" => "r"),
//))
//);
//}
$navigations = array(
    "SIP" => array(
        "/" => array("name" => "Dashboard", "icon_class" => "fa fa-dashboard", "icon" => ""),
        "/users" => array("name" => "Users", "icon_class" => "fa fa-male", "icon" => ""),
        "/permission" => array("name" => "Permission", "icon_class" => "fa fa-th-list", "icon" => ""),
        "/request" => array("name" => "Requests", "icon_class" => "fa fa-th-list", "icon" => ""),
    )
);




function activeNav($haystack, $nav = null)
{

$needle = rtrim($_SERVER['REQUEST_URI'],"/");
if($needle == "") { $needle = "/"; } //Dashboard

$result = array_key_exists($needle, $haystack);

if ($result && ($needle == $nav || $nav == null)) return $result;

foreach ($haystack as $v) {
if (is_array($v)) {
$result = array_key_exists_r($needle, $v,$nav);
}

if ($result && ($needle == $nav || $nav == null)) return $result;
}

return false;
}

?>
<div class="menu">

    <!-- UL BLOCKS -->
    <ul class="nav nav-tabs nav-tabs-simple nav-tabs-primary m-t-10">
        <?php
            foreach($navigations as $key => $blocks):
        $active = activeNav($blocks);
        ?>
        <li class="<?= ($active) ? "active" : ""; ?> llmg"><a href="#menu_block_<?= $key ?>" data-toggle="tab"><?= $key ?></a></li>
        <?php
            endforeach;
        ?>
    </ul>

    <!-- NAVIGATIONS -->
    <div class="tab-content" style="background:transparent; border:none; padding-left:5px;">
        <?php
            foreach($navigations as $key => $blocks):
        $active = activeNav($blocks);

        ?>
        <div id="menu_block_<?= $key ?>" class="tab-pane <?= ($active) ? "active" : ""; ?> sidebar-menu" style="background-color: transparent">
        <ul class="menu-items">
            <li class="m-t-30"></li>

            <?php foreach($blocks as $block => $menu): ?>

            <?php
                            if(isset($menu["child"])) { //Child elements for menu

                                $active = activeNav($menu["child"]);
                                ?>
            <li class="<?= ($active) ? "open" : ""; ?>">
            <a href="javascript:;"><span class="title"><?= $menu["name"]; ?></span> <?php if(isset($menu["details"])) { ?> <span class="details"><?= $menu["details"]; ?></span><?php } ?>
                <span class="<?= ($active) ? "active open" : ""; ?>  arrow"></span></a>
            <span class="icon-thumbnail"><i class="<?= $menu["icon_class"]; ?>"></i></span>
            <ul class="sub-menu" <?= ($active) ? 'style="overflow: hidden; display: block;"' : ""; ?> >
            <?php
                                                foreach($menu["child"] as $child_key => $child):
            $active = activeNav($menu["child"],$child_key);
            ?>
            <li class="<?= ($active) ? "active" : ""; ?>">
            <a href="<?= $child_key; ?>"><?= $child["name"]; ?></a>
            <span class="icon-thumbnail"><?= $child["icon"]; ?></span>
            </li>
            <?php endforeach; ?>
        </ul>
        </li>
        <?php
                            } else { //Menu without child elements
                                $active = activeNav($blocks,$block);
                                ?>
        <li <?= ($active) ? "class='open'" : ""; ?> >
        <a href="<?= $block; ?>"><span class="title"><?= $menu["name"]; ?></span> <?php if(isset($menu["details"])) { ?> <span class="details"><?= $menu["details"]; ?></span><?php } ?></a>
        <span class="icon-thumbnail "><i class="<?= $menu["icon_class"]; ?>"></i></span>
        </li>
        <?php
                            }
                        ?>

        <?php endforeach; ?>
        </ul>
    </div>

    <?php
            endforeach;
        ?>
</div>
<div style="position:absolute; bottom: 0px;" class="full-width">
    <button type="button" class="btn btn-info btn-block btn-compose btn-xs" data-href="support/edit/" data-action="domwin" title="New Support Request">
        <span class="glyphicon glyphicon-envelope m-r-5" aria-hidden="true"></span>Create new support request
    </button>
</div>
</div>


<!--<div class="menu">-->
<!--    <ul class="nav nav-tabs nav-tabs-simple nav-tabs-primary m-t-10">-->
<!--        <li class="active llmg"><a href="#menu_block_1" data-toggle="tab">HR</a></li>-->
<!--        <li class="llmg"><a href="#menu_block_2" data-toggle="tab">Tools</a></li>-->
<!--        <li class="llmg"><a href="#menu_block_3" data-toggle="tab">CMS</a></li>-->
<!--        <li class="llmg"><a href="#menu_block_4" data-toggle="tab">Sys</a></li>-->
<!--    </ul>-->

<!--    <div class="tab-content" style="background:transparent; border:none; padding-left:5px;">-->
<!--        <div id="menu_block_1" class="tab-pane active sidebar-menu" style="background-color: transparent">-->
<!--            <ul class="menu-items">-->
<!--                <li class="m-t-30">-->
<!--                    <a href="/"><span class="title">Dashboard</span></a>-->
<!--                    <span class="icon-thumbnail "><i class="pg-home"></i></span>-->
<!--                </li>-->
<!--                <li>-->
<!--                    <a href="/employees"><span class="title">Employees</span></a>-->
<!--                    <span class="icon-thumbnail "><i class="fa fa-male"></i></span>-->
<!--                </li>-->
<!---->
<!--                <li class="">-->
<!--                    <a href="/reports"><span class="title">Reports</span></a>-->
<!--                    <span class="icon-thumbnail "><i class="fa fa-th-list"></i></span>-->
<!--                </li>-->
<!--                <li>-->
<!--                    <a href="javascript:;"><span class="title">Benefits</span>-->
<!--                        <span class="active open arrow"></span></a>-->
<!--                    <span class="icon-thumbnail"><i class="pg-social"></i></span>-->
<!--                    <ul class="sub-menu">-->
<!--                        <li class="">-->
<!--                            <a href="/benefits">Benefits</a>-->
<!--                            <span class="icon-thumbnail">c</span>-->
<!--                        </li>-->
<!--                        <li class="">-->
<!--                            <a href="/benefit_events">Benefit Events</a>-->
<!--                            <span class="icon-thumbnail">r</span>-->
<!--                        </li>-->
<!--                        <li class="active">-->
<!--                            <a href="/benefit_event_matrices">Life Events Matrix</a>-->
<!--                            <span class="icon-thumbnail">t</span>-->
<!--                        </li>-->
<!--                    </ul>-->
<!---->
<!--                </li>-->
<!---->
<!--                <li class="">-->
<!--                    <a href="/payday"><span class="title">Paydays</span></a>-->
<!--                    <span class="icon-thumbnail "><i class="fa fa-th-list"></i></span>-->
<!--                </li>-->
<!--            </ul>-->
<!--        </div>-->
<!--        <div id="menu_block_2" class="tab-pane sidebar-menu">-->
<!--            <ul class="menu-items">-->
<!--                <li class="">-->
<!--                    <a href="/tasks" class="detailed">-->
<!--                        <span class="title">Tasks</span>-->
<!--                        <span class="details">2 notifications</span>-->
<!--                    </a>-->
<!--                    <span class="icon-thumbnail "><i class="pg-social"></i></span>-->
<!--                </li>-->
<!--                <li class="">-->
<!--                    <a href="/calendar"><span class="title">Calendar</span></a>-->
<!--                    <span class="icon-thumbnail "><i class="pg-calender"></i></span>-->
<!--                </li>-->
<!---->
<!--                <li class="">-->
<!--                    <a href="/messages" class="detailed">-->
<!--                        <span class="title">Messages</span>-->
<!--                        <span class="details">12 notifications</span>-->
<!--                    </a>-->
<!--                    <span class="icon-thumbnail "><i class="pg-mail"></i></span>-->
<!--                </li>-->
<!---->
<!--                <li>-->
<!--                    <a href="javascript:;"><span class="title">HTML broadcast</span>-->
<!--                        <span class="active open arrow"></span></a>-->
<!--                    <span class="icon-thumbnail"><i class="pg-social"></i></span>-->
<!--                    <ul class="sub-menu">-->
<!--                        <li class="">-->
<!--                            <a href="/newsletter_campaigns">Campaigns</a>-->
<!--                            <span class="icon-thumbnail">c</span>-->
<!--                        </li>-->
<!--                        <li class="">-->
<!--                            <a href="/newsletter_user_lists">Recipients lists</a>-->
<!--                            <span class="icon-thumbnail">r</span>-->
<!--                        </li>-->
<!--                        <li class="active">-->
<!--                            <a href="/newsletter_templates">Templates</a>-->
<!--                            <span class="icon-thumbnail">t</span>-->
<!--                        </li>-->
<!--                    </ul>-->
<!---->
<!--                </li>-->
<!--                <li>-->
<!--                    <a href="javascript:;"><span class="title">Nudge Communication</span>-->
<!--                        <span class="active open arrow"></span></a>-->
<!--                    <span class="icon-thumbnail"><i class="pg-social"></i></span>-->
<!--                    <ul class="sub-menu">-->
<!--                        <li class="">-->
<!--                            <a href="/nudge_communications">Nudge Communication</a>-->
<!--                            <span class="icon-thumbnail">c</span>-->
<!--                        </li>-->
<!--                        <li class="">-->
<!--                            <a href="/nudge_communication_history">History</a>-->
<!--                            <span class="icon-thumbnail">r</span>-->
<!--                        </li>-->
<!--                    </ul>-->
<!---->
<!--                </li>-->
<!--            </ul>-->
<!--        </div>-->
<!--        <div id="menu_block_3" class="tab-pane sidebar-menu">-->
<!--            <ul class="menu-items">-->
<!--                <li class="">-->
<!--                    <a href="/cms">-->
<!--                        <span class="title">CMS</span>-->
<!--                    </a>-->
<!--                    <span class="icon-thumbnail "><i class="pg-mail"></i></span>-->
<!--                </li>-->
<!--                <li>-->
<!--                    <a href="javascript:;"><span class="title">Newsroom</span>-->
<!--                        <span class="active open arrow"></span></a>-->
<!--                    <span class="icon-thumbnail"><i class="pg-social"></i></span>-->
<!--                    <ul class="sub-menu">-->
<!--                        <li class="">-->
<!--                            <a href="/newsrooms"><span class="title">Newsroom</span></a>-->
<!--                            <span class="icon-thumbnail "><i class="pg-social"></i></span>-->
<!--                        </li>-->
<!--                        <li class="">-->
<!--                            <a href="/newsroom_categories"><span class="title">Newsroom Categories</span></a>-->
<!--                            <span class="icon-thumbnail "><i class="pg-social"></i></span>-->
<!--                        </li>-->
<!--                    </ul>-->
<!--                </li>-->
<!--                <li>-->
<!--                    <a href="javascript:;"><span class="title">FAQ</span>-->
<!--                        <span class="active open arrow"></span></a>-->
<!--                    <span class="icon-thumbnail"><i class="pg-social"></i></span>-->
<!--                    <ul class="sub-menu">-->
<!--                        <li class="">-->
<!--                            <a href="/faq_groups">FAQ Groups</a>-->
<!--                            <span class="icon-thumbnail">c</span>-->
<!--                        </li>-->
<!--                        <li class="">-->
<!--                            <a href="/faq_items">FAQ Items</a>-->
<!--                            <span class="icon-thumbnail">r</span>-->
<!--                        </li>-->
<!--                        <li class="active">-->
<!--                            <a href="/faq_support">FAQ Support</a>-->
<!--                            <span class="icon-thumbnail">t</span>-->
<!--                        </li>-->
<!--                    </ul>-->
<!---->
<!--                </li>-->
<!--            </ul>-->
<!--        </div>-->
<!--        <div id="menu_block_4" class="tab-pane sidebar-menu">-->
<!--            <ul class="menu-items">-->
<!--                <li class="">-->
<!--                    <a href="/users">-->
<!--                        <span class="title">Users</span>-->
<!--                    </a>-->
<!--                    <span class="icon-thumbnail "><i class="pg-mail"></i></span>-->
<!--                </li>-->
<!--                <li class="">-->
<!--                    <a href="/system_sso_connections">-->
<!--                        <span class="title">SSO</span>-->
<!--                    </a>-->
<!--                    <span class="icon-thumbnail "><i class="pg-mail"></i></span>-->
<!--                </li>-->
<!--                <li class="">-->
<!--                    <a href="/system_ftp_connections">-->
<!--                        <span class="title">Connections</span>-->
<!--                    </a>-->
<!--                    <span class="icon-thumbnail "><i class="pg-mail"></i></span>-->
<!--                </li>-->
<!--            </ul>-->
<!--        </div>-->
<!--        <div class="clearfix"></div>-->
<!--    </div>-->
<!--</div>-->

