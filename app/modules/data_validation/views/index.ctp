<div class="modal-header clearfix text-left">
    <button type="button" class="close m-t-5 close_btn" ng-click="$close()" data-dismiss="modal" aria-label="Close"><i class="pg-close fs-14"></i></button>
    <h5><span id="domwin-title">Data Validation</span></h5></div>
<div class="index" data-ng-cloak>
    <!-- START PANEL -->
    <div class="panel">
        <div class="panel-heading">
            <div class="panel-title"></div>
            <div class="export-options-container pull-right">
                <div class="exportOptions">
                    <!--<div class="btn-group sm-m-t-10">
                        <edit-button data-domwin-icon="" title="Add"  class="btn btn-default blue" data-placement="top" ng-click="editAuto('-1','permission')">Add</edit-button>
                    </div>-->
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="panel-body p-l-0 p-t-10 p-r-0" id="list_holder">

            <div class="dataTables_wrapper form-inline no-footer" id="tableWithExportOptions_wrapper">
                <div class="table-responsive m-l-15 m-">
                    <!--<table aria-describedby="tableWithExportOptions_info" role="grid" class="table table-striped dataTable no-footer" id="tableWithExportOptions">
                        <thead>
                        <tr role="row">
                            <th>ID</th>
                            <th>Permission name</th>
                            <th style="width: 120px"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr data-ng-repeat="permission in permissions">
                            <td>{{permission.UserGroupsModel.id}}</td>
                            <td>{{permission.UserGroupsModel.name}}</td>
                            <td>
                                <edit-button data-domwin-icon="" title="Edit"  class="text-black" data-placement="top" ng-click="editAuto(permission.UserGroupsModel.id,'permission',$index)"><i class="fa fa-edit"></i></edit-button>
                            </td>
                        </tr>
                        </tbody>
                    </table>-->

                    <table aria-describedby="tableWithExportOptions_info" role="grid" class="table table-striped dataTable no-footer" id="tableWithExportOptions">
                        <thead>
                        <tr role="row">
<!--                            <th>ID</th>-->
                            <th>File Name</th>
                            <th>Status</th>
                            <th style="width: 120px"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
<!--                            <td>1</td>-->
                            <td>Employee Details CSV</td>
                            <td ng-class="validated.indexOf('1') > -1 ? 'text-success' : ''">{{(validated.indexOf("1") > -1) ? "Valid" : "Invalid"}}</td>
                            <td>
                                <a ng-click="locationhref('app/modules/data_validation/views/export/Employee_details.csv')" href="javascript: void(0)" data-domwin-icon="" title="Download"  class="text-black" data-placement="top"><i class="fa fa-download"></i></a>
                                <edit-button data-domwin-icon="" title="Upload"  class="text-black" data-placement="top" ng-click="editAuto('-1','data_validation','e')"><i class="fa fa-upload"></i></edit-button>
                            </td>
                        </tr>

                        <tr>
<!--                            <td>2</td>-->
                            <td>Dependents Details CSV</td>
                            <td ng-class="validated.indexOf('2') > -1 ? 'text-success' : ''">{{(validated.indexOf("2") > -1) ? "Valid" : "Invalid"}}</td>
                            <td>
                                <a ng-click="locationhref('app/modules/data_validation/views/export/Dependents_details.csv')" href="javascript: void(0)" data-domwin-icon="" title="Download"  class="text-black" data-placement="top"><i class="fa fa-download"></i></a>
                                <edit-button data-domwin-icon="" title="Upload"  class="text-black" data-placement="top" ng-click="editAuto('-1','data_validation','d')"><i class="fa fa-upload"></i></edit-button>
                            </td>
                        </tr>

                        <tr>
<!--                            <td>3</td>-->
                            <td>Flex Details CSV</td>
                            <td ng-class="validated.indexOf('3') > -1 ? 'text-success' : ''">{{(validated.indexOf("3") > -1) ? "Valid" : "Invalid"}}</td>
                            <td>
                                <a ng-click="locationhref('app/modules/data_validation/views/export/Flex_details.csv')" href="javascript: void(0)" data-domwin-icon="" title="Download"  class="text-black" data-placement="top"><i class="fa fa-download"></i></a>
                                <edit-button data-domwin-icon="" title="Upload"  class="text-black" data-placement="top" ng-click="editAuto('-1','data_validation','f')"><i class="fa fa-upload"></i></edit-button>
                            </td>
                        </tr>

                        <tr>
<!--                            <td>4</td>-->
                            <td>TRS Details CSV</td>
                            <td ng-class="validated.indexOf('4') > -1 ? 'text-success' : ''">{{(validated.indexOf("4") > -1) ? "Valid" : "Invalid"}}</td>
                            <td>
                                <a ng-click="downloadTrs()" href="javascript: void(0)" data-domwin-icon="" title="Download"  class="text-black" data-placement="top"><i class="fa fa-download"></i></a>
                                <edit-button data-domwin-icon="" title="Upload"  class="text-black" data-placement="top" ng-click="editAuto('-1','data_validation','t')"><i class="fa fa-upload"></i></edit-button>
                            </td>
                        </tr>



                        </tbody>
                    </table>

                </div>
                <!-- pagination -->
            </div>
        </div>
    </div>
</div>