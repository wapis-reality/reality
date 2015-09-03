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


<input type="hidden" ng-model="edit.UserGroupsModel.id"/>
<div class="modal-header clearfix text-left">
    <button type="button" class="close m-t-5 close_btn" data-ng-click="$close()" data-dismiss="modal"
            aria-label="Close"><i class="pg-close fs-14"></i></button>

    <h5><span id="domwin-title">{{window.title}}</span></h5>
</div>

<div class="clearfix">
    <div class="modal-body">
        <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
            <li ng-class="{active:panel.isSelected(1)}"><a href ng-click="panel.selectTab(1)">Zakladni</a></li>
            <li ng-class="{active:panel.isSelected(2)}"><a href ng-click="panel.selectTab(2)">Kontaktní osoby</a></li>
            <li ng-class="{active:panel.isSelected(3)}"><a href ng-click="panel.selectTab(3)">Smlouvy</a></li>
            <li ng-class="{active:panel.isSelected(4)}"><a href ng-click="panel.selectTab(4)">Objednavky</a></li>
            <li ng-class="{active:panel.isSelected(5)}"><a href ng-click="panel.selectTab(5)">Obsazene pozice</a></li>
        </ul>

        <div ng-show="panel.isSelected(1)">
            <p class="description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ut felis sed nisi lacinia rutrum et sodales ligula. Etiam rhoncus leo ut dolor tempus mollis. Nulla volutpat in sem eu condimentum.</p>
            <fieldset class="empty_space">
                <legend>Zakladni informace</legend>
                <div class="row m-t-10">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Nazev</label>
                            <input type="text" ng-model="edit.CompanyModel.name" class="form-control"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>ICO</label>
                            <input type="text" ng-model="edit.CompanyModel.IDNUM" class="form-control"/>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>DIC</label>
                            <input type="text" ng-model="edit.CompanyModel.VAT" class="form-control"/>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Ulice: </label>
                            <input type="text" ng-model="edit.CompanyModel.address1" class="form-control"/>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Mesto: </label>
                            <input type="text" ng-model="edit.CompanyModel.city" class="form-control"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>PSC: </label>
                            <input type="text" ng-model="edit.CompanyModel.postcode" class="form-control"/>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Kraj: </label>
                            <input type="text" ng-model="edit.CompanyModel.county" class="form-control"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Stat: </label>
                            <input type="text" ng-model="edit.CompanyModel.country" class="form-control"/>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Www: </label>
                            <input type="text" ng-model="edit.CompanyModel.www" class="form-control"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Poznamka</label>
                            <textarea ng-model="edit.CompanyModel.note" class="form-control" rows="15"></textarea>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>

        <!-- kontaktni osoby -->
        <div ng-show="panel.isSelected(2)">
            <p class="description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ut felis sed nisi lacinia rutrum et sodales ligula. Etiam rhoncus leo ut dolor tempus mollis. Nulla volutpat in sem eu condimentum.</p>
            <fieldset class="empty_space">
                <legend>Kontaktni osoby</legend>
                <div class="row m-t-15">
                    <div class="col-sm-11 padding-0">
                        <div class="col-sm-3">Jmeno</div>
                        <div class="col-sm-3">Prijmeni</div>
                        <div class="col-sm-3">Telefon</div>
                        <div class="col-sm-3">Email</div>
                    </div>
                    <div class="col-sm-1">
                        <a href="" ng-click="add_contact_person()"><i class="fa  fa-plus-square"></i> </a>
                    </div>
                </div>
                <hr class="m-t-10"/>
                <div class="row m-t-10" ng-repeat="contact in edit.CompanyContactModel" ng-show="edit.CompanyContactModel.length > 0">
                    <div class="col-sm-11 padding-0">
                        <div class="col-sm-3"><input type="text" ng-model="contact.first_name" class="form-control"/> </div>
                        <div class="col-sm-3"><input type="text" ng-model="contact.last_name" class="form-control"/></div>
                        <div class="col-sm-3"><input type="text" ng-model="contact.phone" class="form-control"/></div>
                        <div class="col-sm-3"><input type="text" ng-model="contact.email" class="form-control"/></div>
                    </div>
                    <div class="col-sm-1">
                        <a href="#" ng-click="remove_contact_person($index)" onclick="return false;"><i class="fa fa-trash"></i> </a>
                    </div>
                </div>
                <div class="row m-t-10 text-center" ng-hide="edit.CompanyContactModel.length > 0">
                    Kontaktni osoba nebyla zadefinovana.
                </div>

            </fieldset>
        </div>

        <!-- smlouvy -->
        <div ng-show="panel.isSelected(3)">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <h6>Pretahnete soubor zde</h6>

                        <div class="flex_uploader">
                            <div id="f_uploader">
                                <input type="file" file-read="uploadme.src" data-ng-click="hideWizard($event)" name="file" multiple>
                            </div>

                            <table cellspacing="50" cellpadding="50">
                                <tr ng-repeat="t in edit.items">
                                    <td><a target="_blank" href="uploaded/{{t}}" download> {{$index+1}}. {{t}}</a></td>
                                    <td width="100"></td>
                                    <td><a href="javascript:;" ng-click="deleteFile($index)"> <i class="fa fa-trash-o"></i></a></td>
                                </tr>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Objednavky -->
        <div ng-show="panel.isSelected(4)">
            <p class="description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ut felis sed nisi lacinia rutrum et sodales ligula. Etiam rhoncus leo ut dolor tempus mollis. Nulla volutpat in sem eu condimentum.</p>
            <fieldset class="empty_space">
                <legend>Objednavky</legend>
                <div class="row m-t-15">
                    <div class="col-sm-11 padding-0">
                        <div class="col-sm-3">Nazev</div>
                        <div class="col-sm-3">Profese</div>
                        <div class="col-sm-3">Mesto</div>
                        <div class="col-sm-3">Stav</div>
                    </div>
                    <div class="col-sm-1">
                        <a href="" ng-click="add_contact_person()"><i class="fa  fa-plus-square"></i> </a>
                    </div>
                </div>
                <hr class="m-t-10"/>
                <div class="row m-t-10" ng-repeat="order in edit.OrderModel" ng-show="edit.OrderModel.length > 0">
                    <div class="col-sm-11 padding-0">
                        <div class="col-sm-3">{{order.name}}</div>
                        <div class="col-sm-3">{{order.profession}}</div>
                        <div class="col-sm-3">{{order.city}}</div>
                        <div class="col-sm-3">{{order.status}}</div>
                    </div>
                    <div class="col-sm-1">
                        <a href="#" ng-click="remove_contact_person($index)" onclick="return false;"><i class="fa fa-info-circle"></i> </a>
                        <a href="#" ng-click="remove_contact_person($index)" onclick="return false;"><i class="fa fa-trash"></i> </a>
                    </div>
                </div>
                <div class="row m-t-10 text-center" ng-hide="edit.OrderModel.length > 0">
                    Objednavka nebyla zadefinovana.
                </div>

            </fieldset>
        </div>

        <!-- Obsazene pozice -->
        <div ng-show="panel.isSelected(5)">
            <p class="description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ut felis sed nisi lacinia rutrum et sodales ligula. Etiam rhoncus leo ut dolor tempus mollis. Nulla volutpat in sem eu condimentum.</p>
            <fieldset class="empty_space">
                <legend>Obsazene pozice</legend>
                <div class="row m-t-15">
                    <div class="col-sm-11 padding-0">
                        <div class="col-sm-3">Nazev</div>
                        <div class="col-sm-3">Profese</div>
                        <div class="col-sm-3">Mesto</div>
                        <div class="col-sm-3">Vitez</div>
                    </div>
                    <div class="col-sm-1">

                    </div>
                </div>
                <hr class="m-t-10"/>
                <div class="row m-t-10" ng-repeat="order in edit.OrderDoneModel" ng-show="edit.OrderDoneModel.length > 0">
                    <div class="col-sm-11" style="padding-right: 0">
                        <div class="col-sm-3 p-b-10 b-b b-grey" style="padding-left: 0">{{order.name}}</div>
                        <div class="col-sm-3 p-b-10  b-b b-grey">{{order.profession}}</div>
                        <div class="col-sm-3 p-b-10  b-b b-grey">{{order.city}}</div>
                        <div class="col-sm-3 p-b-10  b-b b-grey">{{order.person}}</div>
                    </div>
                    <div class="col-sm-1" style="padding-left: 0">
                        <div class="col-sm-12  p-b-10 b-b b-grey" style="padding: 0">
                            <a href="#" ng-click="remove_contact_person($index)" onclick="return false;"><i class="fa fa-info-circle"></i> </a>
                        </div>
                    </div>
                </div>
                <div class="row m-t-10 text-center" ng-hide="edit.OrderDoneModel.length > 0">
                    Objednavka nebyla zadefinovana.
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

