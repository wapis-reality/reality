<style>
    .request_uploader {
        height: auto;
        min-height: 320px;
        border: 1px solid #efefef;
        padding: 10px;
    }

    .request_uploader #file_uploader {
        height: 250px;
        position: absolute;
        top: 35px;
        left: 25px;
        width: 94%;
        border: 2px dotted #ccc;
        display: none;
        background: url("images/drop.png") no-repeat center;
    }

    .request_uploader input[type=file] {
        height: 100%;
        width: 100%;
        color: transparent;
        opacity: 0;
        -moz-opacity: 0;
        filter: progid:DXImageTransform.Microsoft.Alpha(opacity=0);
    }

    .request_uploader textarea {
        height: 250px;
        width: 97%;
        resize: none;
        border: none;
        outline: none;
        /*border:1px dotted red;
        background-color: blue;*/
    }

    .flex_uploader {
        height: auto;
        min-height: 320px;
        border: 1px solid #ccc;
        padding: 10px;
    }

    .flex_uploader #f_uploader {
        height: 250px;
        border: 1px dotted #ccc;
        margin-bottom: 10px;
        background: url("images/drop.png") no-repeat center;
    }

    .flex_uploader input[type=file] {
        height: 100%;
        width: 100%;
        color: transparent;
        opacity: 0;
        -moz-opacity: 0;
        filter: progid:DXImageTransform.Microsoft.Alpha(opacity=0);
    }

</style>


<input type="hidden" ng-model="edit.RealEstateModel.id"/>
<div class="modal-header clearfix text-left">
    <button type="button" class="close m-t-5 close_btn" data-ng-click="$close()" data-dismiss="modal"
            aria-label="Close"><i class="pg-close fs-14"></i></button>

    <h5><span id="domwin-title">{{window.title}}</span></h5>
</div>

<div class="clearfix">
    <div class="modal-body">
        <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
            <li ng-class="{active:panel.isSelected(1)}"><a href ng-click="panel.selectTab(1)">Zakladni</a></li>
        </ul>

        <div ng-show="panel.isSelected(1)">
            <p class="description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ut felis sed nisi lacinia rutrum et sodales ligula. Etiam rhoncus leo ut dolor tempus mollis. Nulla volutpat in sem eu condimentum.</p>
            <fieldset class="empty_space">
                <legend>Zakladni informace</legend>
                <div class="row m-t-10">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Nazev</label>
                            <input type="text" ng-model="edit.RealEstateModel.name" class="form-control"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Typ</label>
                            <input type="text" ng-model="edit.RealEstateModel.advert_type" class="form-control"/>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Funkce</label>
                            <input type="text" ng-model="edit.RealEstateModel.advert_function" class="form-control"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Podtyp</label>
                            <input type="text" ng-model="edit.RealEstateModel.advert_subtype" class="form-control"/>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Popis</label>
                            <textarea ng-model="edit.RealEstateModel.description" class="form-control" rows="15"></textarea>
                        </div>
                    </div>
                </div>
            </fieldset>
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

