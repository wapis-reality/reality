<div class="modal-header clearfix text-left">
    <button type="button" class="close m-t-5 close_btn" data-ng-click="$close()" data-dismiss="modal" aria-label="Close"><i class="pg-close fs-14"></i></button>
    <h5><span id="domwin-title">Edit Scheduled</span></h5></div>
<div class="modal-body">


    <table aria-describedby="tableWithExportOptions_info" role="grid" class="table dataTable no-footer" id="tableWithExportOptions">
        <tbody>
        <tr>
            <td>Switched on</td>
            <td>
                <input name="data[Report][status__chck]" id="ReportStatus_" name_addon="__chck" value="0" type="hidden">
                <input type="checkbox" name="data[Report][status]" class="checkbox append-plus switched" checked="checked" id="ReportStatus">
            </td>
        </tr>

        <tr>
            <td>Minute</td>
            <td>
                <input name="data[Report][minute]" class="form-control minute" type="text" id="ReportMinute">
            </td>
        </tr>

        <tr>
            <td>Hour</td>
            <td><input name="data[Report][hour]" class="form-control hour" type="text" id="ReportHour"></td>
        </tr>

        <tr>
            <td>Day of the month</td>
            <td>
                <input name="data[Report][day_of_month]" class="form-control day_month" type="text" value="*" id="ReportDayOfMonth">
            </td>
        </tr>

        <tr>
            <td>Month</td>
            <td>
                <div class="over_radio">
                    <input type="radio" name="data[Report][radio]" id="ReportRadioValueMonth" class="switch-radio" data-switch-on=".month_static" data-switch-off=".month" checked="checked" value="value_month">Enter the value
                </div>
                <input name="data[Report][month_input]" class="form-control month_static" type="text" id="ReportMonthInput" value="*">
                <div class="over_radio">
                    <input type="radio" name="data[Report][radio]" id="ReportRadioMonth" class="switch-radio" data-switch-on=".month" data-switch-off=".month_static" value="month">Select the month
                </div>


                <select name="data[Report][month]" class="form-control  month" disabled="disabled" id="ReportMonth">
                    <option value="">&nbsp;</option>
                    <option value="January" selected="selected">January</option>
                    <option value="February">February</option>
                    <option value="March">March</option>
                    <option value="April">April</option>
                    <option value="May">May</option>
                    <option value="June">June</option>
                    <option value="July">July</option>
                    <option value="August">August</option>
                    <option value="September">September</option>
                    <option value="October">October</option>
                    <option value="November">November</option>
                    <option value="December">December</option>
                </select>
            </td>
        </tr>

        <tr>
            <td>Day of the week</td>
            <td>
                <div class="over_radio">
                    <input type="radio" name="data[Report][radio1]" id="ReportRadio1ValueWeek" class="switch-radio" data-switch-on=".day_of_week" data-switch-off=".day" checked="checked" value="value_week">
                    Enter the value
                </div>
                <input name="data[Report][day_of_week]" class="form-control day_of_week" value="*" type="text" id="ReportDayOfWeek">
                <div class="over_radio">
                    <input type="radio" name="data[Report][radio1]" id="ReportRadio1Week" class="switch-radio" data-switch-on=".day" data-switch-off=".day_of_week" value="week">
                    Select the day of the week
                </div>


                <select name="data[Report][day]" class="form-control day" disabled="disabled" id="ReportDay">
                    <option value="">&nbsp;</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                    <option value="Saturday">Saturday</option>
                    <option value="Sunday">Sunday</option>
                    <option value="Monday" selected="selected">Monday</option>
                </select>
            </td>
        </tr>
        </tbody>
    </table>

    <hr/>
    <div class="row m-t-20">
        <div class="col-sm-6">
            <button type="button" class="btn btn-default close_btn" data-ng-click="$close()"><i class="pg-close"></i>Close</button>
        </div>
        <div class="col-sm-6">
            <button class="btn btn-default blue pull-right form_submit append_schedule" data-ng-click="$close()">Save</button>
        </div>
    </div>
</div>
</div>


<script>
    //@todo do not have time, just temporary the html is not done, but the scripts want to run. We have to init when the modal is done.
    setTimeout(function() {

        $(".minute").val($(".scheduled-edit-minute").val());
        $(".hour").val($(".scheduled-edit-hour").val());
        $(".day_month").val($(".scheduled-edit-month1").val());
        $(".month_static").val($(".scheduled-edit-month").val());
        $(".day_of_week").val($(".scheduled-edit-day").val());
//        $(".scheduled-edit-minute").val(minute);
//        $(".scheduled-edit-hour").val(hour);
//        $(".scheduled-edit-month1").val(month1);
//        $(".scheduled-edit-month").val(month);
//        $(".scheduled-edit-day").val(day);

        SwitchRadio = Class.create({
            config: {
                class: ".switch-radio"
            },

            init: function () {
                this.element = $(this.config.class);
                this.click();
            },

            click: function () {
                this.element.click($.proxy(function (e) {
                    this.enable = $($(e.target).data("switch-on"));
                    this.disable = $($(e.target).data("switch-off"));

                    this.switch();
                }, this));
            },

            switch: function () {
                this.enable.prop("disabled", false);
                this.disable.prop("disabled", true);
            }
        });

        $(document).ready(function () {

            switchRadio = new SwitchRadio();

            $(".append_schedule").click(function () {
                var minute = $(".minute").val(),
                    hour = $(".hour").val(),
                    month1 = $(".day_month").val(),
                    month = ($(".month_static").prop("disabled") == true) ? $(".month").val() : $(".month_static").val(),
                    day = ($(".day_of_week").prop("disabled") == true) ? $(".day").val() : $(".day_of_week").val()

                $(".append_schedule_value").html(hour + " | " + minute + " | " + month1 + " | " + month + " | " + day);

//                html = '<input type="hidden" name="data[Report][scheduled][minute]" value="' + minute + '"/>';
//                html += '<input type="hidden" name="data[Report][scheduled][hour]" value="' + hour + '"/>';
//                html += '<input type="hidden" name="data[Report][scheduled][day_of_month]" value="' + month1 + '"/>';
//                html += '<input type="hidden" name="data[Report][scheduled][month]" value="' + month + '"/>';
//                html += '<input type="hidden" name="data[Report][scheduled][day_of_week]" value="' + day + '"/>';
//
//                if ($('.switched').prop('checked')) {
//                    html += '<input type="hidden" name="data[Report][scheduled][active]" value="1"/>';
//                } else {
//                    html += '<input type="hidden" name="data[Report][scheduled][active]" value="0"/>';
//                }
                $(".scheduled-edit-minute").val(minute);
                $(".scheduled-edit-hour").val(hour);
                $(".scheduled-edit-month1").val(month1);
                $(".scheduled-edit-month").val(month);
                $(".scheduled-edit-day").val(day);

                if ($('.switched').prop('checked')) {
                    $(".scheduled-edit-switched").val(1);
                } else {
                    $(".scheduled-edit-switched").val(0);
                }

                $(".scheduled-edit-minute").trigger("input");
                $(".scheduled-edit-hour").trigger("input");
                $(".scheduled-edit-month1").trigger("input");
                $(".scheduled-edit-month").trigger("input");
                $(".scheduled-edit-day").trigger("input");
                $(".scheduled-edit-switched").trigger("input");

//                $(".append_schedule_value_hidden").html(html);
            });
        });
    });

</script>