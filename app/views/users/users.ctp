
<div class="index" data-ng-cloak>
    <!-- START PANEL -->
    <div class="panel">
        <div class="panel-heading">
            <div class="panel-title">Listing of Users</div>
            <div class="export-options-container pull-right">
                <div class="exportOptions">
                    <div class="btn-group sm-m-t-10">
                        <edit-button data-domwin-icon="" title="Add"  class="btn btn-default blue" data-placement="top" ng-click="editAuto('-1','user')">Add</edit-button>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
            <div class="panel-body p-l-0 p-t-10 p-r-0" id="list_holder">

                <div class="dataTables_wrapper form-inline no-footer" id="tableWithExportOptions_wrapper">
                    <div class="table-responsive m-l-15 m-">
                        <table aria-describedby="tableWithExportOptions_info" role="grid" class="table table-striped dataTable no-footer" id="tableWithExportOptions">
                            <thead>
                            <tr role="row">
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th style="width: 120px"></th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr data-ng-repeat="user in users">
                                    <td>{{user.UserModel.id}}</td>
                                    <td>{{user.UserModel.last_name}}</td>
                                    <td>{{user.UserModel.first_name}}</td>
                                    <td>
                                        <edit-button data-domwin-icon="" title="Edit"  class="text-black" data-placement="top" ng-click="editAuto(user.UserModel.id,'user',$index)"><i class="fa fa-edit"></i></edit-button>
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