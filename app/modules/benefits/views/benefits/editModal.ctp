<style>
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

    .row_spacing {
        margin-right: -18px;
        margin-left: -18px;
        background-color: #F5F5F5;
        padding-bottom: 3px;
        padding-top: 3px;
    }

    .benefit_table_headings {
        padding-left: 20px !important;
        border-bottom: 1px solid #ddd;
        border-top: none !important;
        color: #868686;
    }

    .benefit_table {
        background-color: #F5F5F5 !important;
    }

    .benefit_table_color {
        background-color: #F5F5F5 !important;
    }

    .benefit_options {
        border-bottom: 1px solid #ddd;
        height: 25px;
    }

    .benefit_table_td {
        border-bottom: none !important;
        padding-top: 2px !important;
        padding-bottom: 2px !important;
        overflow: auto;
    }

    .ageGroups{
        background-color: #ddd !important;
        left: 30px;
        padding: 10px;
        position: absolute;
        top: 48px;
        width: 374px;
        z-index: 999;
    }

    .eligibilityGroups{
        background-color: #ddd !important;
        left: 30px;
        padding: 10px;
        position: absolute;
        top: 24px;
        width: 374px;
        z-index: 999;
    }

    .genderGroups{
        background-color: #ddd !important;
        left: 30px;
        padding: 10px;
        position: absolute;
        top: 72px;
        width: 374px;
        z-index: 999;
    }

    .group_summary{
        color: #8c8c8c;
        font-size: 10px;
    }

    .uppercase_title{
        color: #626262 !important;
        font-weight: bold;
        font-size: 12px !important;
        text-transform: uppercase !important;
    }

    .cursor_pointer{
        cursor: pointer;
    }

</style>


<div class="modal-header clearfix text-left">
    <button type="button" class="close m-t-5 close_btn" ng-click="$close()" data-dismiss="modal" aria-label="Close">
        <i class="pg-close fs-14"></i>
    </button>
    <h5>
        <span id="domwin-title">{{window.title}}</span>
    </h5>
</div>

<section class="tab clearfix">
<div class="modal-body">
    <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
        <li ng-class="{active:panel.isSelected(1)}"><a href ng-click="panel.selectTab(1)">Benefit Details</a></li>
        <li ng-class="{active:panel.isSelected(2)}" ng-show="edit.BenefitModel.selection_type == 'Cover Levels'"><a href ng-click="panel.selectTab(2)">Benefit Rates</a></li>
        <li ng-class="{active:panel.isSelected(3)}"><a href ng-click="panel.selectTab(3)">Benefit Eligibility</a></li>
        <li ng-class="{active:panel.isSelected(4)}"><a href ng-click="panel.selectTab(4)">Attachments</a></li>
        <li ng-class="{active:panel.isSelected(5)}"><a href ng-click="panel.selectTab(5)">Others</a></li>
    </ul>
    <div ng-show="panel.isSelected(1)">
        <p class="description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ut felis sed nisi lacinia rutrum et sodales ligula. Etiam rhoncus leo ut dolor tempus mollis. Nulla volutpat in sem eu condimentum.</p>
        <fieldset class="empty_space">
            <legend>Flex</legend>
            <div class="row m-t-5">
                <div class="col-sm-2">
                    <div class="checkbox check-default">
                        <input type="checkbox" id="flex" ng-model="edit.BenefitModel.flex" ng-true-value="'1'" ng-false-value="'0'" ng-change="clearFlex()">
                        <label for=flex>Flex Benefit?</label>
                    </div>
                </div>
            </div>
        </fieldset>
        <fieldset class="empty_space">
            <legend>General</legend>
            <div class="row m-t-20">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>BENEFIT TYPE</label>
                        <select class="form-control" ng-model="edit.BenefitModel.benefit_type" ng-options="key as value for (key, value) in selectOptions['benefit_types']"/>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>NAME</label>
                        <input type="text" class="form-control" ng-model="edit.BenefitModel.name"/>
                    </div>
                </div>
            </div>

            <div class="row m-t-20">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>PROVIDER</label>
                        <input type="text" class="form-control" ng-model="edit.BenefitModel.provider"/>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>CATEGORY</label>
                        <select class="form-control" ng-model="edit.BenefitModel.category" ng-options="key as value for (key, value) in selectOptions['benefit_categories']"/>
                    </div>
                </div>
            </div>
        </fieldset>
        <fieldset class="empty_space">
            <legend>Display option + calculation</legend>
            <div class="row m-t-20" ng-show="edit.BenefitModel.flex == 1">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>SELECTION TYPE</label>
                        <select class="form-control" ng-model="edit.BenefitModel.selection_type" ng-options="selectionType for selectionType in selectionTypes" ng-change="clearSelectionType()"></select>
                    </div>
                </div>
                <div class="col-sm-6" ng-show="edit.BenefitModel.selection_type !== 'Type-In' && edit.BenefitModel.selection_type !== ''">
                    <div class="form-group">
                        <label>DISPLAY OPTION</label>
                        <select class="form-control" ng-model="edit.BenefitModel.display_option" ng-options="displayName for displayName in displayNames"></select>
                    </div>
                </div>
            </div>
            <div class="row m-t-20">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>CALCULATION</label>
                        <select class="form-control" ng-model="edit.BenefitModel.calc" ng-options="calcName for calcName in calcNames"></select>
                    </div>
                </div>
                <div class="col-sm-6" ng-show="edit.BenefitModel.calc == 'Other'">
                    <div class="form-group">
                        <label>OTHER CALCULATION</label>
                        <input type="text" class="form-control" ng-model="edit.BenefitModel.other_calc"/>
                    </div>
                </div>
            </div>
            <div class="row m-t-20" ng-show="edit.BenefitModel.selection_type == 'Type-In' && edit.BenefitModel.flex == 1 || edit.BenefitModel.benefit_type == '9' && edit.BenefitModel.flex == 1 || edit.BenefitModel.benefit_type == '11' && edit.BenefitModel.flex == 1 || edit.BenefitModel.benefit_type == '14' && edit.BenefitModel.flex == 1">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>MINIMUM</label>
                        <label ng-show="edit.BenefitModel.benefit_type == '14'"> %</label>
                        <label ng-show="edit.BenefitModel.benefit_type == '9'"> SELL</label>
                        <input class="form-control" ng-model="edit.BenefitModel.minimum">
                        </input>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>MAXIMUM</label>
                        <label ng-show="edit.BenefitModel.benefit_type == '14'"> %</label>
                        <label ng-show="edit.BenefitModel.benefit_type == '9'"> BUY</label>
                        <input class="form-control" ng-model="edit.BenefitModel.maximum">
                        </input>
                    </div>
                </div>
            </div>
            <div class="row m-t-20" ng-show="edit.BenefitModel.flex == 1 && edit.BenefitModel.benefit_type == '9'">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label ng-show="edit.BenefitModel.flex == 1 && edit.BenefitModel.benefit_type == '9'">MINIMUM TOTAL SELL</label>
                        <input class="form-control" ng-model="edit.BenefitModel.minimumTotalDays">
                        </input>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label ng-show="edit.BenefitModel.flex == 1 && edit.BenefitModel.benefit_type == '9'">MAXIMUM TOTAL SELL</label>
                        <input class="form-control" ng-model="edit.BenefitModel.maximumTotalDays">
                        </input>
                    </div>
                </div>
            </div>
            <div class="row m-t-20" ng-show="edit.BenefitModel.selection_type == 'Type-In' && edit.BenefitModel.flex == 1 && edit.BenefitModel.benefit_type != '11' || edit.BenefitModel.benefit_type == '9' && edit.BenefitModel.flex == 1 || edit.BenefitModel.benefit_type == '14' && edit.BenefitModel.flex == 1">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>INCREMENT</label>
                        <input class="form-control" ng-model="edit.BenefitModel.increment">
                        </input>
                    </div>
                </div>
                <div class="col-sm-6" ng-show="edit.BenefitModel.flex == 1 && edit.BenefitModel.benefit_type == '14'">
                    <div class="form-group">
                        <label>PENSIONABLE SALARY DEFINITION</label>
                        <select class="form-control" ng-model="edit.BenefitModel.penSalaryDefinition" ng-options="penSalName for penSalName in penSalNames"></select>
                        </input>
                    </div>
                </div>
                <div class="col-sm-6" ng-show="edit.BenefitModel.flex == 1 && edit.BenefitModel.benefit_type == '9'">
                    <div class="form-group">
                        <label>HOLIDAY SALARY DEFINITION</label>
                        <select class="form-control" ng-model="edit.BenefitModel.holSalaryDefinition" ng-options="holSalName for holSalName in holSalNames"></select>
                        </input>
                    </div>
                </div>
            </div>
            <div class="row m-t-20" ng-show="edit.BenefitModel.flex == 1 && edit.BenefitModel.benefit_type == '11'">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>INCREMENT</label>
                        <input class="form-control" ng-model="edit.BenefitModel.increment">
                        </input>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>SALARY CAP</label>
                        <input class="form-control" ng-model="edit.BenefitModel.salary_multiple_cap">
                        </input>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>CORE AMOUNT</label>
                        <input class="form-control" ng-model="edit.BenefitModel.core_amount">
                        </input>
                    </div>
                </div>
            </div>
            <div class="row m-t-20" ng-show="edit.BenefitModel.flex == 1">
                <div class="col-sm-2">
                    <div class="checkbox check-default">
                        <input type="checkbox" id="SalarySac" ng-model="edit.BenefitModel.salarySacChecked" ng-true-value="'1'" ng-false-value="'0'">
                        <label for=SalarySac>Salary Sacrifice</label>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="checkbox check-default">
                        <input type="checkbox" id="TaxFree" ng-model="edit.BenefitModel.taxFreeChecked" ng-true-value="'1'" ng-false-value="'0'">
                        <label for=TaxFree>Tax Free</label>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="checkbox check-default">
                        <input type="checkbox" id="EENIFree" ng-model="edit.BenefitModel.eeNIFreeChecked" ng-true-value="'1'" ng-false-value="'0'">
                        <label for=EENIFree>EE NI Free</label>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="checkbox check-default">
                        <input type="checkbox" id="ERNIFree" ng-model="edit.BenefitModel.erNIFreeChecked" ng-true-value="'1'" ng-false-value="'0'">
                        <label for=ERNIFree>ER NI Free</label>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="checkbox check-default">
                        <input type="checkbox" id="P11DReport" ng-model="edit.BenefitModel.p11DReportChecked" ng-true-value="'1'" ng-false-value="'0'">
                        <label for=P11DReport>P11D Report</label>
                    </div>
                </div>
            </div>
        </fieldset>

        <fieldset class="empty_space" ng-show="edit.BenefitModel.flex == 1">
            <legend>Others</legend>
            <div class="row m-t-20">
                <div class="col-sm-6" ng-show="edit.BenefitModel.flex == 1 && edit.BenefitModel.benefit_type == '9'">
                    <div class="checkbox check-default">
                        <input type="checkbox" id="holAdvanced" ng-model="edit.BenefitModel.holAdvancedChecked" ng-true-value="'1'" ng-false-value="'0'">
                        <label for=holAdvanced>Advanced Options</label>
                    </div>
                </div>
                <div class="col-sm-6" ng-show="edit.BenefitModel.flex == 1 &&  edit.BenefitModel.benefit_type == '14'">
                    <div class="checkbox check-default">
                        <input type="checkbox" id="penAdvanced" ng-model="edit.BenefitModel.penAdvancedChecked" ng-true-value="'1'" ng-false-value="'0'">
                        <label for=penAdvanced>Advanced Options</label>
                    </div>
                </div>
            </div>

            <div class="row m-t-20" ng-show="edit.BenefitModel.flex == 1">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>RENEWAL SELECTION</label>
                        <select class="form-control" ng-model="edit.BenefitModel.renewal_selection" ng-options="renewalName for renewalName in renewalNames"></select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>AGE BASIS</label>
                        <select class="form-control" ng-model="edit.BenefitModel.ageBasis" ng-options="ageBasisName for ageBasisName in ageBasisNames"></select>
                    </div>
                </div>
            </div>
            <div class="row m-t-20" ng-show="edit.BenefitModel.flex == 1">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>INITIAL DEFAULT</label>
                        <input type="text" class="form-control" ng-model="edit.BenefitModel.initialDefault"/>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>NEW JOINERS DEFAULT</label>
                        <input type="text" class="form-control" ng-model="edit.BenefitModel.default"/>
                    </div>
                </div>
            </div>
            <div class="row m-t-20" ng-show="edit.BenefitModel.flex == 1">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>CHARGING METHOD</label>
                        <select class="form-control" ng-model="edit.BenefitModel.charging" ng-options="chargingName for chargingName in chargingNames"></select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>TERMINATION METHOD</label>
                        <select class="form-control" ng-model="edit.BenefitModel.termination" ng-options="terminationName for terminationName in terminationNames"></select>
                    </div>
                </div>
            </div>
        </fieldset>


    </div>
    <div ng-show="panel.isSelected(2)">
        <p class="description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec feugiat, dolor vitae placerat molestie, turpis lectus faucibus neque, ut tristique.</p>
        <fieldset class="empty_space">
            <legend>Basic</legend>
            <div class="row m-t-10">
                <div class="col-sm-6 m-b-5">
                        <span class="uppercase_title" style="margin-left:2px;">Cover Levels</span>
                        <i class="fa fa-plus p-t-5 m-r-25 cursor_pointer" style="float: right; color: #958686; font-size: 10px;" ng-click="addRowCovers();" onclick="return false;"> ADD</i>
                </div>
                <div class="col-sm-6">
                    <span class="uppercase_title"><strong>Options</strong></span>
                </div>
            </div>
            <div class="row" style="padding-bottom: 10px;">
                <div class="col-sm-6">
                    <table class="table benefit_table">
                        <thead>
                            <tr>
                                <th class="uppercase_title col-sm-4">NAME</th>
                                <th class="uppercase_title col-sm-4">VALIDATION</th>
                                <th class="uppercase_title col-sm-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="cover in edit.BenefitRate.covers">
                                <td class="benefit_table_color">
                                    <input type="text" style="min-width:100px;" class="form-control" ng-model="cover.coverName"/>
                                </td>
                                <td class="benefit_table_color">
                                    <select class="form-control" style="min-width:100px;" ng-model="cover.validation" ng-options="valName for valName in valNames"></select>
                                </td>
                                <td class="benefit_table_color">
                                    <a data-ng-click="removeRowCovers($index);" onclick="return false;"><i class="pg-close cursor_pointer"></i> </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-6">
                    <div class="col-sm-12">
                        <div class="benefit_options">
                            <p style="font-weight: bold; display:inline;">ELIGIBILITY GROUPS<span class="text-uppercase m-l-15 group_summary" >{{BenefitRate.eligibilityGroupSummary}}</span></p><i class="fa fa-cog cursor_pointer" style="float: right;padding-top:6px;" ng-click="open_eligibilityGroups()"></i>

                            <div class="row eligibilityGroups p-t-5" ng-show="eligibilityGroups_open">
                                <div>
                                    <i class="pg-close fs-14 cursor_pointer" style="float: right;padding-top:6px;" ng-click="open_eligibilityGroups()"></i>
                                </div>
                                <div style="float:left; width:100%">
                                <fieldset class="empty_space m-t-10 p-b-10 p-l-10" style="background-color: #FFF;">
                                    <div class="checkbox check-success" style="display: block" ng-repeat="(key, type) in BenefitRate.eligibilityGroupOptions">
                                        <input id="checkbox_eligibility_group_{{key}}"
                                               type="checkbox"
                                               ng-checked="BenefitRate.eligibilityGroupCheckbox[type.name]"
                                               ng-model="BenefitRate.eligibilityGroupCheckbox[type.name]"
                                               ng-change="BenefitRate.eligibilityGroupCheckboxChange(key, type)"
                                            />
                                        <label for="checkbox_eligibility_group_{{key}}">{{type.name}}</label>
                                        <hr class="m-r-20" ng-show="type.name == 'All'"/>
                                    </div>
                                </fieldset>
                                </div>
                            </div>

                        </div>

                        <div class="benefit_options">

                            <p style="font-weight: bold; padding-bottom: 15px">AGE GROUPS<span class="text-uppercase m-l-15 group_summary">{{BenefitRate.ageSummary}}</span><i class="fa fa-cog cursor_pointer" style="float: right;padding-top:6px;" ng-click="open_ageGroups();"></i>

                            <div class="row ageGroups p-t-5" ng-show="ageGroups_open">
                                <a href="#" id="add_new" ng-click="addRowAges();" onclick="return false;"><i class="fa fa-plus" style="padding-top:6px;margin: auto;"> Add</i></a>
                                <i class="pg-close fs-14 cursor_pointer" style="float: right;padding-top:6px;" ng-click="open_ageGroups();"></i>

                                <table class="table benefit_table" style="margin-top: 5px !important; margin-bottom: 0px !important;">
                                    <thead>
                                        <tr>
                                            <th class="benefit_table_headings">AGE FROM</th>
                                            <th class="benefit_table_headings">AGE TO</th>
                                            <th class="benefit_table_headings"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="(index, item) in edit.BenefitRate.ages">
                                            <td>
                                                <input type="text" class="form-control" ng-model="item.ageFrom"  />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" ng-model="item.ageTo"  />
                                            </td>
                                            <td>
                                                <a data-ng-click="removeRowAges($index);" onclick="return false;"><i class="pg-close cursor_pointer"></i> </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="benefit_options">
                            <p style="font-weight: bold; display:inline;">GENDER<span class="text-uppercase m-l-15 group_summary" >{{BenefitRate.genderSummary}}</span></p><i class="fa fa-cog cursor_pointer" style="float: right;padding-top:6px;" ng-click="open_genderGroups()"></i>

                            <div class="row genderGroups p-t-5" ng-show="genderGroups_open">
                                <div>
                                    <i class="pg-close fs-14 cursor_pointer" style="float: right;padding-top:6px;" ng-click="open_genderGroups()"></i>
                                </div>
                                <div style="float:left; width:100%">
                                    <fieldset class="empty_space m-t-10 p-b-10 p-l-10" style="background-color: #FFF;">
                                        <div class="checkbox check-success" style="display: block" ng-repeat="(key, type) in BenefitRate.genderOptions">
                                            <input id="checkbox_gender_group_{{key}}"
                                                   type="checkbox"
                                                   ng-checked="BenefitRate.genderCheckbox[type.name]"
                                                   ng-model="BenefitRate.genderCheckbox[type.name]"
                                                   ng-change="BenefitRate.genderCheckboxChange(key, type)"
                                                />
                                            <label for="checkbox_gender_group_{{key}}">{{type.name}}</label>
                                            <hr class="m-r-20" ng-show="type.name == 'None'"/>
                                        </div>
    <!--                                    <hr/>-->
    <!--                                    <div class="checkbox check-success">-->
    <!--                                        <input id="checkbox_gender_group_2"-->
    <!--                                               type="checkbox"-->
    <!--                                               ng-checked="edit.BenefitRate.gender[1].value == 1"-->
    <!--                                               ng-model="edit.BenefitRate.gender[1].value"-->
    <!--                                               ng-change="BenefitRate.genderCheckboxChange(1)"-->
    <!--                                            />-->
    <!--                                        <label for="checkbox_gender_group_2">Female</label>-->
    <!--                                    </div>-->
    <!--                                    <br/>-->
    <!--                                    <div class="checkbox check-success">-->
    <!--                                        <input id="checkbox_gender_group_3"-->
    <!--                                               type="checkbox"-->
    <!--                                               ng-checked="edit.BenefitRate.gender[2].value == 1"-->
    <!--                                               ng-model="edit.BenefitRate.gender[2].value"-->
    <!--                                               ng-change="BenefitRate.genderCheckboxChange(2)"-->
    <!--                                            />-->
    <!--                                        <label for="checkbox_gender_group_3">Male</label>-->
    <!--                                    </div>-->
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                <!--                <div class="checkbox check-default">-->
                <!--                    <input type="checkbox" id="groupChecked" ng-model="groupChecked" ng-checked="edit.BenefitRate.groups != 0" data-ng-click="removeAllRowGroups()">-->
                <!--                    <label for = groupChecked>Groups</label>-->
                <!--                    <input type="checkbox" id="group1Checked" ng-model="group1Checked" ng-show="groupChecked">-->
                <!--                    <label for = group1Checked>Group 1</label>-->
                <!--                </div>-->
                <!--                <div class="checkbox check-default">-->
                <!--                    <input type="checkbox" id="ageChecked" ng-model="ageChecked" ng-checked="edit.BenefitRate.ages != 0" data-ng-click="removeAllRowAges()">-->
                <!--                    <label for = ageChecked>Age</label>-->
                <!--                    <a href="#" id="add_new" ng-click="addRowAges();" onclick="return false;"><i class="fa fa-cog" ng-show="edit.BenefitRate.ages != 0 || ageChecked"></i></a>-->
                <!--                    <table class="table">-->
                <!--                        <tr ng-repeat="item in edit.BenefitRate.ages">-->
                <!--                            <td>-->
                <!--                                <input type="text" class="form-control" ng-model="item.ageFrom"/>-->
                <!--                            </td>-->
                <!--                            <td>-->
                <!--                                <input type="text" class="form-control" ng-model="item.ageTo"/>-->
                <!--                            </td>-->
                <!--                            <td>-->
                <!--                                <a data-ng-click="removeRowAges($index);" onclick="return false;"><i class="pg-close"></i> </a>-->
                <!--                            </td>-->
                <!--                        </tr>-->
                <!--                    </table>-->
                <!--               </div>-->
                <!--                <div class="checkbox check-default">-->
                <!--                    <input type="checkbox" id="genderChecked" ng-model="genderChecked" ng-true-value="'1'" ng-false-value="'0'">-->
                <!--                    <label for = genderChecked>Gender</label>-->
                <!--                </div>-->
            </div>
            </div>
        </fieldset>
        <fieldset class="empty_space">
            <legend>Rate Matrix</legend>
            <div ng-repeat='group in edit.BenefitRate.eligibilityGroup'>
                <div class="uppercase_title p-t-10 p-b-5">{{group}}</div>
                <table class="table benefit_table" style="border-bottom: none;padding: 0px;">
                    <thead>
                        <tr>
                            <th class="uppercase_title col-sm-3">Cover Level</th>
                            <th class="uppercase_title col-sm-3">Validation</th>
<!--                            <th class="uppercase_title" ng-if="edit.BenefitRate.ages.length > 0" ng-class="{col-sm-1: edit.BenefitRate.ages.length > 0}">Age From</th>-->
<!--                            <th class="uppercase_title" ng-if="edit.BenefitRate.ages.length > 0" ng-class="{col-sm-1: edit.BenefitRate.ages.length > 0}">Age To</th>-->
<!--                            <th class="uppercase_title" ng-class="{col-sm-2: edit.BenefitRate.ages.length > 0, col-sm-3: edit.BenefitRate.ages.length == 0}">Gender</th>-->
<!--                            <th class="uppercase_title" ng-class="{col-sm-2: edit.BenefitRate.ages.length > 0, col-sm-3: edit.BenefitRate.ages.length == 0}">Cost</th>-->
                            <th class="uppercase_title col-sm-6" style="padding: 0">
                                    <span ng-if="edit.BenefitRate.ages.length > 0" class="col-sm-2" style="padding: 5px 20px !important;">Age From</span>
                                    <span ng-if="edit.BenefitRate.ages.length > 0" class="col-sm-2" style="padding: 5px 20px !important;">Age To</span>
<!--                                    <span ng-class="{edit.BenefitRate.ages.length > 0 ? 'col-sm-4' : 'col-sm-4'}">Gender</span>-->
<!--                                    <span ng-class="{edit.BenefitRate.ages.length > 0 ? 'col-sm-4' : 'col-sm-4'}">Cost</span>-->
                                    <span ng-if="edit.BenefitRate.gender != 'None'"
                                          ng-class="{'col-sm-2': edit.BenefitRate.ages.length > 0,
                                                     'col-sm-4': edit.BenefitRate.ages.length == 0,
                                                        }"
                                          style="padding: 5px 20px !important;">Gender</span>
                                    <span  ng-class="{  'col-sm-3': edit.BenefitRate.gender[0] != 'None' && edit.BenefitRate.ages.length > 0,
                                                        'col-sm-4': edit.BenefitRate.gender[0] != 'None' && edit.BenefitRate.ages.length == 0,
                                                        'col-sm-4': edit.BenefitRate.gender[0] == 'None' && edit.BenefitRate.ages.length > 0,
                                                        'col-sm-6': edit.BenefitRate.gender[0] == 'None' && edit.BenefitRate.ages.length == 0}"
                                           style="padding: 5px 20px !important;">Cost EE</span>
                                    <span  ng-class="{  'col-sm-3': edit.BenefitRate.gender[0] != 'None' && edit.BenefitRate.ages.length > 0,
                                                        'col-sm-4': edit.BenefitRate.gender[0] != 'None' && edit.BenefitRate.ages.length == 0,
                                                        'col-sm-4': edit.BenefitRate.gender[0] == 'None' && edit.BenefitRate.ages.length > 0,
                                                        'col-sm-6': edit.BenefitRate.gender[0] == 'None' && edit.BenefitRate.ages.length == 0}"
                                           style="padding: 5px 20px !important;">Cost ER</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody ng-repeat="c in edit.BenefitRate.covers">
                        <td class="benefit_table_td">{{ c.coverName }}</td>
                        <td class="benefit_table_td">{{ c.validation }}</td>
                        <td class="benefit_table_td" style="padding: 0 !important">
                            <table ng-show="edit.BenefitRate.gender.length > 0 && edit.BenefitRate.ages.length > 0">
                                <tbody ng-repeat="gender in edit.BenefitRate.gender">
                                <tr ng-repeat="a in edit.BenefitRate.ages">
                                    <td class="benefit_table_td col-sm-2">{{a.ageFrom}}</td>
                                    <td class="benefit_table_td col-sm-2">{{a.ageTo}}</td>
                                    <td class="benefit_table_td col-sm-2" ng-if="gender != 'None'">{{gender}}</td>
                                    <td ng-class="{'benefit_table_td',
                                                    'col-sm-3': gender != 'None',
                                                    'col-sm-4': gender == 'None'}">
                                        <input type="text" class="form-control" ng-model="edit.BenefitRate.rateData[group][c.key][a.key][gender]['ee']" />
                                    </td>
                                    <td ng-class="{'benefit_table_td',
                                                    'col-sm-3': gender != 'None',
                                                    'col-sm-4': gender == 'None'}">
                                        <input type="text" class="form-control" ng-model="edit.BenefitRate.rateData[group][c.key][a.key][gender]['er']" />
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <table ng-show="edit.BenefitRate.gender.length > 0 && (!edit.BenefitRate.ages || edit.BenefitRate.ages.length == 0)">
                                <tbody>
                                <tr ng-repeat="gender in edit.BenefitRate.gender">
                                    <td class="benefit_table_td col-sm-4" ng-if="gender != 'None'">{{gender}}</td>
                                    <td ng-class="{'benefit_table_td',
                                                    'col-sm-4': gender != 'None',
                                                    'col-sm-6': gender == 'None'}">
                                        <input type="text" class="form-control" ng-model="edit.BenefitRate.rateData[group][c.key][gender]['ee']" />
                                    </td>
                                    <td ng-class="{'benefit_table_td',
                                                    'col-sm-4': gender != 'None',
                                                    'col-sm-6': gender == 'None'}">
                                        <input type="text" class="form-control" ng-model="edit.BenefitRate.rateData[group][c.key][gender]['er']" />
                                    </td>
                                </tr>
                                </tbody>
                            </table>
    <!--                        <table ng-show="(!edit.BenefitRate.gender || edit.BenefitRate.gender.length == 0) && edit.BenefitRate.ages.length > 0">-->
    <!--                            <tbody>-->
    <!--                            <tr ng-repeat="a in edit.BenefitRate.ages">-->
    <!--                                <td class="benefit_table_td col-sm-3">{{a.ageFrom}}</td>-->
    <!--                                <td class="benefit_table_td col-sm-3">{{a.ageTo}}</td>-->
    <!--                                <td class="benefit_table_td col-sm-3">-->
    <!--                                    <input type="text" class="form-control" ng-model="edit.BenefitRate.rateData[group][c.key][a.key]['ee']" />-->
    <!--                                </td>-->
    <!--                                <td class="benefit_table_td col-sm-3">-->
    <!--                                    <input type="text" class="form-control" ng-model="edit.BenefitRate.rateData[group][c.key][a.key]['er']" />-->
    <!--                                </td>-->
    <!--                            </tr>-->
    <!--                            </tbody>-->
    <!--                        </table>-->
    <!---->
    <!--                        <table ng-show="(!edit.BenefitRate.gender || edit.BenefitRate.gender.length == 0) && (!edit.BenefitRate.ages || edit.BenefitRate.ages.length == 0)">-->
    <!--                            <tbody>-->
    <!--                            <tr>-->
    <!--                                <td class="benefit_table_td">-->
    <!--                                    <input type="text" class="form-control" ng-model="edit.BenefitRate.rateData[group][c.key]['ee']" />-->
    <!--                                </td>-->
    <!--                                <td class="benefit_table_td">-->
    <!--                                    <input type="text" class="form-control" ng-model="edit.BenefitRate.rateData[group][c.key]['er']" />-->
    <!--                                </td>-->
    <!--                            </tr>-->
    <!--                            </tbody>-->
    <!--                        </table>-->
                        </td>
                    </tbody>
                </table>
            </div>
        </fieldset>
    </div>
    <div ng-show="panel.isSelected(3)">
        <div class="row m-t-5">
            <div class="col-sm-6">
                <div class="checkbox check-default">
                    <input type="checkbox" id="Hide" ng-model="edit.BenefitModel.hideIneligibleChecked" ng-true-value="'1'" ng-false-value="'0'">
                    <label for=Hide>Hide Benefit If Ineligible</label>
                </div>
            </div>
        </div>
        <div class="row m-t-20">
            <div class="col-sm-20 p-l-20">
                <a id="add_new" ng-click="addRowConditions();" onclick="return false;"><i class="fa fa-plus-square"></i> Add new condition</a>
            </div>
        </div>
        <table class="table">
            <thead ng-hide="edit.BenefitEligibilityModel.length ==0">
            <tr>
                <th>Variable</th>
                <th>Logic Condition</th>
                <th>Value</th>
                <th></th>
            </tr>
            </thead>
            <tbody ng-hide="edit.BenefitEligibilityModel.length ==0">
            <tr ng-repeat="eligiblity_row in edit.BenefitEligibilityModel">
                <td>
                    <select class="form-control" ng-model="eligiblity_row.variable" ng-options="varName for varName in varNames"></select>
                </td>
                <td>
                    <select class="form-control" ng-model="eligiblity_row.symbol" ng-options="logicName for logicName in logicNames"></select>
                </td>
                <td>
                    <input type="text" class="form-control" name="valueCondition" ng-model="eligiblity_row.value">
                </td>
                <td>
                    <a data-ng-click="removeRowConditions($index)" onclick="return false;"><i class="pg-close"></i> </a>
                </td>
            </tr>
            </tbody>
            <tbody ng-show="edit.BenefitEligibilityModel.length == 0">
            <tr>
                <td colspan="4" class="text-center">Eligibility setting is empty</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div ng-show="panel.isSelected(4)">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <h6>Drop Files here</h6>

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
    <div ng-show="panel.isSelected(5)">
        <div class="row m-t-20" ng-show="edit.BenefitModel.flex == 1">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>DESCRIPTION</label>
                    <textarea style="resize: vertical;width: 100%;" class="form-control" ng-model="edit.BenefitModel.description"></textarea>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>TOOLTIP DESCRIPTION</label>
                    <textarea style="resize: vertical;width: 100%;" class="form-control" ng-model="edit.BenefitModel.tooltip_description"></textarea>
                </div>
            </div>
        </div>
        <div class="row m-t-20">
            <div class="col-sm-12">
                <label id="BenefitUniqueID">Benefit Unique ID :</label>&nbsp;<!--<label for = BenefitUniqueID ng-bind="edit.BenefitModel.benefitUniqueId"/>--><label for=BenefitUniqueID ng-model="edit.BenefitModel.benefitUniqueId">{{edit.BenefitModel.name + edit.BenefitModel.provider}}</>
            </div>
        </div>
        <div class="row m-t-20" ng-show="edit.BenefitModel.flex == 1">
            <div class="col-sm-6">
                <div class="checkbox check-default">
                    <input type="checkbox" id="BenefitSpouse" ng-model="edit.BenefitModel.spouseBenefitChecked" ng-true-value="'1'" ng-false-value="'0'" ng-change="clearSpouse()">
                    <label for=BenefitSpouse>Spouse Benefit</label>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group" ng-show="edit.BenefitModel.spouseBenefitChecked == 1">
                    <label>PARTNER COVER SAME LEVEL</label>
                    <select class="form-control" ng-model="edit.BenefitModel.sameLevel" ng-options="sameLevelName for sameLevelName in sameLevelNames"></select>
                </div>
            </div>
        </div>
        <div class="row m-t-20" ng-show="edit.BenefitModel.flex == 1">
            <div class="col-sm-6">
                <div class="checkbox check-default">
                    <input type="checkbox" id="StepRestrict" ng-model="edit.BenefitModel.stepRestrictionChecked" ng-true-value="'1'" ng-false-value="'0'" ng-change="clearStep()">
                    <label for=StepRestrict>Step Restriction</label>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group" ng-show="edit.BenefitModel.stepRestrictionChecked == 1">
                    <label>STEP TYPE</label>
                    <select class="form-control" ng-model="edit.BenefitModel.stepType" ng-options="sameTypeName for sameTypeName in sameTypeNames" ng-change="clearStepType()"></select>
                </div>
            </div>
        </div>
        <div class="row m-t-20" ng-show="edit.BenefitModel.stepType == 'Up' || edit.BenefitModel.stepType == 'Both'">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>STEP UP PER YEAR</label>
                    <input type="text" class="form-control" ng-model="edit.BenefitModel.step_up_per_year"/>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>STEP UP PER ENROLMENT</label>
                    <input type="text" class="form-control" ng-model="edit.BenefitModel.step_up_per_enrol"/>
                </div>
            </div>
        </div>
        <div class="row m-t-20" ng-show="edit.BenefitModel.stepType == 'Down' || edit.BenefitModel.stepType == 'Both'">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>STEP DOWN PER YEAR</label>
                    <input type="text" class="form-control" ng-model="edit.BenefitModel.step_down_per_year"/>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>STEP DOWN PER ENROLMENT</label>
                    <input type="text" class="form-control" ng-model="edit.BenefitModel.step_down_per_enrol"/>
                </div>
            </div>
        </div>
        <!--        <div class="row m-t-20" ng-show="edit.BenefitModel.flex == 1">-->
        <!--            <div class="col-sm-6">-->
        <!--                <div class="checkbox check-default">-->
        <!--                    <input type="checkbox" id="Funding" ng-model="edit.BenefitModel.fundingChecked" ng-true-value="'1'" ng-false-value="'0'" ng-change="clearFunding()">-->
        <!--                    <label for = Funding>Funding</label>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--            <div class="col-sm-6">-->
        <!--                <div class="form-group" ng-show="edit.BenefitModel.fundingChecked == 1">-->
        <!--                    <label>FUNDING VALUE</label>-->
        <!--                    <input class="form-control" ng-model="edit.BenefitModel.fundingValue">-->
        <!--                    </input>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->
        <div class="row m-t-20" ng-show="edit.BenefitModel.flex == 1">
            <div class="col-sm-6">
                <div class="checkbox check-default">
                    <input type="checkbox" id="BenefitTradeDown" ng-model="edit.BenefitModel.tradeDownChecked" ng-true-value="'1'" ng-false-value="'0'" ng-change="clearTrade()">
                    <label for=BenefitTradeDown>Trade Down</label>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group" ng-show="edit.BenefitModel.tradeDownChecked == 1">
                    <label>TRADE DOWN OPTIONS</label>
                    <select class="form-control" ng-model="edit.BenefitModel.trade" ng-options="tradeName for tradeName in tradeNames"></select>
                </div>
            </div>
        </div>
    </div>
    <hr/>
    <div class="row m-t-20">
        <div class="col-sm-6">
            <button class="btn btn-default" data-ng-click="$close()"><i class="pg-close"></i>
                Close
            </button>
        </div>
        <div class="col-sm-6">
            <button ng-if="$parent.allowEdit" class="btn btn-default blue pull-right" data-ng-click="modalOptions.submit();">
                Save
            </button>
        </div>
    </div>
</div>
</section>
