<div class="modal-header clearfix text-left">
    <button type="button" class="close m-t-5 close_btn" ng-click="$close()" data-dismiss="modal" aria-label="Close">
        <i class="pg-close fs-14"></i></button>
    <h5><span id="domwin-title">Create a new scheme</span></h5>
</div>
<div class="modal-body">
    <selection class="tab">
        <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
            <li ng-class="{active:panel.isSelected(1)}"><a href ng-click="panel.selectTab(1)">Basic</a></li>
            <li ng-class="{active:panel.isSelected(2)}"><a href ng-click="panel.selectTab(2)">Paygroups</a></li>
            <li ng-class="{active:panel.isSelected(3)}"><a href ng-click="panel.selectTab(3)">Schemes</a></li>
            <li ng-class="{active:panel.isSelected(4)}"><a href ng-click="panel.selectTab(4)">Sections</a></li>
            <li ng-class="{active:panel.isSelected(5)}"><a href ng-click="panel.selectTab(5)">Users</a></li>
            <li ng-class="{active:panel.isSelected(6)}"><a href ng-click="panel.selectTab(6)">Comms</a></li>
        </ul>

        <div ng-show="panel.isSelected(1)">
            <div class="row m-t-20">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Employer name</label>
                        <input type="text" class="form-control" ng-model="editableClientScheme.ClientScheme.name"/>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Regulatory Staging Date</label>
                        <input calendar class="form-control" ng-model="editableClientScheme.ClientScheme.effective_date"/>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Actual Staging Date</label>
                        <input calendar class="form-control" ng-model="editableClientScheme.ClientScheme.name"/>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Pre-assesment</label>
                        <select class="form-control">
                            <option>Yes</option>
                            <option>No</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Contributions to be calculated</label>
                        <select class="form-control">
                            <option>Yes</option>
                            <option>No</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Is Payroll restricted</label>
                        <select class="form-control">
                            <option>Yes</option>
                            <option>No</option>
                        </select>
                    </div>
                </div>
            </div>

            <hr/>
            <div class="row m-t-20">
                <div class="col-sm-6">
                    <button class="btn btn-default" ng-click="$close()"><i class="pg-close"></i>Close</button>
                </div>
                <div class="col-sm-6">
                    <button class="btn btn-default blue pull-right" ng-click="panel.selectTab(2)">Next</button>
                </div>
            </div>
        </div>


        <div ng-show="panel.isSelected(2)" class="p-t-20 p-b-20">

            <div class="pull-right m-b-10">
                <a onclick="return false;" class="btn btn-default btn-sm" title="Add Scheme" ng-click="addPaygroup()"><i class="fa fa-plus-circle"></i> Add Paygroup</a>
            </div>
            <div class="clearfix"></div>

            <div class="padding-10" ng-repeat="(key,item) in paygroups" style="background: rgba(0,0,0,.02); margin-bottom:10px; position: relative">
                <a href="#" onclick="return false;" ng-click="removePaygroup($index)" style="position: absolute; right: 10px; top:5px; z-index:99"><span class="fa fa-close"></span></a>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Pay Group name</label>
                            <input type="text" class="form-control" ng-model="item.scheme_number"/>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Payroll frequency</label>
                            <select class="form-control">
                                <option>Monthly</option>
                                <option>Weekly</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Payroll upload method</label>
                            <select class="form-control">
                                <option>On-line</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Pay reference period</label>
                            <input calendar class="form-control" ng-model="item.agancy_number"/>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Payroll cut-off date</label>
                            <input calednar class="form-control" ng-model="item.default_retirement_age"/>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Contribution deduction date</label>
                            <input calendar class="form-control" ng-model="item.agancy_number"/>

                        </div>
                    </div>
                </div>

            </div>

            <hr/>
            <div class="row m-t-20">
                <div class="col-sm-6">
                    <button class="btn btn-default" ng-click="panel.selectTab(1)"><i class="pg-close"></i>Prev</button>
                </div>
                <div class="col-sm-6">
                    <button class="btn btn-default blue pull-right" ng-click="panel.selectTab(3)">Next</button>
                </div>
            </div>
        </div>

        <div ng-show="panel.isSelected(3)" class="p-t-20 p-b-20">

            <div class="pull-right m-b-10">
                <a onclick="return false;" class="btn btn-default btn-sm" title="Add Scheme" ng-click="addScheme()"><i class="fa fa-plus-circle"></i> Add Scheme</a>
            </div>
            <div class="clearfix"></div>

            <div class="padding-10" ng-repeat="(key,item) in schemes" style="background: rgba(0,0,0,.02); margin-bottom:10px; position: relative">
                <a href="#" onclick="return false;" ng-click="removeScheme($index)" style="position: absolute; right: 10px; top:5px; z-index:99"><span class="fa fa-close"></span></a>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Scheme number</label>
                            <input type="text" class="form-control" ng-model="item.scheme_number"/>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Scheme name</label>
                            <input type="text" class="form-control" ng-model="item.scheme_name"/>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Scheme type</label>
                            <input type="text" class="form-control" ng-model="item.scheme_type"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Agency name</label>
                            <input type="text" class="form-control" ng-model="item.agancy_name"/>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Agency number</label>
                            <input type="text" class="form-control" ng-model="item.agancy_number"/>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Default retirement age</label>
                            <input type="text" class="form-control" ng-model="item.default_retirement_age"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Provider</label>
                            <select class="form-control">
                                <option>Provider 1</option>
                                <option>Provider 2</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group">
                            <label>Scheme microsite URL</label>
                            <input type="text" class="form-control" ng-model="item.microsite"/>
                        </div>
                    </div>
                </div>
            </div>

            <hr/>
            <div class="row m-t-20">
                <div class="col-sm-6">
                    <button class="btn btn-default" ng-click="panel.selectTab(1)"><i class="pg-close"></i>Prev</button>
                </div>
                <div class="col-sm-6">
                    <button class="btn btn-default blue pull-right" ng-click="panel.selectTab(3)">Next</button>
                </div>
            </div>
        </div>

        <div ng-show="panel.isSelected(4)" class="p-t-20 p-b-20">

            <div class="pull-right m-b-10">
                <a onclick="return false;" class="btn btn-default btn-sm" title="Add Scheme" ng-click="addSection()"><i class="fa fa-plus-circle"></i> Add Section</a>
            </div>
            <div class="clearfix"></div>

            <div class="padding-10" ng-repeat="(key,item) in sections" style="background: rgba(0,0,0,.02); margin-bottom:10px; position: relative">
                <a href="#" onclick="return false;" ng-click="removeSection($index)" style="position: absolute; right: 10px; top:5px; z-index:99"><span class="fa fa-close"></span></a>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Scheme Number</label>
                            <input type="text" class="form-control" ng-model="item.scheme_number"/>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Employer ID</label>
                            <input type="text" class="form-control" ng-model="item.id"/>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Section Number</label>
                            <input type="text" class="form-control" ng-model="item.scheme_type"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Section Name</label>
                            <input type="text" class="form-control" ng-model="item.agancy_name"/>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Payment Arrangement Number</label>
                            <input type="text" class="form-control" ng-model="item.agancy_number"/>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Segment by this Worker Group(s):</label>
                            <input type="text" class="form-control" ng-model="item.default_retirement_age"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Segment by this Payroll Paygroup:</label>
                            <input type="text" class="form-control" ng-model="item.default_retirement_age"/>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Salary Sacrifice/Exchange?</label>
                            <select class="form-control"><option>Yes</option></select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Employee Communication Method</label>
                            <input type="text" class="form-control" ng-model="item.default_retirement_age"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Employer Contribution %</label>
                            <input type="text" class="form-control" ng-model="item.default_retirement_age"/>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Employee Contribution % (Gross)</label>
                            <input type="text" class="form-control" ng-model="item.default_retirement_age"/>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Representative Annual Salary</label>
                            <input type="text" class="form-control" ng-model="item.default_retirement_age"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Earnings basis being used</label>
                            <input type="text" class="form-control" ng-model="item.default_retirement_age"/>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>If 'Other' Please provide description</label>
                            <input type="text" class="form-control" ng-model="item.default_retirement_age"/>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Section Frequency</label>
                            <input type="text" class="form-control" ng-model="item.default_retirement_age"/>
                        </div>
                    </div>
                </div>
            </div>

            <hr/>
            <div class="row m-t-20">
                <div class="col-sm-6">
                    <button class="btn btn-default" ng-click="panel.selectTab(1)"><i class="pg-close"></i>Prev</button>
                </div>
                <div class="col-sm-6">
                    <button class="btn btn-default blue pull-right" ng-click="panel.selectTab(3)">Next</button>
                </div>
            </div>
        </div>
        <div ng-show="panel.isSelected(5)" class="p-t-20 p-b-20">


            <div class="pull-right m-b-10">
                <a onclick="return false;" class="btn btn-default btn-sm" title="Add Scheme" ng-click="addUser()"><i class="fa fa-plus-circle"></i> Add User</a>
            </div>
            <div class="clearfix"></div>

            <div class="padding-10" ng-repeat="(key,item) in users" style="background: rgba(0,0,0,.02); margin-bottom:10px; position: relative">
                <a href="#" onclick="return false;" ng-click="removeUser($index)" style="position: absolute; right: 10px; top:5px; z-index:99"><span class="fa fa-close"></span></a>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>First name</label>
                            <input type="text" class="form-control" ng-model="item.firstname"/>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Last name</label>
                            <input type="text" class="form-control" ng-model="item.lastname"/>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Role</label>
                            <input type="text" class="form-control" ng-model="item.role"/>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>E-mail</label>
                            <input type="text" class="form-control" ng-model="item.email"/>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row m-t-20">
                <div class="col-sm-6">
                    <button class="btn btn-default" ng-click="panel.selectTab(2)"><i class="pg-close"></i>Prev
                    </button>
                </div>
                <div class="col-sm-6">
                    <button class="btn btn-default blue pull-right" ng-click="panel.selectTab(4)">Next</button>
                </div>
            </div>
        </div>

        <div ng-show="panel.isSelected(6)" class="p-t-20 p-b-20">
            <h3 style="font-size:14px;line-height: 20px;font-weight: bold;margin-bottom: 0px;padding-bottom: 0;">Provider 1</h3>
            <h4 style="font-size: 13px;line-height: 9px;font-weight: bold;font-style: italic;padding-left: 10px;">Scheme 1</h4>
            <div class="padding-10 clearfix" style="border-bottom: 1px solid #efefef;" >
                <div class="col-sm-6" style="font-weight: bold;">Section</div>
                <div class="col-sm-3" style="font-weight: bold; text-align: center">Email</div>
                <div class="col-sm-3" style="font-weight: bold; text-align: center">PDF</div>
            </div>
            <div class="padding-10  clearfix" style="border-bottom:1px solid #efefef; ">
                <div class="col-sm-6">Section 1</div>
                <div class="col-sm-3" style="text-align: center"><div class="checkbox check-success " style="margin:0"><input id="checkbox1{{key}}" type="checkbox" value="1" ng-model="item.email"><label>&nbsp;</label></div></div>
                <div class="col-sm-3" style="text-align: center"><div class="checkbox check-success " style="margin:0"><input id="checkbox1{{key}}" type="checkbox" value="1" ng-model="item.pdf"><label>&nbsp;</label></div></div>
            </div>
            <div class="padding-10  clearfix" style="border-bottom:1px solid #efefef; ">
                <div class="col-sm-6">Section 2</div>
                <div class="col-sm-3" style="text-align: center"><div class="checkbox check-success " style="margin:0"><input id="checkbox1{{key}}" type="checkbox" value="1" ng-model="item.email"><label>&nbsp;</label></div></div>
                <div class="col-sm-3" style="text-align: center"><div class="checkbox check-success " style="margin:0"><input id="checkbox1{{key}}" type="checkbox" value="1" ng-model="item.pdf"><label>&nbsp;</label></div></div>
            </div>

            <h3 style="font-size:14px;line-height: 20px;font-weight: bold;margin-bottom: 0px;padding-bottom: 0;">Provider 2</h3>
            <h4 style="font-size: 13px;line-height: 9px;font-weight: bold;font-style: italic;padding-left: 10px;">Scheme 1</h4>
            <div class="padding-10 clearfix"  style="border-bottom: 1px solid #efefef;" >
                <div class="col-sm-6" style="font-weight: bold;">Section</div>
                <div class="col-sm-3" style="font-weight: bold; text-align: center">Email</div>
                <div class="col-sm-3" style="font-weight: bold; text-align: center">PDF</div>
            </div>
            <div class="padding-10  clearfix" style="border-bottom:1px solid #efefef; ">
                <div class="col-sm-6">Section 1</div>
                <div class="col-sm-3" style="text-align: center"><div class="checkbox check-success " style="margin:0"><input id="checkbox1{{key}}" type="checkbox" value="1" ng-model="item.email"><label>&nbsp;</label></div></div>
                <div class="col-sm-3" style="text-align: center"><div class="checkbox check-success " style="margin:0"><input id="checkbox1{{key}}" type="checkbox" value="1" ng-model="item.pdf"><label>&nbsp;</label></div></div>
            </div>
            <h4 style="font-size: 13px;line-height: 9px;font-weight: bold;font-style: italic;padding-left: 10px;">Scheme 2</h4>
            <div class="padding-10 clearfix"  style="border-bottom: 1px solid #efefef;">
                <div class="col-sm-6" style="font-weight: bold;">Section</div>
                <div class="col-sm-3" style="font-weight: bold; text-align: center">Email</div>
                <div class="col-sm-3" style="font-weight: bold; text-align: center">PDF</div>
            </div>
            <div class="padding-10  clearfix" style="border-bottom:1px solid #efefef; ">
                <div class="col-sm-6">Section 1</div>
                <div class="col-sm-3" style="text-align: center"><div class="checkbox check-success " style="margin:0"><input id="checkbox1{{key}}" type="checkbox" value="1" ng-model="item.email"><label>&nbsp;</label></div></div>
                <div class="col-sm-3" style="text-align: center"><div class="checkbox check-success " style="margin:0"><input id="checkbox1{{key}}" type="checkbox" value="1" ng-model="item.pdf"><label>&nbsp;</label></div></div>
            </div>

            <div class="row m-t-20">
                <div class="col-sm-6">
                    <button class="btn btn-default" ng-click="panel.selectTab(2)"><i class="pg-close"></i>Prev
                    </button>
                </div>
                <div class="col-sm-6">
                    <button class="btn btn-default blue pull-right" ng-click="submitForm()">Submit</button>
                </div>
            </div>
        </div>
    </selection>
</div>
<div class="clearfix"></div>