<div class="modal-header clearfix text-left">
    <button type="button" class="close m-t-5 close_btn" ng-click="$close()" data-dismiss="modal" aria-label="Close"><i class="pg-close fs-14"></i></button>
    <h5><span id="domwin-title">TRS Setting</span></h5></div>
<div class="modal-body" >
    <div class="panel">
        <div class="panel-body p-l-0 p-t-10 p-r-0" id="list_holder">
            <!-- Start items section -->
            <div class="dataTables_wrapper form-inline no-footer" id="tableWithExportOptions_wrapper">
                <div class="table-responsive">
                    <table aria-describedby="tableWithExportOptions_info" role="grid" class="table table-striped dataTable no-footer" id="tableWithExportOptions">
                        <thead>
                        <tr role="row">
<!--                            <th class="col-sm-1">ID</th>-->
                            <th class="col-sm-4">Name</th>
                            <th class="col-sm-2">Include in TRS</th>
                            <th class="col-sm-5">Description</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="item in detail.items.benefits">
<!--                            <td>{{item.BenefitModel.id}}</td>-->
                            <td>{{item.BenefitModel.name}}</td>
                            <td>
                                <select class="form-control" style="width: 100%;" ng-model="item.BenefitModel.trsSelect" ng-options="trsSelectName for trsSelectName in trsSelectNames"></select>
                            </td>
                            <td>
                                <textarea style="resize: vertical;width: 100%;" class="form-control" ng-model="item.BenefitModel.trsDescription" />
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <button ng-if="$parent.allowEdit" class="btn btn-default blue pull-right" ng-click="trsSubmit();">
                    Save
                </button>
            </div>
            <!-- End items section -->
        </div>
    </div>
</div>