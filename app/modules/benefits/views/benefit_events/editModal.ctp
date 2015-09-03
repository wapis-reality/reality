<div ng-cloak>
    <div class="modal-header clearfix text-left">
        <button type="button" class="close m-t-5 close_btn" ng-click="$close()" data-dismiss="modal" aria-label="Close">
            <i class="pg-close fs-14"></i>
        </button>
        <h5>
            <span id="domwin-title">{{window.title}}</span>
        </h5>
    </div>
<div class="tab clearfix">
        <div class="modal-body">
            <form class="form-default" role="form" action="">
                <ul class="nav nav-tabs nav-tabs-simple">
                    <li class="active"><a href="#tab1">Basic Details</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab1">
                        <div class="row">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" ng-model="edit.BenefitEventsModel.name" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea style="resize: vertical;" class="form-control" ng-model="edit.BenefitEventsModel.description" />
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
                    <button ng-if="$parent.allowEdit" class="btn btn-default blue pull-right" ng-click="modalOptions.submit();">Submit
                    </button>
                </div>
            </div>
            <!--    </form>-->
        </div>

</div>
</div>