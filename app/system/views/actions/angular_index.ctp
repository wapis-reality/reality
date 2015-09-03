<style>
    ul#tab-3 li:last-child {
        border-bottom: 1px solid #cdd6db;
    }
</style>
<div class="index">
    <div class="row" ng-cloak>
        <ul id="tab-3" class="nav nav-tabs nav-tabs-simple nav-tabs-left bg-main">
            <li ng-repeat="module in detail.sub_modules" ng-class="{active: $index==0}">
                <a href="#tab_{{module.name}}" onclick="return false;" role="tab" data-toggle="tab">{{module.title}}</a>
            </li>
        </ul>
        <div class="tab-content bg-white">
            <div class="tab-pane" ng-repeat="module in detail.sub_modules" id="tab_{{module.name}}"  ng-class="{active: $index==0}">
                <!-- START PANEL -->
                <div class="panel" ng-if="detail.global_view[module.name].render_type ==='table'">
                    <div class="panel-heading">
                        <div class="panel-title">{{detail.global_view[module.name].page_title}}</div>


                        <div class="export-options-container pull-right">
                            <div class="exportOptions">
                                <div class="btn-group sm-m-t-10">
                                    <edit-button  ng-if="$parent.allowEdit" data-domwin-icon="" title="Add" class="btn btn-default blue " data-placement="top" ng-click="editAuto('', module.name)"><i class="fa fa-edit"></i> Add</edit-button>
                                    <a class="btn btn-default blue" ng-click="editEvent(i.url+'/'+enc_id,$event)" title="{{i.title}} Details" ng-repeat="i in buttonsPossibility.top"> <i ng-class="i.icon" title="{{i.title}} Details"></i> {{i.title}}</a>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body p-l-0 p-t-10 p-r-0" id="list_holder">
                        <!-- Start items section -->
                        <div class="dataTables_wrapper form-inline no-footer" id="tableWithExportOptions_wrapper">
                            <div class="table-responsive">
                                <table aria-describedby="tableWithExportOptions_info" role="grid" class="table table-striped dataTable no-footer" id="tableWithExportOptions">
                                    <thead>
                                    <tr role="row">
                                        <th ng-repeat="c in detail.global_view[module.name].index.columns.column">{{c.title}}</th>
                                        <th style="width: 120px"></th>
                                        <th style="width: 120px"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="item in listings[module.name]">
                                        <td ng-repeat="c in detail.global_view[module.name].index.columns.column">{{item[c.field.item.split(".")[0]][c.field.item.split(".")[1]]}}</td>
                                        <td><a class="text-black" ng-click="editEvent(i.url+'/'+enc_id+'/'+item[i.params.item.split('.')[0]][i.params.item.split('.')[1]],$event)" title="{{i.title}} Details" ng-repeat="i in buttonsPossibility.row"> <i title="{{i.title}} Details" ng-class="i.icon"></i> </a></td>
                                        <td class="v-align-middle">
                                            <edit-button data-domwin-icon="" title="Edit"  class="text-black" data-placement="top" ng-click="editAuto(item[module.model].id,module.name,$index)"><i class="fa fa-edit"></i></edit-button>
                                        </td>
                                    </tr>

                                    <!-- if there's no record available -->
                                    <tr data-ng-if="listings[module.name].length == 0"><td class="text-center" colspan="{{detail.global_view[module.name].index.columns.column.length + 2}}">No details</td></tr>

                                    </tbody>
                                </table>



                            </div>
                            <pagination style="float: right;" ng-model="currentPage" total-items="todos" max-size="maxSize" items-per-page="itemsPerPage" class="pagination-sm"></pagination>
                        </div>
                        <!-- End items section -->
                    </div>
                </div>
                <div class="panel" ng-if="detail.global_view[module.name].render_type ==='matrix'">
                    <table class="table table-striped dataTable no-footer">
                        <thead>
                        <tr>
                            <th>{{detail.global_view[module.name].title}}</th>
                            <th data-ng-if="listings[detail.global_view[module.name].matrix.x_item.dataSource].length != 0" ng-repeat="col in listings[detail.global_view[module.name].matrix.x_item.dataSource]">{{col[detail.global_view[module.name].matrix.x_item.title.split(".")[0]][detail.global_view[module.name].matrix.x_item.title.split(".")[1]]}}</th>
                            <th data-ng-if="listings[detail.global_view[module.name].matrix.x_item.dataSource].length == 0">No benefits available.</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr data-ng-if="listings[detail.global_view[module.name].matrix.y_item.dataSource].length == 0">
                            <td class="text-center" colspan="{{listings[detail.global_view[module.name].matrix.x_item.dataSource].length + 1}}">No available details.</td>
                        </tr>

                        <tr data-ng-if="listings[detail.global_view[module.name].matrix.y_item.dataSource].length != 0" ng-repeat="row in listings[detail.global_view[module.name].matrix.y_item.dataSource]">
                            <td>{{row[detail.global_view[module.name].matrix.y_item.title.split(".")[0]][detail.global_view[module.name].matrix.y_item.title.split(".")[1]]}}</td>

                            <td ng-repeat="col in listings[detail.global_view[module.name].matrix.x_item.dataSource]">

                                <select data-ng-if="$parent.allowEdit" ng-change='updateMatrix(listings[detail.global_view[module.name].matrix.values.dataSource][row[detail.global_view[module.name].matrix.y_item.title.split(".")[0]].id][col[detail.global_view[module.name].matrix.x_item.title.split(".")[0]].id], row[detail.global_view[module.name].matrix.y_item.title.split(".")[0]].id ,col[detail.global_view[module.name].matrix.x_item.title.split(".")[0]].id,detail.global_view[module.name].onChange)'
                                        ng-model='listings[detail.global_view[module.name].matrix.values.dataSource][row[detail.global_view[module.name].matrix.y_item.title.split(".")[0]].id][col[detail.global_view[module.name].matrix.x_item.title.split(".")[0]].id]'

                                        ng-options="key as value for (key , value) in listings[detail.global_view[module.name].matrix.values.list]" >
                                </select>

                               <div data-ng-if="!$parent.allowEdit"> {{listings[detail.global_view[module.name].matrix.values.list][matrix[row[detail.global_view[module.name].matrix.y_item.title.split(".")[0]].id][col[detail.global_view[module.name].matrix.x_item.title.split(".")[0]].id]]}}
                                </div>

                            </td>
                        </tr>

                        </tbody>
                    </table>

                    <div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>