<style>
    .wrapper {
        background-color: white;
        border: 1px solid #cdd6db;
        border-radius: 3px;
        padding: 20px;
    }

</style>


<input type="hidden" ng-model="edit.UserGroupsModel.id"/>

<div class="modal-header clearfix text-left">
    <button type="button" class="close m-t-5 close_btn" data-ng-click="$close()" data-dismiss="modal"
            aria-label="Close"><i class="pg-close fs-14"></i></button>

    <h5><span id="domwin-title">Edit Permission</span></h5>
</div>
<div class="clearfix">
    <div class="modal-body">
        <ul class="nav nav-tabs nav-tabs-simple">
            <li class="active"><a data-toggle="tab" href="#tabReport">Basic</a></li>
        </ul>

        <div class="tab-content">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" ng-model="edit.UserGroupsModel.name" class="form-control"/>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Description</label>
                        <textarea ng-model="edit.UserGroupsModel.description" class="form-control" rows="15"></textarea>
                    </div>
                </div>
            </div>
        </div>


        <hr>
        <div class="row m-t-20">
            <div class="col-sm-6">
                <button type="button" class="btn btn-default close_btn" data-ng-click="$close()"><i
                        class="pg-close"></i>Close
                </button>
            </div>
            <div class="col-sm-6">
                <button class="btn btn-default blue pull-right form_submit" data-ng-click="modalOptions.submit();">
                    Save
                </button>
            </div>
        </div>
    </div>
</div>

