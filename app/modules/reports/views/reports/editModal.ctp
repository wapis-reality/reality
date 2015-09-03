<div class="modal-header clearfix text-left">
    <button type="button" class="close m-t-5 close_btn" data-ng-click="$close()" data-dismiss="modal" aria-label="Close"><i class="pg-close fs-14"></i></button>

    <h5><span id="domwin-title">{{window.title}}</span></h5></div>
<div class="clearfix">
<div class="modal-body">
    <ul class="nav nav-tabs nav-tabs-simple">
        <li class="active"><a data-toggle="tab" href="#tabReport">Report</a></li>
    </ul>

        <input type="text" ng-model="edit.fields" class="field-sortable-angular" style="display: none;"/>
        <input type="text" ng-model="edit.conditions" class="condition-angular" style="display: none;"/>
        <input type="hidden" ng-model="edit.id"/>
        <div class="tab-content">

            <div class="tab-pane active" id="tabReports">
                <input type="hidden" ng-model="data.index" value="0" />

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" ng-model="edit.name" />
                    </div>

                    <!-- SCHEDULED -->
                    <div class="col-md-6 form-group">
                        <div class="col-md-12">
                            <label>Scheduled</label>
                        </div>

                        <div class="col-md-9 m-t-5">
                            <div class="append_schedule_value_hidden" style="display: none;">
                                <input type="text" name="data[Report][scheduled][id]" ng-model="edit.scheduled.id" class="scheduled-edit-id" ng-show="edit.scheduled.id != ''"/>
                                <input type="text" name="data[Report][scheduled][minute]" ng-model="edit.scheduled.minute" class="scheduled-edit-minute"/>
                                <input type="text" name="data[Report][scheduled][hour]" ng-model="edit.scheduled.hour" class="scheduled-edit-hour"/>
                                <input type="text" name="data[Report][scheduled][day_of_month]" ng-model="edit.scheduled.day_of_month" class="scheduled-edit-month1"/>
                                <input type="text" name="data[Report][scheduled][month]" ng-model="edit.scheduled.month" class="scheduled-edit-month"/>
                                <input type="text" name="data[Report][scheduled][day_of_week]" ng-model="edit.scheduled.day_of_week" class="scheduled-edit-day"/>
                                <input type="text" name="data[Report][scheduled][active]" ng-model="edit.scheduled.active" class="scheduled-edit-switched"/>
                            </div>

                             <span class="append_schedule_value"  style="float: left; margin-right: 10px;">
                                    <span ng-show="edit.scheduled.id == 0">
                                        Not specified
                                    </span>

                                 <span ng-show="edit.scheduled.id != 0">
                                    {{edit.scheduled.hour}} | {{edit.scheduled.minute}} | {{edit.scheduled.day_of_month}}  | {{edit.scheduled.month}} | {{edit.scheduled.day_of_week}}
                                 </span>
                            </span>
                        </div>

                        <input type="hidden" ng-model="data.scheduled.id" />

                        <div class="col-md-3 m-t-5">
    <!--                        <a href="/reports/schedule/1"  data-domwin-style="slide-right" data-action="domwin" data-toggle="tooltip" data-placement="top" title="Scheduled" class="sftp_ftp_connection_btn"><i class="fa fa-cog"></i></a>-->

                            <a href="/reports/schedule/1" onclick="return false;" data-domwin-icon="" title="Scheduled"  class="text-black" data-placement="top" class="sftp_ftp_connection_btn" ng-click="scheduledWindow(3)"> <i class="fa fa-cog"></i></a>
                            <a href="javascript:;" class="remove-scheduled"><i class="fa  fa-trash-o"></i></a>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- EMAIL -->
                <div class="row" style="background: #f8f8f8; border:1px solid #f8f8f8; border-width: 1px 0;">
                    <div class="col-md-6 form-group">

                        <div class="checkbox check-default">
                            <input type="checkbox" id="ReportEmailExists" ng-model="email_show" ng-checked="edit.email != ''" class="checked_email ng-untouched ng-valid ng-dirty ng-valid-parse">
                            <label for="ReportEmailExists">Email</label>
                        </div>
                    </div>

                    <div ng-show="email_show" class="col-md-6 form-group">
                        <input type="text" class="form-control" ng-model="edit.email"/>
                        <div style="clear: both;"></div>
                    </div>
                </div>

                <!-- SFTP/FTP connection -->
    <!--            <div class="row" >-->
    <!--                <div class="col-md-6 form-group">-->
    <!--                    <div class=" ">-->
    <!--                        <input type="checkbox" class="checkbox checked_connection" />-->
    <!--                        <label for="ReportSftpFtpExists">SFTP/FTP</label>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--                <div class="col-md-6">-->
    <!--                    <div class="sftp_ftp_content append-plus" style="" data-what=".checked_connection" data-where=".sftp_ftp_content" data-when=".checked_connection" data-option="checkbox" data-module="Report">-->
    <!--                        <span class="sftp_ftp_fix" style="display: inline-block">-->
    <!--                            <span class="connection-name">-->
    <!--                                <input type="hidden" name="data[Report][connection_hidden]" value=""/>-->
    <!--                            </span>/-->
    <!--                        </span>-->
    <!---->
    <!--                        <span class="sftp_ftp_container" style="display: inline-block;">-->
    <!--                            <input type="text" class="form-control" name="data[Report][path]" value="">-->
    <!--                        </span>-->
    <!--                        <a href="/reports/connection"data-domwin-style="slide-right" data-action="domwin" data-toggle="tooltip" data-placement="top" title="Connections" class="sftp_ftp_connection_btn"><i class="fa fa-cog"></i></a>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->

                <hr>

                <!-- FILE FORMAT -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-12 form-group">
                            <label>File format</label>
                        </div>

                        <div class="col-md-12 form-group">
                            <input type="radio" class="choose_format" name="file_format[]" ng-model="edit.type" value="csv" data-element=".csv" /> CSV

                             <span class="csv none-radio" style="margin-left: 5px; display: inline-block;">
    <!--                            <a href="/reports/csv/1" data-domwin-style="slide-right" data-action="domwin" data-toggle="tooltip" data-placement="top" title="Csv options" class="sftp_ftp_connection_btn"><i class="fa fa-cog"></i></a>-->
                            </span>
                        </div>

                        <div class="col-md-12 form-group">
                            <input type="radio" class="choose_format" name="file_format[]" ng-model="edit.type" data-element=".xlsx1" value="xlsx" /> XLSX
                        </div>

                        <div class="col-md-12 form-group">
                            <input type="radio" class="choose_format" name="file_format[]" ng-model="edit.type" data-element=".pdf1" value="pdf"/> PDF
                        </div>
                    </div>
                </div>

                <hr>

                <!-- FIELDS AND CONDITIONS-->
                <div class="row">
                    <!-- FIELDS -->
                    <div class="col-md-6 form-group">
                        <div class="col-md-10">
                            <label>Fields</label>

                            <select class="field-select block form-control">
                                <option data-ng-repeat="reportselect in report_select" value="{{reportselect.value}}">{{reportselect.text}}</option>
                            </select>
                        </div>
                        <div class="col-md-1 m-t-25">

                            <a href="#" class="btn blue m-t-20 add-sortable">+</a>
                        </div>

                        <div class="row">
                            <div class="col-md-12 m-t-15">
                                <div class="field-container append-plus" data-what=".field-select" data-where=".field-container" data-when=".add-sortable" data-option="sortable" data-module="Report/sortable_field_select">


                                </div>
                            </div>
                        </div>
                </div>
                    <!-- CONDITIONS -->
                    <div class="col-md-6">
                        <div class="col-md-10">
                            <label>Conditions</label>
<!--                            <select class="condition-select block form-control">-->
<!--                                <option value="employee.id">Id</option>-->
<!--                                <option value="employee.nino">Nino</option>-->
<!--                                <option value="employee_employment.employee_number">Employee number</option>-->
<!--                                <option value="employee.first_name">First name</option>-->
<!--                                <option value="employee.last_name">Last name</option>-->
<!--                                <option value="employee.email">Email</option>-->
<!--                                <option value="employee_employment.primary_payroll_code">Payroll code</option>-->
<!--                                <option value="employee_employment.payroll_area">Payroll area</option>-->
<!--                                <option value="employee_pensions.pension_scheme">Pension scheme</option>-->
<!--                                <option value="employee_pensions.employee_contribution_pay_period">EE contribution</option>-->
<!--                                <option value="employee_pensions.employer_contribution_pay_period">ER contribution</option>-->
<!--                                <option value="employee_pensions.employee_contribution">EE contribution %</option>-->
<!--                                <option value="employee_pensions.employer_contribution">ER contribution %</option>-->
<!--                                <option value="employee_attribute.ae_date">AE Date</option>-->
<!--                                <option value="employee_attribute.assessment_date">Assessment Date</option>-->
<!--                                <option value="employee_attribute.assigned_pension_scheme">Assigned pension scheme</option>-->
<!--                                <option value="employee_attribute.assigned_pension_scheme_contribution">Assigned EE contribution</option>-->
<!--                                <option value="employee_attribute.assigned_pension_scheme_employer_contribution">Assigned ER contribution</option>-->
<!--                            </select>-->
                            <select class="condition-select block form-control">
                                <option data-ng-repeat="reportselect in report_select" value="{{reportselect.value}}">{{reportselect.text}}</option>
                            </select>
                        </div>

                        <div class="col-md-1 m-t-25">
                            <a href="#" class="btn blue m-t-20 add-condition">+</a>
                        </div>

                        <div class="row">
                            <div class="col-md-12 m-t-15">
                                <div class="condition-container append-plus" data-what=".condition-select" data-where=".condition-container" data-when=".add-condition" data-option="spec" data-module="Report/spec_condition_select" data-elements="value|select|text" data-select="<=|>=|>|<|=">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="csv_options">
                    <input type="hidden" name="data[Report][csv_delimiter]" value=";">
                </div>
            </div>

            <div class="row" style="background: #f8f8f8; border:1px solid #f8f8f8; border-width: 1px 0;">
                <div class="col-md-6 form-group">
                    <div class="checkbox check-default">
                        <input type="checkbox" id="ReportDone" ng-model="edit.report_done" ng-checked="edit.report_done" class="ng-untouched ng-valid ng-dirty ng-valid-parse">
                        <label for="ReportDone">This report is done</label>
                    </div>
                </div>
            </div>

            <hr/>
            <div class="row m-t-20">
                <div class="col-sm-6">
                    <button type="button" class="btn btn-default close_btn" data-ng-click="$close()"><i class="pg-close"></i>Close</button>
                </div>
                <div class="col-sm-6">
                    <button ng-if="$parent.allowEdit" class="btn btn-default blue pull-right form_submit" data-ng-click="modalOptions.submit();">Save</button>
                </div>
            </div>
        </div>
</div>
</div>

<script>

    function buildSortable()
    {
        var arr = jQuery.parseJSON($(".field-sortable-angular").val());
        var html = "";

        if(arr !== null) {
            console.log("in");
            $.each(arr, function(k, v) {
                html += '<li class="append-plus-sortable">';
                    html += '<span class="sortable-name">'+v.ReportsFieldsModel.name+'</span>';
                    html += '<span class="remove-appended"><i class="fa fa-remove"></i></span>';
                    html += '<span class="clear"></span>';
                    html += '<input type="hidden" name="data[value][]" class="field-select_field-container_value" value="'+v.ReportsFieldsModel.table+'"/>';
                    html += '<input type="hidden" name="data[text][]" class="field-select_field-container_text" value="'+v.ReportsFieldsModel.name+'"/>';
                html += '</li>';
            });
        }

        $(".field-container").html(html);
    }

    function buildCondition()
    {
        var arr = jQuery.parseJSON($(".condition-angular").val());
        var html = '';

        if(arr !== null) {
            $.each(arr, function (k, v) {
                html += '<div class="spec-container">';
                html += '<span class="spec-name">';
                    html += v.ReportsConditionsModel.name;
                    html += '<input type="hidden" name="data[value][]" class="condition-select_condition-container_value" value="'+v.ReportsConditionsModel.table+'"/>';
                    html += '<input type="hidden" name="data[text][]" class="condition-select_condition-container_text" value="'+v.ReportsConditionsModel.name+'"/>';
                html += '</span>';

                html+= '<span class="spec-select">';
                    html+= '<select name="data[select][]" onchange="funnyBlur()">';
                        if(v.ReportsConditionsModel.condition == "=") {html+= '<option SELECTED>=</option>';} else {html+= '<option>=</option>';}
                        if(v.ReportsConditionsModel.condition == "<") {html+= '<option SELECTED><</option>';} else {html+= '<option><</option>';}
                        if(v.ReportsConditionsModel.condition == ">") {html+= '<option SELECTED>></option>';} else {html+= '<option>></option>';}
                        if(v.ReportsConditionsModel.condition == "<=") {html+= '<option SELECTED><=</option>';} else {html+= '<option><=</option>';}
                        if(v.ReportsConditionsModel.condition == ">=") {html+= '<option SELECTED>>=</option>';} else {html+= '<option>>=</option>';}
                html+= '</select>';
                html+= '</span>';

                html+= '<span class="spec-input">';
                    html+= '<input type="input" onblur="funnyBlur()" name="data[text_input][]" class="form-control condition-select_condition-container_text1" value="'+v.ReportsConditionsModel.value+'"/>';
                html+= '</span>';

                html += ' <span class="remove-appended"><i class="fa fa-remove"></i></span>';
                html += '</div>';
            });
        }

        $(".condition-container").html(html);
    }

//@todo do not have time, just temporary the html is not done, but the scripts want to run. We have to init when the modal is done.
setTimeout(function() {
    $(document).ready(function() {
        buildSortable();
        buildCondition();

        //Init in each element
        $(".append-plus").each(function (i) {
            new AppendPlus({
                "element": this
            });
        });



        });

    $(document).ready(function() {

        $(".choose_format").click(function() {
            $(".none-radio").css({"display":"none"});

            if ($(this).is(':checked')) {
                $($(this).data("element")).css({"display":"inline-block"});
            } else {
                $($(this).data("element")).css({"display":"none"});
            }
        });
    });


    function removeCssFixed(target) {
        var element = target.parent().find(".field-select_field-container_value").val().replace(".","_").replace(" ","_");
        $("."+element).remove();
    }

    /**
     *  When sortable an element re order the csv fixed options
     * @param event
     * @param ui
     */
    function orderCsv(event, ui) {
        var element = $(ui.item);
        var next = $(ui.item).next();

        element = element.find(".field-select_field-container_value").val().replace(".","_").replace(" ","_");

        if(next.is('li')) {
            next = next.find(".field-select_field-container_value").val().replace(".","_").replace(" ","_");
        } else {
            next = null;
        }

        var clone =  $("."+element).clone();



        if(next == null) {
            $("."+element).remove();
            $("#csv_options").append(clone);
        } else {
            if($("."+next).length > 0) {
                $("."+element).remove();
                $("."+next).before(clone);
            }
        }

    }

    $("body").delegate(".remove-scheduled","click",function() {

        $(".append_schedule_value").html("Not specified");
        $(".scheduled-edit-id").val("");
        $(".scheduled-edit-switched").val("empty");
        $(".scheduled-edit-switched").trigger("input");
        $(".scheduled-edit-id").trigger("input");
    });

    $(document).ready(function() {
        $(".generate_report").click(function(e) {
            e.preventDefault();

            var link = $(this).attr("href");

            $.ajax({
                type: "POST",
                url: link,
                data: $('.modal-body form').serialize(),
                success: function(data) {
                    data = jQuery.parseJSON(data);
                    location.href = data.data.location;

                }
            });
        });
    });
},500);
</script>