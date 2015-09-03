$(document).ready(function() {
    /* dcmodeller sliders */
    var sliders = $('.sliders-wrap .slider');
    if (sliders) {
        $.each(sliders,function(index, value){
            $(value).slider({
                orientation: "horizontal",
                range: "min",
                min: parseInt($(value).data('start'),10),
                max: parseInt($(value).data('end'),10),
                value: parseInt($(value).data('startat'),10),
                slide: function(event, ui){
                    var type = $(ui.handle).parent().data('type');
                    $(ui.handle).attr('data-progress',ui.value+type);
                },
                change: function(event, ui){
                    var type = $(ui.handle).parent().data('type');
                    $(ui.handle).attr('data-progress',ui.value+type);
                }
            });
            $(value).find('span.ui-slider-handle').attr('data-progress',parseInt($(value).data('startat'),10)+$(value).data('type'));
        });
    }
    var slider_dcmodeller1 = $('.slider-contribution .slider');
    if(slider_dcmodeller1){
        $.each(slider_dcmodeller1, function(index, value){
           $(value).slider({
               step: 0.5
           })
        });
    }
    var slider_dcmodeller2 = $('.slider-age .slider');
    if(slider_dcmodeller2){
        $.each(slider_dcmodeller2, function(index, value){
            $(value).slider({
                slide: function(event, ui){
                    var type = $(ui.handle).parent().data('type');
                    $(ui.handle).attr('data-progress',ui.value+type);
                    $('#chosen-pension-age').text(ui.value);
                },
                change: function(event, ui){
                    var type = $(ui.handle).parent().data('type');
                    $(ui.handle).attr('data-progress',ui.value+type);
                    $('#chosen-pension-age').text(ui.value);
                }
            });
            /* age from data atribute from slider */
            $('#chosen-pension-age').text($(value).data('startat'));
        });
    }
    var fixed = $('#fixed'),
        percentual = $('#percentual'),
        fixedamount = $('.fixed-amount'),
        wrappercentual = $('.percentual-wrap');
    if(fixed){
        fixed.on('click', function(){
            fixed.addClass('active');
            percentual.removeClass('active');
            fixedamount.removeClass('not-selected');
            wrappercentual.addClass('not-selected');
        });
    }
    if(percentual){
        percentual.on('click', function(){
            fixed.removeClass('active');
            percentual.addClass('active');
            fixedamount.addClass('not-selected');
            wrappercentual.removeClass('not-selected');
        });
    }

    /**
     * Contribution change slider
     * @type {*|jQuery|HTMLElement}
     */
    var contribution_slider = $('#contribution_slider');
    if (contribution_slider.length > 0){
        contribution_slider.slider({
            orientation: "horizontal",
            range: "min",
            step: 0.5,
            min: contribution_slider.data('start'),
            max: parseInt(contribution_slider.data('end'),10),
            value: parseInt(contribution_slider.data('startat'),10),

            slide: function(event, ui){


                /**
                 *
                 "min" => 3.5,
                 "max" => 20,
                 "step" => 0.5,
                 "ER_max" => 1, //ER base
                 "ER_max_member" => 1.5, //ER max
                 "status" => "valid",
                 "ratio" => 0.5,
                 "default_setting" => array(
                 'attribute' => 'assigned_pension_scheme_contribution',
                 'pension_code' => array('11')
                 ),
                 "default_value" => 1,
                 "new_joiner_pension_code" => array('11'),
                 "rounding" => "05floor"
                 */

                var ratio = 0.5,
                    ER_max = 1,
                    ER_max_member = 1.5,
                    min = contribution_slider.data('start');


                var ER_percent = ((ui.value-min)* ratio) + ER_max;
                ER_percent = (ER_percent == 0) ? ER_max : ER_percent;
                ER_percent = (ER_percent >= ER_max_member) ? ER_max_member : ER_percent;
                ER_percent = Math.floor(ER_percent) + ( Math.round( (ER_percent - Math.floor(ER_percent)) ) ? 0.5 : 0.0 );


                // ER
                var ee_contribution = ui.value,
                    er_contribution = ER_percent,
                    total_contribution = ee_contribution + er_contribution;

                $('#er_contribution').html(er_contribution);
                $('#total_contribution').html(total_contribution);

                $('#input_er_contribution').val(er_contribution);
                $('#input_ee_contribution').val(ee_contribution);
                $('#input_total_contribution').val(total_contribution);

                $(ui.handle).attr('data-progress',ui.value + '%');

            },
            change: function(event, ui){
                $(ui.handle).attr('data-progress',ui.value + '%');
            }
        });
        contribution_slider.slider('value','3.5')
    }
});