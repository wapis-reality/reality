<div class="modal-header clearfix text-left">
    <button type="button" class="close m-t-5 close_btn" ng-click="$close()" data-dismiss="modal" aria-label="Close">
        <i class="pg-close fs-14"></i>
    </button>
    <h5>
        <span id="domwin-title">{{modalTitle}}</span>
    </h5>
</div>
<div class="modal-body">
    <div class="row">
      <div class="col-sm-12">
               <!--   <input class="form-control" type="text" ng-model="editableClient.Client.name" onkeyup="$('#url').val($(this).val().replace(/ /g,'-').toLowerCase());">-->

                <div class="form-group">
                    <label>Client name</label>
                    <input class="form-control" type="text" ng-model="editableClient.ClientModel.name" ng-keyup="updateUrl()">
                </div>

       </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label>Client URL</label>

                <div class="clearfix">
                    <div class="col-sm-2" style="line-height: 35px; padding-left: 0">http://server/</div>
                    <div class="col-sm-10"><input class="form-control" type="text" ng-model="editableClient.ClientModel.url"/></div>
                </div>

            </div>
        </div>
    </div>
    <hr/>
    <div class="row m-t-20">
        <div ng-class="editClientDetails ? 'col-sm-6' : 'col-sm-12 text-center'">
            <button class="btn btn-default pull-centre" ng-click="$close()"><i class="pg-close"></i>
                Close
            </button>
        </div>
        <div class="col-sm-6">
            <button ng-if="editClientDetails" class="btn btn-default blue pull-right" ng-click="submitForm()">Save
            </button>
        </div>
    </div>
</div>
<div class="clearfix"></div>