<div id="quickview" class="quickview-wrapper" data-pages="quickview">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs">
        <li>
            <a href="#quickview-alerts" data-toggle="tab">Alerts</a>
        </li>
        <li class="active">
            <a href="#quickview-chat" data-toggle="tab">Contacts</a>
        </li>
    </ul>
    <a class="btn-link quickview-toggle" data-toggle-element="#quickview" data-toggle="quickview"><i class="pg-close"></i></a>
    <!-- Tab panes -->
    <div class="tab-content">
        <!-- BEGIN Alerts !-->
        <div class="tab-pane fade no-padding" id="quickview-alerts">
            <div class="view-port clearfix" id="alerts">
                <!-- BEGIN Alerts View !-->
                <div class="view bg-white">
                    <!-- BEGIN View Header !-->
                    <div class="navbar navbar-default navbar-sm">
                        <div class="navbar-inner">
                            <!-- BEGIN Header Controler !-->
<!--                            <a href="javascript:;" class="inline action p-l-10 link text-master" data-navigate="view" data-view-port="#chat" data-view-animation="push-parrallax">-->
<!--                                <i class="pg-more"></i>-->
<!--                            </a>-->
                            <!-- END Header Controler !-->
                            <div class="view-heading" style="margin: 15px 0px;">
                                Today&#39;s events
                            </div>
                            <!-- BEGIN Header Controler !-->
<!--                            <a href="#" class="inline action p-r-10 pull-right link text-master">-->
<!--                                <i class="pg-search"></i>-->
<!--                            </a>-->
                            <!-- END Header Controler !-->
                        </div>
                    </div>
                    <!-- END View Header !-->
                    <!-- BEGIN Alert List !-->
                    <div data-init-list-view="ioslist" class="list-view boreded list-view-wrapper  no-top-border">

                        <div id="alert_list_wrapper" class="m-b-50" style="display:block;">



                        </div>
                        <!-- BEGIN List Group !-->

                        <!-- END List Group !-->
                    </div>
                    <!-- END Alert List !-->
                </div>
                <!-- EEND Alerts View !-->
            </div>
        </div>
        <!-- END Alerts !-->
        <div class="tab-pane fade in active" id="quickview-chat">
            <div class="view-port clearfix" id="chat">
                <div class="view bg-white">
                    <!-- BEGIN View Header !-->
                    <div class="navbar navbar-default">
                        <div class="navbar-inner">
                            <!-- BEGIN Header Controler !-->
<!--                            <a href="javascript:;" class="inline action p-l-10 link text-master" data-navigate="view" data-view-port="#chat" data-view-animation="push-parrallax">-->
<!--                                <i class="pg-plus"></i>-->
<!--                            </a>-->
                            <!-- END Header Controler !-->
                            <div class="view-heading">
                                Contact List
<!--                                <div class="fs-11">Show All</div>-->
                            </div>
                            <!-- BEGIN Header Controler !-->
                            <a href="#" class="inline action p-r-10 pull-right link text-master" id="search_contact">
                                <i class="pg-search"></i>
                            </a>
                            <!-- END Header Controler !-->
                        </div>
                    </div>
                    <!-- END View Header !-->
                    <div data-init-list-view="ioslist" class="list-view boreded list-view-wrapper no-top-border">
                        <div class="form-group form-group-default" id="search_contact_input_wrapper" style="display:none; margin-bottom: 0px;">
                            <input type="text" class="form-control" placeholder="search" id="search_contact_input">
                        </div>
                        <div id="contact_list_wrapper" class="m-b-50" style="display:block;">



                    </div>
                    </div> <!-- close contact_list_wrapper -->
                </div>
                <!-- BEGIN Conversation View  !-->
                <div class="view chat-view bg-white clearfix contact_hook">
                    <!-- BEGIN Header  !-->
                    <div class="navbar navbar-default">
                        <div class="navbar-inner">
                            <a href="javascript:;" class="link text-master inline action p-l-10" data-navigate="view" data-view-port="#chat" data-view-animation="push-parrallax">
                                <i class="pg-arrow_left"></i>
                            </a>

                            <div class="view-heading">
                                John Smith
                                <div class="fs-11 hint-text">Online</div>
                            </div>
                            <a href="#" class="link text-master inline action p-r-10 pull-right ">
                                <i class="pg-more"></i>
                            </a>
                        </div>
                    </div>
                    <!-- END Header  !-->
                    <!-- BEGIN Conversation  !-->


                    <div class="chat-inner" id="my-conversation">
                        Phone number: +44 7372 983 1298<br/>
                        E-mail: poklop@seznam.cz
                    </div>
                    <!-- BEGIN Conversation  !-->
                    <!-- BEGIN Chat Input  !-->
                    <div class="b-t b-grey bg-white clearfix p-l-10 p-r-10">
                        <div class="row">
                            <div class="col-xs-1 p-t-15">
                                <a href="#" class="link text-master"><i class="fa fa-plus-circle"></i></a>
                            </div>
                            <div class="col-xs-8 no-padding">
                                <input type="text" class="form-control chat-input" data-chat-input="" data-chat-conversation="#my-conversation" placeholder="Say something">
                            </div>
                            <div class="col-xs-2 link text-master m-l-10 m-t-15 p-l-10 b-l b-grey col-top">
                                <a href="#" class="link text-master"><i class="pg-camera"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- END Chat Input  !-->
                </div>
                <!-- END Conversation View  !-->
            </div>
        </div>
    </div>
</div>