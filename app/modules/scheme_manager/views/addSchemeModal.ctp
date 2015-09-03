<style>
    .datepicker.datepicker-dropdown {
        z-index: 99999 !important;
    }

    #dd_zone {
        position: absolute;
        left: 0px;
        top: 53px;
        height: 900px;
        width: 873px;
        z-index: 998;
        text-align: center;
        line-height: 600px;
        font-size: 44px;
        font-weight: bold;
        color: white;
        background: rgba(0, 0, 0, 0.2) none repeat scroll 0px 0px;
    }

    #library {
        position: absolute;
        height: calc(100% - 197px);
        opacity: 0.8;
        background: rgb(255, 255, 255) none repeat scroll 0% 0%;
        right: 0px;
        width: 350px;
        top: 197px;
        z-index: 9999;
        border-left: 1px solid rgb(204, 204, 204);
        border-top: 1px solid rgb(204, 204, 204);
        overflow: auto;
    }

    #library_icon {
        position: absolute;
        top: 164px;
        right: 11px;
        z-index: 800;
        color: #333;
        font-size: 18px;
    }



    div.module-list {
        display: inline-block;
        margin: 10px 0px 10px 4px;
        border: 1px dashed #ececec;
        padding: 5px 5px 5px 10px;
    }
</style>


<div class="modal-header clearfix text-left">
    <button type="button" class="close m-t-5 close_btn" ng-click="$close()" data-dismiss="modal" aria-label="Close">
        <i class="pg-close fs-14"></i></button>
    <h5><span id="domwin-title">{{modalTitle}}</span></h5>
</div>
<div class="modal-body">
    <selection class="tab">
        <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
            <li ng-class="{active:panel.isSelected(1)}"><a href ng-click="panel.selectTab(1)">Basic</a></li>
            <li data-ng-if="!schemeId" ng-class="{active:panel.isSelected(2)}"><a href ng-click="panel.selectTab(2)">Modules</a></li>
            <li ng-class="{active:panel.isSelected(3)}"><a href ng-click="panel.selectTab(3)">Settings</a></li>
            <li data-ng-if="!schemeId" ng-class="{active:panel.isSelected(4)}"><a href ng-click="panel.selectTab(4)">Benefits</a></li>
        </ul>

        <div ng-show="panel.isSelected(1)">
            <p class="description">Please enter the new scheme name and the date from which it  is effective. Then select the scheme's required user security settings and enter details of the contact for user queries (e.g. HR or payroll).</p>
            <fieldset class="empty_space">
                <legend>Basic information</legend>
                <div class="row m-t-20">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Scheme name</label>
                            <input type="text" class="form-control" ng-model="editableClientScheme.ClientScheme.name"/>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Effective Dates</label>
                            <!--<input calendar class="form-control" ng-model="editableClientScheme.ClientScheme.effective_date"/>-->

                            <input date-picker type="text" show-button-bar="false" class="form-control" datepicker-popup ng-model="editableClientScheme.ClientScheme.effective_date" is-open="datestatus.effective_date" min-date="minDate" ng-click="dateOpen($event,'effective_date')"  />
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset class="empty_space">
                <legend>Security settings</legend>
                <div class="row m-t-20">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Password reset frequency</label>
                            <select class="form-control" ng-model="editableClientScheme.ClientScheme.security.reset_frequency">
                                <option value="60">60 days</option>
                                <option value="90">90 days</option>
                                <option value="120">120 days</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Incorrect login attempts permitted</label>
                            <input type="text" class="form-control" ng-model="editableClientScheme.ClientScheme.security.incorrect_attempts"/>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>First login process</label>
                            <select class="form-control" ng-model="editableClientScheme.ClientScheme.security.first_login">
                                <option value="random">Generate random password</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Username</label>
                            <select class="form-control" ng-model="editableClientScheme.ClientScheme.security.username">
                                <option value="employee_id">EmployeeID</option>
                                <option value="email">Email address</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Username and Password distribution</label>
                            <select class="form-control" ng-model="editableClientScheme.ClientScheme.security.distribution">
                                <option value="email">By Email</option>
                                <option value="letter">Letter</option>
                                <option>...</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Annual reset</label>
                            <select class="form-control" ng-model="editableClientScheme.ClientScheme.security.annual_reset">
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Password rules</label><br/>

                            <div class="checkbox " style="top:0; margin-top:0">
                                <input type="checkbox" id="checkbox1" ng-model="editableClientScheme.ClientScheme.security.password_uppercase">
                                <label for="checkbox1">Upper case</label>
                            </div>
                            <div class="checkbox " style="top:0; margin-top:0">
                                <input type="checkbox" id="checkbox2" ng-model="editableClientScheme.ClientScheme.security.password_lowercase">
                                <label for="checkbox2">Lower case</label>
                            </div>
                            <div class="checkbox " style="top:0; margin-top:0">
                                <input type="checkbox" id="checkbox3" ng-model="editableClientScheme.ClientScheme.security.password_numbers">
                                <label for="checkbox3">Number</label>
                            </div>
                            <div class="checkbox " style="top:0; margin-top:0">
                                <input type="checkbox" id="checkbox4" ng-model="editableClientScheme.ClientScheme.security.password_spec_character">
                                <label for="checkbox4">Spec. character</label>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset class="empty_space">
                <legend>Contact details</legend>
                <div class="row m-t-20">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>E-mail</label>
                            <input type="text" class="form-control" ng-model="editableClientScheme.ClientScheme.email"/>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Phone number</label>
                            <input class="form-control" ng-model="editableClientScheme.ClientScheme.phone_number"/>
                        </div>
                    </div>
                </div>
            </fieldset>


            <hr/>
            <div class="row m-t-20">
                <div class="col-sm-6">
                    <button class="btn btn-default" ng-click="$close()"><i class="pg-close"></i>Close</button>
                </div>
                <div class="col-sm-6">
                    <button class="btn btn-default blue pull-right" ng-click="(schemeId)? panel.selectTab(3): panel.selectTab(2)">Next <i class="pg pg-arrow_right"></i></button>
                </div>
            </div>
        </div>
        <div ng-show="panel.isSelected(2)" class="p-b-20">
            <p class="description">Please select the specific benefit modules the scheme will feature and the additional website pages required (e.g. Home, Support, etc).</p>
            <fieldset class="empty_space">
                <legend>Available modules</legend>
                    <div class="checkbox check-success module-list" ng-repeat="(k,item) in editableClientScheme.Modules.modul">
                        <input id="checkbox_modules{{k}}"
                               type="checkbox"
                               name="editClientSchemeModules[]"
                               ng-model="item.value"
                               ng-checked="item.value"
                               ng-disabled="item.disabled" />
                        <label for="checkbox_modules{{k}}">{{item.name}}</label>
                    </div>
            </fieldset>
            <fieldset class="empty_space">
                <legend>Additional website pages</legend>
                    <div class="checkbox check-success module-list" ng-repeat="(k,item) in editableClientScheme.Modules.page">
                        <input id="checkbox_pages{{k}}"
                               type="checkbox"
                               name="editClientSchemePages[]"
                               ng-model="item.value"
                               ng-checked="item.value"
                               ng-disabled="item.disabled" />
                        <label for="checkbox_pages{{k}}">{{item.name}}</label>
                    </div>
            </fieldset>

            <hr/>
            <div class="row m-t-20">
                <div class="col-sm-6">
                    <button class="btn btn-default" ng-click="panel.selectTab(1)"><i class="pg pg-arrow_left"></i> Prev</button>
                </div>
                <div class="col-sm-6">
                    <button class="btn btn-default blue pull-right" ng-click="panel.selectTab(3)">Next <i class="pg pg-arrow_right"></i></button>
                </div>
            </div>


        </div>
        <div ng-show="panel.isSelected(3)" class="p-b-20">
            <p class="description">Please set the basic scheme rules for each of your chosen scheme modules.</p>
            <fieldset class="empty_space">
                <legend>Flex</legend>
                <div class="row m-t-20">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Flex Fund</label>
                            <input type="text" class="form-control" ng-model="editableClientScheme.ClientScheme.flex_fund"/>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Maximum Flex Spend</label>
                            <input class="form-control" ng-model="editableClientScheme.ClientScheme.maximum_flex_spend"/>
                        </div>
                    </div>
                </div>
                <div class="row m-t-20">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Annual Enrolment Period (start date)</label>
                          <!--  <input calendar type="text" class="form-control" ng-model="editableClientScheme.ClientScheme.annual_enrolment_period_from"/>-->
                            <input date-picker type="text" show-button-bar="false" class="form-control" datepicker-popup ng-model="editableClientScheme.ClientScheme.annual_enrolment_period_from" is-open="datestatus.dp2" min-date="minDate" ng-click="dateOpen($event,'dp2')"  />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Annual Enrolment Period (end date)</label>
                           <!-- <input calendar class="form-control" ng-model="editableClientScheme.ClientScheme.annual_enrolment_period_to"/>-->
                            <input date-picker type="text" show-button-bar="false" class="form-control" datepicker-popup ng-model="editableClientScheme.ClientScheme.annual_enrolment_period_to" is-open="datestatus.dp3" min-date="minDate" ng-click="dateOpen($event,'dp3')" />
                        </div>
                    </div>
                </div>
                <div class="row m-t-20">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Plan eligibility</label>
                            <input type="text" class="form-control" ng-model="editableClientScheme.ClientScheme.plan_eligibility"/>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Confirmation / Change window</label>
                            <input class="form-control" ng-model="editableClientScheme.ClientScheme.change_window"/>
                        </div>
                    </div>
                </div>
                <div class="row m-t-20">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Plan Eligibility Date</label>
                            <!--<input calendar type="text" class="form-control" ng-model="editableClientScheme.ClientScheme.plan_eligibility_date"/>-->
                            <input date-picker type="text" show-button-bar="false" class="form-control" datepicker-popup ng-model="editableClientScheme.ClientScheme.plan_eligibility_date" is-open="datestatus.dp4" min-date="minDate" ng-click="dateOpen($event,'dp4')" />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Coverage Effective date</label>
                           <!-- <input calendar class="form-control" ng-model="editableClientScheme.ClientScheme.coverage_effective_date"/>-->
                            <input date-picker type="text" show-button-bar="false" class="form-control" datepicker-popup ng-model="editableClientScheme.ClientScheme.coverage_effective_date" is-open="datestatus.dp5" min-date="minDate" ng-click="dateOpen($event,'dp5')" />

                        </div>
                    </div>
                </div>
                <div class="row m-t-20">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Payroll Frequency</label>
                            <select class="form-control" ng-model="editableClientScheme.ClientScheme.payroll_frequency">
                                <option value="monthly">Monthly</option>
                                <option value="annual">Annual</option>

                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Life Events Effective Date</label>
                            <select class="form-control" ng-model="editableClientScheme.ClientScheme.life_events_effective_date">
                                <option value="1stFollowingM">First of following month</option>
                            </select>
                        </div>
                    </div>
                </div>
            </fieldset>
            <hr/>
            <div class="row m-t-20">
                <div class="col-sm-6">
                    <button class="btn btn-default" ng-click="(schemeId)? panel.selectTab(1): panel.selectTab(2)"><i class="pg pg-arrow_left"></i> Prev</button>
                </div>
                <div class="col-sm-6">
                    <button data-ng-if="!schemeId" class="btn btn-default blue pull-right" ng-click="panel.selectTab(4)">Next <i class="pg pg-arrow_right"></i></button>
                    <button data-ng-if="schemeId && editSchemeDetails" class="btn btn-default blue pull-right" ng-click="submitForm()">Submit <i class="fa  fa-save"></i></button>
                </div>
            </div>
        </div>
        <div ng-show="panel.isSelected(4)" class="p-b-20">
            <p class="description">To create benefits with default provisions, effective check the boxes below. To apply benefits created previously, click the icon to the right and select by client or benefit type. {{editableClientScheme.selected_benefit_type}} --- {{editableClientScheme.selected_benefits}}</p>

            <a href="#" id="library_icon" onclick="$('#library').toggleClass('none'); return false;"><span class="fa fa-list-alt"></span></a>
            <div id="dd_zone" class="none">Drop here</div>
            <div id="library" class="none">
                <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                    <li ng-class="{active:libraryPanel.isSelected(1)}"><a href ng-click="libraryPanel.selectTab(1)">By client</a></li>
                    <li ng-class="{active:libraryPanel.isSelected(2)}"><a href ng-click="libraryPanel.selectTab(2)">By type</a></li>
                    <li class="pull-right text-right" ng-class="{active:libraryPanel.isSelected(3)}"><a href ng-click="libraryPanel.selectTab(3)"><span class="pg-search"></span> </a></li>
                </ul>
                <div ng-show="libraryPanel.isSelected(1)">
                    <ul>
                        <li ng-repeat="(client, items) in library_list.clients">{{client}}
                            <ul dnd-list="list">
                                <li class="dd" ng-repeat="(id,item) in items" data-client="{{client}}" data-index="{{id}}" title="{{item.BenefitType.name}}">{{item.BenefitModel.name}} <span class="fa fa-info-circle"></span></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div ng-show="libraryPanel.isSelected(2)">
                    <ul>
                        <li ng-repeat="(type, items) in library_list.types">{{type}}
                            <ul dnd-list="list">
                                <li class="dd" ng-repeat="(id,item) in items" data-client="{{item.Client.name}}" data-index="{{id}}" title="{{item.BenefitType.name}}">{{item.Client.name}} - {{item.BenefitModel.name}} <span class="fa fa-info-circle"></span></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div ng-show="libraryPanel.isSelected(3)">
                    <input type="text"  ng-model="search.$" class="form-control" placeholder="Start typing here ..." style="width:89%; margin:20px"/>
                    <ul>
                        <li class="dd" ng-repeat="item in library_list.all | filter:search:strict" data-client="{{item.Client.name}}" data-index="{{item.BenefitModel.id}}">{{item.Client.name}} - {{item.BenefitModel.name}} <span class="fa fa-info-circle"></span></li>
                    </ul>
                </div>
            </div>


            <fieldset class="empty_space">
                <legend>Benefit types</legend>
                <div class="benefit_type_list clearfix p-t-20">
                    <div class="col-sm-12 padding-10" style="border: 1px dashed #ccc; margin-bottom: 5px; min-height: 50px" ng-repeat="(key, type) in benefitTypes">
                        <div class="checkbox check-success " style="margin:0">
                            <input id="checkbox_x_{{key}}" type="checkbox" ng-checked="editableClientScheme.selected_benefit_type[key] == 1" ng-model="editableClientScheme.selected_benefit_type[key]" ng-change="benefitTypeCheckbox.checkboxChange(key)">
                            <label for="checkbox_x_{{key}}">{{type}}</label>
                        </div>
                        <div>
                            <div ng-repeat="b in editableClientScheme.selected_benefits[key]" class="padding-10 clearfix" style="border: 1px solid rgb(250, 250, 250); font-size: 11px; margin-top: 10px; background: rgba(0, 0, 0, 0.1);">
                                <div class="col-sm-11">{{b.Client.name}} - {{b.BenefitModel.name}}</div>
                                <div class="col-sm-1 text-right">
                                    <span class="fa fa-info-circle"></span>
                                    <span class="fa fa-close" ng-click="remove_library_item(key,$index)"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <div class="row m-t-20">
                <div class="col-sm-6">
                    <button class="btn btn-default" ng-click="panel.selectTab(3)"><i class="pg pg-arrow_left"></i> Prev</button>
                </div>
                <div class="col-sm-6">
                    <button class="btn btn-default blue pull-right" ng-click="submitForm()">Submit <i class="fa  fa-save"></i></button>
                </div>
            </div>
        </div>
    </selection>
</div>
<div class="clearfix"></div>
{{initDD()}}