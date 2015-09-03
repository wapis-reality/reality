<style>
    .btn-rounded {
        border-color: #dadada;
        border-radius: 50%;
        color: #5d5d5d;
        font-size: 1em;
        height: 35px;
        line-height: 24px;
        margin-right: 8px;
        text-align: center;
        width: 35px;
    }

    .btn-rounded .fa {
        left: -6px;
        position: relative;
    }

    .nav-tabs.bg-main {

    }

    .nav-tabs.bg-main li {
        background: #2b303b;
        border: none;
        border-radius: 0;
    }
    .nav-tabs.bg-main li > li.active::after {
        background: #2b303b;
    }

    .client_row {
        background: #fbfbfb;
        border-bottom: 1px solid #d9dfe2;
        padding: 15px;
        padding-bottom: 0;
        min-height: 70px;
        position: relative;
    }

    .client_row .arrow {
        display: inline-block;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 1px solid #378ed0;
        color: #378ed0;
        text-align: center;
        margin-right: 10px;
    }

    .client_row .arrow.grey {
        opacity: 0.3;
    }

    .client_row .add_scheme {
        position: absolute;
        top: 17px;
        right: 15px;
    }

    .list_title {
        border-bottom: 1px solid #8fc1de;
        padding: 15px;
    }

    .scheme_row {
        background: #fff;
        border-bottom: 1px solid #d9dfe2;
        padding: 15px;
        position: relative
    }

    .client_row .scheme_row.row_1 {
        border-top: 1px solid #d9dfe2;
        margin-top: 15px
    }

    .client_row .scheme_row .mml {
        display: inline-block;
    }

    .client_row .scheme_row .mml.bg-success-lighter {
        background-color: #d7e8f6;
        background-color: #FFF;
        color: #378ed0;
    }

    .client_row .scheme_row .mml.bg-danger-lighter {
        background-color: #ffd6d6;
        color: #ff3300;
    }

    .client_row .scheme_row .title {
    }

    .effective_date_status {
        border: 1px solid black;
        border-radius: 4px;
        display: block;
        height: 5px;
        width: 75px;
    }

    .effective_date_status .effective_date_progress {
        display: block;
        height: 100%;
    }

    .effective_date_status .effective_date_progress.green {
        background-color: green;
    }

    .effective_date_status .effective_date_progress.red {
        background-color: red;
    }

    .effective_date_status .effective_date_progress.orange {
        background-color: orange;
    }

    .effective_date_tooltip {
        display: none;

    }

    .effective_date_tooltip p {
        line-height: 8px;
    }

    .effective_date_tooltip_arrow {
        border-top: 1px solid white;
        color: #378ed0;
        font-size: 26px;
        left: 65px;
        position: absolute;
        top: 82px;
    }

    .effective_date:hover > .effective_date_tooltip {
        background: white;
        border: 1px solid #378ed0;
        border-radius: 5px;
        display: block;
        left: -25px;
        /*height: 50px;*/
        padding: 10px;
        position: absolute;
        top: -100px;
        width: 150px;
        z-index: 99;
    }

    .module_box {
        cursor: pointer;
    }

    .wrapper {
        position: relative;
        background: #FBFBFB;
        border-radius: 50%;
    }

    .wrapper, .wrapper * {
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
    }

    .wrapper {
        width: 40px;
        height: 40px;
        margin-left: auto;
        margin-right: auto;
    }

    .wrapper .pie {
        width: 50%;
        height: 100%;
        transform-origin: 100% 50%;
        position: absolute;
        background: #FBFBFB;
        border: 3px solid red;
    }

    .wrapper .spinner {
        border-radius: 100% 0 0 100% / 50% 0 0 50%;
        z-index: 200;
        border-right: none;

    }

    .wrapper .filler {
        border-radius: 0 100% 100% 0 / 0 50% 50% 0;
        left: 50%;
        opacity: 0;
        z-index: 100;
        border-left: none;
        /* opacity:1 */
    }

    .wrapper .mask {
        width: 50%;
        height: 100%;
        position: absolute;
        background: inherit;
        opacity: 1;
        z-index: 300;
        border-radius: 50px 0px 0 50px;
        border-color: #ccc;
        border-width: 3px 0 3px 3px;
        border-style: solid;
    }

    .wrapper .bg {
        width: 100%;
        height: 100%;
        border: 3px solid #ccc;
        border-radius: 50%;
    }

    .wrapper .bg span {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        text-align: center;
        font-weight: bold;
        padding-top: 33%;
        z-index: 999;
        font-size: 11px;
    }

    .wrapper.d180 .mask {
        opacity: 0;
    }

    .wrapper.d180 .filler {
        opacity: 1;
    }

    .wrapper.orange .pie {
        border-color: #FAA41A;
    }

    .wrapper.green .pie {
        border-color: #97DA33;
    }

    /**
    progress bar
    **/
    .module_box span.progress {
        background: #f47d7d none repeat scroll 0 0;
        border-top: 1px solid #eee;
        display: block;
        height: 5px;
        left: 0;
        position: absolute;
        right: 0;
    }

    .module_box span.progress span {
        background-color: #97da33;
        display: block;
        height: 100%;
        width: 95%;
    }

    /**
     * date alert
     **/
    .date_alert {

        padding: 2px 4px;
        display: inline-block;
    }

    .date_alert.orange {
        background: #FAA41A;
        color: #fff;;

    }
    .date_alert.red {
        background: red;
        color:#fff;
    }

</style>
<div class="index">
    <!-- START PANEL -->
    <div class="export-options-container pull-right" style="margin-right: 5px">
        <div class="exportOptions">
            <div class="btn-group sm-m-t-10">
                <a onclick="return false;" ng-click="editClient('this');" href="/clients/edit" class="btn btn-default blue" title="Add New Client"><i class="fa  pg-plus"></i> Add Client</a><!-- data-action="domwin" -->
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="panel">
        <div class="panel-heading">
            <div class="panel-title">Dashboard</div>
            <div class="clearfix"></div>
        </div>
        <div class="panel-body p-l-0 p-t-10 p-r-0">
            <div id="an_controller" style="margin-top: 20px;">
                <div class="bold list_title">Client Name</div>
                <div ng-show="clients.length == 0" class="text-center padding-20" style="font-size: 16px; font-style: italic; line-height: 40px">
                    No client was found, for creating of a new client, please click on "Add client" button <br/> [IMAGE_PREVIEW_PLACEHOLDER]
                </div>
                <div class="client_row" ng-repeat="c in clients">
                    <div class="clearfix">
                        <!-- client name -->
                        <div class="col-lg-2 col-md-3 col-sm-4 m-b-10">
                            <a href="#" class="arrow" ng-click="open_close($event)" onclick="return false;" ng-show="schemes[c.id].length > 0"><i class="fa fa-caret-down"></i></a>
                            <a href="#" class="arrow" ng-show="c.mainTopRecord.empty" ><i class="fa pg-minus"></i></a>
                            <span ng-click="editClient(c.id,c.mainTopRecord.allowEdit);" style="cursor:pointer" title="Edit Client Detail">{{c.name}}</span>
                        </div>

                        <!-- status -->
                        <div class="col-lg-2 col-md-2 col-sm-4 m-b-10"><span class="font-montserrat text-uppercase">Status:</span> <br class="hidden-sm"/> {{c.mainTopRecord.status}}</div>

                        <!-- Scheme name -->
                        <div class="col-lg-4 col-md-3 col-sm-4 m-b-10">
                            <span ng-hide="c.mainTopRecord.empty"><span class="font-montserrat text-uppercase">Scheme name:</span> <br class="hidden-sm"/>{{c.mainTopRecord.name}}</span>
                            <a onclick="return false;" class="btn btn-default btn-sm hidden-md hidden-lg" title="Add Scheme" ng-show="client_scheme_add[c.id] == true" ng-click="editClientScheme(c.id, $index)"><i class="fa fa-plus-circle"></i> Add Scheme</a>
                        </div>

                        <!-- effective date -->
                        <div class="col-lg-2 col-md-2 col-sm-4 m-b-10">

                            <span ng-hide="c.mainTopRecord.empty">
                                <span class="font-montserrat text-uppercase">Live from:</span><br class="hidden-sm"/>
                                <span class="date_alert" ng-class="{
                                    'orange': (c.mainTopRecord.effective_date_details.warning == 'warning'),
                                    'red': (c.mainTopRecord.effective_date_details.warning == 'late')
                                }">
                                    <span class="effective_date" tooltip-placement="top" tooltip-html-unsafe="<b>Live</b>: {{c.mainTopRecord.effective_date | date:'dd/MM/yyyy'}}<br/><b>QA</b>: {{c.mainTopRecord.effective_date_details.QA | date:'dd/MM/yyyy'}}<br/><b>UAT</b>: {{c.mainTopRecord.effective_date_details.UAT | date:'dd/MM/yyyy'}}<br/><b>LSD</b>: {{c.mainTopRecord.effective_date_details.LSD | date:'dd/MM/yyyy'}}">
                                        {{c.mainTopRecord.effective_date | date:'dd/MM/yyyy'}}
                                    </span>
                                </span>
                                <span style="display: inline-block" ng-show="c.mainTopRecord.effective_date_details.warning == 'warning'" tooltip-placement="right" tooltip="You almost reach deadline for submission"><i class="fa  fa-info-circle"></i></span>

                                <span style="display: inline-block" ng-show="c.mainTopRecord.effective_date_details.warning == 'late'" tooltip="You missed deadline!!"><i class="fa  fa-info-circle"></i></span>

                            </span>
                        </div>

                        <!-- progress bar -->
                        <div ng-hide="client_scheme_add[c.id] == true" class="col-lg-2 col-md-1 col-sm-2  m-b-10 text-center">
                            <span ng-show="c.mainTopRecord.showProgress">
                                <div class="wrapper" ng-class="{d180: d180(c.mainTopRecord.progressDeg),green: c.mainTopRecord.progress > 70, orange: c.mainTopRecord.progress > 45 && c.mainTopRecord.progress < 70}">
                                    <div class="pie spinner" style=" transform: rotate({{c.mainTopRecord.progressDeg}}deg);"></div>
                                    <div class="pie filler"></div>
                                    <div class="mask"></div>
                                    <div class="bg"><span>{{c.mainTopRecord.progress}}%</span></div>
                                </div>
                            </span>
                        </div>

                        <!-- add scheme -->
                        <div class="col-sm-2 hidden-sm" ng-show="client_scheme_add[c.id] == true">
                            <a onclick="return false;" class="btn btn-default btn-sm" title="Add Scheme" ng-show="client_scheme_add[c.id] == true" ng-click="editClientScheme(c.id, $index)"><i class="fa fa-plus-circle"></i> Add Scheme</a>
                        </div>
                    </div>
                    <div class="block_schemes none">
                        <div class="scheme_row row_{{$index+1}} row" ng-repeat="s in schemes[c.id]">
                            <!-- name -->
                            <div class="title col-md-3 col-lg-2 col-sm-2"><a href="javascript:;" ng-click="editClientScheme(c.id, $index,s.id,s.activated)">{{s.name}}</a></div>

                            <!-- status -->
                            <div class="col-lg-2 col-md-2 col-sm-2">{{s.status_scheme}}</div>

                            <!-- list of modules -->
                            <div class="col-lg-4 col-md-3 col-sm-8">
                                <div class="btn-group">
                                    <a
                                        ng-if="m.module != 'Benefits'"
                                        ng-click="editEvent('/angular_requests/' + m.module + 'ModuleData/' + s.enc_id, c.name + ' > ' + s.name + ':' + getModuleName(m.module), m.module + 'Controller', s.enc_id,s.activated);"
                                        ng-repeat="m in modules[s.id]"

                                        class="module_box btn btn-default btn-sm m-b-5"
                                        onclick="return false;"
                                        title="Module Customization"
                                        >
                                        {{getModuleName(m.module)}}
                                        
                                        <span class="progress" ng-show="s.activated == 0">
                                            <span ng-style="{'width': (m.total_questions == 0) ? '100%' : (((m.answered_questions)*100)/m.total_questions)+'%'}"></span>
                                        </span>
                                    </a>
                                </div>
                            </div>

                            <!-- efective date -->
                            <div class="effective_date col-lg-1 col-md-1 col-sm-3">
                                {{s.effective_date | date:'dd/MM/yyyy'}}
                            </div>

                            <!-- tools -->
                            <div class="col-lg-3 col-md-3 col-sm-9">
                                <div class="btn-group sm-m-t-10  pull-right" tooltip="{{
                                    !isSchemeComplete(s.id)?
                                        'Set-up incomplete':
                                            (s.activated == 1)?
                                                'Scheme has been already submitted':
                                                'Submit scheme'

                                        }}">
                                    <a
                                        onclick="return false;"
                                        class="btn btn-default btn-sm"
                                        ng-disabled="!(s.activated == 0 && isSchemeComplete(s.id))" ng-click="submitScheme(s.id, c.id, $index)"
                                        >
                                        <i class="fa fa-check"></i>
                                        Submit
                                    </a>
                                </div>



                                <div class="rounded-group">
                                    <!-- unlock scheme -->
                                    <a href="javascript: void(0);" class="btn btn-default btn-sm btn-rounded pull-right" tooltip="Unlock Scheme"  title="Unlock Scheme" ng-click="unlockScheme(s.id, c.id,$index)" ng-if="s.activated == 1 && $root.user.group_id == 1"><i class="fa fa-unlock-alt"></i></a>

                                    <a href="javascript: void(0);" class="btn btn-default btn-sm btn-rounded pull-right" tooltip="Renewal"  title="Renewal" ng-click="editClientScheme(c.id, $index,s.id,0,'renew')" ng-show="s.activated == 1 && (s.status_scheme == 'live' || s.status_scheme == 'submitted')"><i class="fa fa-refresh"></i></a>

                                    <a href="javascript: void(0);" class="btn btn-default btn-sm btn-rounded pull-right" tooltip="Preview"  title="Preview" ng-click="preview(c.id,s.id)"><i class="fa fa-desktop"></i></a>
                                    <a ng-click="locationhref('/system/downloadfactfind/'+s.enc_id)" class="btn btn-default btn-sm btn-rounded pull-right" tooltip="Download FactFind" title="Download Scheme"><i class="fa fa-download"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


