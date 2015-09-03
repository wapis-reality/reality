<style>
    .datepicker.datepicker-dropdown {
        z-index: 99999 !important;
    }
</style>


<div class="modal-header clearfix text-left">
    <button type="button" class="close m-t-5 close_btn" ng-click="$close()" data-dismiss="modal" aria-label="Close">
        <i class="pg-close fs-14"></i>
    </button>
    <h5>
        <span id="domwin-title">Scheme renewal</span>
    </h5>
</div>
<div class="modal-body">
<!--    <form class="form-default" data-type="angular" role="form" action="/">-->
        <div class="row m-t-20">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Scheme name</label>
                    <input type="text" class="form-control" ng-model="edit.ClientScheme.name" />
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Effective Date</label>
                    <input calendar class="form-control" ng-model="edit.ClientScheme.effective_date" />
                </div>
            </div>
        </div>

    <div class="row m-t-20">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Select Modules</label>
                    <br/><div style="display:block; height: 20px;" ng-repeat="item in edit.modules.modul">
                        <br/><input
                            type="checkbox"
                            name="editClientSchemeModules[]"
                            value="{{item.key}}"
                            ng-checked="item.value"
                            > {{item.name}}
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label>Select Pages</label>
                    <br/><div style="display:block; height: 20px;" ng-repeat="item in edit.modules.page">
                        <br/><input
                            type="checkbox"
                            name="editClientSchemePages[]"
                            value="{{item.key}}"
                            ng-checked="item.value || item.key == 'home'"
                            > {{item.name}}
                    </div>

                </div>
            </div>
        </div>


        <hr/>
        <div class="row m-t-20">
            <div class="col-sm-6">
                <button class="btn btn-default" ng-click="$close()"><i class="pg-close"></i>
                    Close
                </button>
            </div>
            <div class="col-sm-6">
                <button class="btn btn-default blue pull-right" ng-click="submitForm()">Submit
                </button>
            </div>
        </div>
<!--    </form>-->
</div>