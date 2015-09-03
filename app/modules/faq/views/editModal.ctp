<input type="hidden" ng-model="edit.UserGroupsModel.id" />

<div class="modal-header clearfix text-left">
    <button type="button" class="close m-t-5 close_btn" data-ng-click="$close()" data-dismiss="modal" aria-label="Close"><i class="pg-close fs-14"></i></button>

    <h5><span id="domwin-title">{{window.title}}</span></h5>
</div>
<div class="tab clearfix">
<form name="form.userForm" ng-submit="submitForm()" novalidate>
<div class="modal-body">
    <ul class="nav nav-tabs nav-tabs-simple">
        <li class="active"><a data-toggle="tab" href="#tabReport">FAQ</a></li>
    </ul>

    <div class="tab-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group" show-errors>
                    <label>Question</label>
                    <input type="text" name="question" ng-model="edit.FaqItemsModel.title" class="form-control" required />
                    <p ng-show="form.userForm.question.$invalid && !form.userForm.question.$pristine" class="text-danger">Question is required.</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="form-group" show-errors>
                    <label>Answer</label>
                    <textarea name="description" ng-model="edit.FaqItemsModel.description" class="form-control" rows="15" required></textarea>
                    <p ng-show="form.userForm.description.$invalid  && !form.userForm.description.$pristine" class="text-danger">Answer is required.</p>
                </div>
            </div>
        </div>
    </div>

    <hr>
    <div class="row m-t-20">
        <div class="col-sm-6">
            <button type="button" class="btn btn-default close_btn" data-ng-click="$close()"><i class="pg-close"></i>Close</button>
        </div>
        <div class="col-sm-6">
            <button type="submit" ng-if="$parent.allowEdit" class="btn btn-default blue pull-right form_submit">Save</button>
        </div>
    </div>
</div>
</form>
</div>

