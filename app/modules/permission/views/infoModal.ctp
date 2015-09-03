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

    <h5><span id="domwin-title">Details</span></h5>
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

                        <div>{{edit.UserGroupsModel.name}}</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Description</label>

                        <div ng-bind-html="edit.UserGroupsModel.description | nl2br"></div>
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
        </div>
    </div>
</div>

