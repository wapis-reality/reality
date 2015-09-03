<div ng-cloak>
    <div class="modal-header clearfix text-left">
        <button type="button" class="close m-t-5 close_btn" ng-click="$close()" data-dismiss="modal" aria-label="Close">
            <i class="pg-close fs-14"></i>
        </button>
        <h5>
            <span id="domwin-title">TRS</span>
        </h5>
    </div>
    <div class="modal-body">
        <form class="form-default" role="form" action="">
            <ul class="nav nav-tabs nav-tabs-simple">
                <li class="active"><a href="#tab1">Basic Details</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab1">
                    <div class="row m-t-20" ng-repeat="benefit in benefits">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for = "{{benefit.BenefitModel.name}}">{{benefit.BenefitModel.name}}</label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <select class="form-control" ng-model="benefit.BenefitModel.trsSelect" ng-options="trsSelectName for trsSelectName in trsSelectNames"></select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group" style="margin-top: -26px;">
                                <label>Description</label>
                                <textarea style="resize: vertical;" class="form-control" ng-model="benefit.BenefitModel.description" />
                            </div>
                        </div>
                    </div>


                </div>
            </div>

        </form>
        <hr/>
        <div class="row m-t-20">
            <div class="col-sm-6">
                <button class="btn btn-default" ng-click="$close()"><i class="pg-close"></i>
                    Close
                </button>
            </div>
            <div class="col-sm-6">
                <button ng-if="$parent.allowEdit" class="btn btn-default blue pull-right" ng-click="modalOptions.submit();">
                    Submit
                </button>
            </div>
        </div>
        <!--    </form>-->
    </div>
</div>