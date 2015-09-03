<div ng-cloak>
<div class="modal-header clearfix text-left">
    <button type="button" class="close m-t-5 close_btn" data-ng-click="$close()" data-dismiss="modal" aria-label="Close"><i class="pg-close fs-14"></i></button>

    <h5><span id="domwin-title">Upload</span></h5>
</div>
    <div class="tab clearfix">
<div class="modal-body">
    <input type="hidden" ng-model="edit.UserGroupsModel.id" />
    <ul class="nav nav-tabs nav-tabs-simple">
        <li class="active"><a data-toggle="tab" href="#tabReport">File Upload</a></li>
    </ul>
    <!--  <form method="" id="testform">
--<input type="text" ng-model="test.name" />
      <input type="text" ng-model="test.name2"/>-->
    <div class="tab-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Browse</label>
                    <input type="file" onchange="angular.element(this).scope().getFile(event)" class="form-control" />
                </div>

                <div>
                    <p ng-repeat="m in message">{{m}}</p>
                </div>

            </div>

        </div>
    </div>
      <!--  </form>-->


    <hr>
    <div class="row m-t-20">
        <div class="col-sm-6">
            <button type="button" class="btn btn-default close_btn" data-ng-click="$close()"><i class="pg-close"></i>Close</button>
        </div>
        <div class="col-sm-6">
            <button class="btn btn-default blue pull-right form_submit" data-ng-click="validate();">Validate</button>
        </div>
    </div>
</div>
        </div>
</div>
