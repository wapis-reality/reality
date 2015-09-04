
<div class="index" data-ng-cloak>
    <!-- START PANEL -->
    <div class="panel">
        <div class="panel-heading">
            <div class="panel-title">Seznam nemovitostí</div>
            <div class="export-options-container pull-right">
                <div class="exportOptions">
                    <div class="btn-group sm-m-t-10">
                        <edit-button data-domwin-icon="" title="Add"  class="btn btn-default blue" data-placement="top" ng-click="editAuto('-1','real_estate')">Pridat</edit-button>
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
                            <th class="col-sm-2">Id</th>
                            <th class="col-sm-3">Nazev</th>
                            <th class="col-sm-3">Vlastník</th>
                            <th class="col-sm-3">Makléř</th>
                            <th class="col-sm-1"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr data-ng-repeat="request in requests">
                            <td>{{request.RealEstateModel.id}}</td>
                            <td>{{request.RealEstateModel.name}}</td>
                            <td>{{request.Client.first_name}} {{request.Client.last_name}}</td>
                            <td>{{request.Broker.first_name}} {{request.Broker.last_name}}</td>
                            <td>
                                <edit-button data-domwin-icon="" title="Edit"  class="text-black" data-placement="top" ng-click="editAuto(request.RealEstateModel.id,'real_estate',$index)"><i class="fa fa-edit"></i></edit-button>
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