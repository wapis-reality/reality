$(document).ready(function() {
    if ($('.hide-button')) {
        $('.hide-button').on('click', function (e) {
            e.preventDefault();
            var target = $(this).attr('rel');
            var ico = $(this).find('i');
            var label = $(this).attr('data-switch-label');

            if(target && $(target)){
                if($(target).hasClass('none')){
                    $(target).removeClass('none');
                    if($(ico)){
                        if($(ico).hasClass('glyphicon-arrow-down')) {
                            $(ico).removeClass('glyphicon-arrow-down');
                            $(ico).addClass('glyphicon-arrow-up');
                        }
                        if($(ico).hasClass('glyphicon-download')) {
                            $(ico).removeClass('glyphicon-download');
                            $(ico).addClass('glyphicon-upload');
                        }
                    }

                }else{
                    $(target).addClass('none');
                    if($(ico)){
                        if($(ico).hasClass('glyphicon-arrow-up')) {
                            $(ico).removeClass('glyphicon-arrow-up');
                            $(ico).addClass('glyphicon-arrow-down');
                        }
                        if($(ico).hasClass('glyphicon-upload')) {
                            $(ico).removeClass('glyphicon-upload');
                            $(ico).addClass('glyphicon-download');
                        }


                    }
                }

                if(label != ''){
                    if($(this).find('span')[0]) {
                        var tmp = $(this).children('span').html();
                        $(this).children('span').text(label);
                    }else{
                        var tmp = $(this).html();
                        $(this).html(label);
                    }
                    $(this).attr('data-switch-label', tmp);
                }
            }
        });
    }
    if ($('.show-tooltip')) {
        $('.show-tooltip').on('mouseover', function (e) {
            var target = $(this).attr('rel');

            $('.tooltip-panel').addClass('none');
            if(target && $(target)){
                $(target).removeClass('none');
            }
        });
        /*$('.show-tooltip').on( "mouseout", function (e) {
            var target = $(this).attr('rel');
            if(target && $(target)){
                $(target).addClass('none');
            }
        });*/
    }
    if($('#all-tooltips')){
        $('#all-tooltips').click( function(e){
            e.preventDefault();
            if (!$(this).hasClass('show')){
                $('.tooltip-panel').removeClass('none');
                $($(this).find('span')).html('Close all sections');
            } else {
                $('.tooltip-panel').addClass('none');
                $($(this).find('span')).html('View all sections simultaneously');
            }
            $(this).toggleClass('show');

        })
    }
    var benefit_edit = $('.btn-edit');
    if(benefit_edit){
        benefit_edit.on('click', function(e){
            e.preventDefault();
            var btn = $(e.target);
            var parentElement = btn.closest('div.row');
            parentElement.find('.edit-part').removeClass('none');
            parentElement.find('.view-part').addClass('none');
        })
    }
    var benefit_cancel = $('.btn-cancel');
    if(benefit_cancel){
        benefit_cancel.on('click', function(e){
            e.preventDefault();
            var btn = $(e.target);
            var parentElement = btn.closest('div.row');
            parentElement.find('.edit-part').addClass('none');
            parentElement.find('.view-part').removeClass('none');
        });
    }
    var btn_expand = $('.btn-expander');
    if(btn_expand){
        btn_expand.on('click', function(e){
           var icon = $(e.target);
            if(icon){
                if(icon.hasClass('glyphicon-circle-arrow-down')){
                    icon.removeClass('glyphicon-circle-arrow-down').addClass('glyphicon-circle-arrow-up')
                }else if(icon.hasClass('glyphicon-circle-arrow-up')){
                    icon.addClass('glyphicon-circle-arrow-down').removeClass('glyphicon-circle-arrow-up');
                }
            }
        });
    }
    var btn_expand = $('.btn-expand-panel');
    if(btn_expand){
        btn_expand.on('click', function(e){
            var icon = $(e.target);
            if(icon){
                if(!icon.hasClass('glyphicon')){
                    icon = icon.find('span.glyphicon');
                }
                if(icon.hasClass('glyphicon-menu-down')){
                    icon.removeClass('glyphicon-menu-down').addClass('glyphicon-menu-up')
                }else if(icon.hasClass('glyphicon-menu-up')){
                    icon.addClass('glyphicon-menu-down').removeClass('glyphicon-menu-up');
                }
            }
        });
    }

    $(function () {
        $('#myTab a:first').tab('show')
    })

    $('.inlineEdit_edit_btn').click(function(e){
        e.preventDefault();
        var parent = $(e.target).closest('.row');

        $(parent).find('.text_value').addClass('none');
        $(parent).find('.input_value').removeClass('none');

        $(parent).find('.inlineEdit_edit_btn').addClass('none');
        $(parent).find('.inlineEdit_cancel_btn').removeClass('none');
        $(parent).find('.inlineEdit_save_btn').removeClass('none');
    });

    $('.inlineEdit_cancel_btn').click(function(e){
        e.preventDefault();
        var parent = $(e.target).closest('.row');

        $(parent).find('.text_value').removeClass('none');
        $(parent).find('.input_value').addClass('none');

        $(parent).find('.inlineEdit_edit_btn').removeClass('none');
        $(parent).find('.inlineEdit_cancel_btn').addClass('none');
        $(parent).find('.inlineEdit_save_btn').addClass('none');
    })
    $('.inlineEdit_save_btn').click(function(e){
        e.preventDefault();
    })

    $('.datepicker').each(function(i, element){
        $(element).datepicker();
    });

    if($("#editNomineeModal")){
        $("#editNomineeModal").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);

            $(this).find(".modal-body").load(link.attr("data-href"), function(){
                $('.datepicker').each(function(i, element){
                    $(element).datepicker();
                });
            });
        });
    }


    if($('#change_password')){
        var validate = function() {
            var errors = [];
            var pass = $('#Password').val();
            var passNew = $('#PasswordNew').val();
            var passNewConf = $('#PasswordNewConfirm').val();

            if (pass == '' || pass.length < 6){
                errors.push('Password must be at least 6 characters long');
            }
            if (passNew == '' || passNew.length < 6){
                errors.push('New password must be at least 6 characters long');
            }
            if (passNew != passNewConf){
                errors.push('Confirm password and New password are different');
            }

            if(errors.length > 0){
                return errors;
            }else{
                return true;
            }
        }
    }

    if($('#change-pass')){
        $('#change-pass').click(function(e){
            e.preventDefault();
            var not_errors = validate();
            if(not_errors !== true){
                alert(not_errors.join('\n'));
            }else{
                alert('Password changed');
            }
        });
    }

    if($('#payslips')){
        function show(obj){
            var target = $(obj).attr('rel');
            $('.btn-colapse').each( function(i, item){
                if($(item).attr('rel') != $(obj).attr('rel')) {
                    hide(item);
                }
            });
            $($(target)[0]).removeClass('none');

            $(obj).removeClass('btn-expand');
            $(obj).addClass('btn-colapse');
            $(obj).find($('.glyphicon')).removeClass('glyphicon-menu-down').addClass('glyphicon-menu-up')

            $(obj).unbind();
            $(obj).click(function(e){
                e.preventDefault();
                hide(obj);
            })
        }
        function hide(obj){
            var target = $(obj).attr('rel');

            $($(target)[0]).addClass('none');

            $(obj).removeClass('btn-colapse');
            $(obj).addClass('btn-expand');
            $(obj).find($('.glyphicon')).removeClass('glyphicon-menu-up').addClass('glyphicon-menu-down')

            $(obj).unbind();
            $(obj).click(function(e){
                e.preventDefault();
                show(obj);
            });
        }
        $('.btn-expand').click(function(e){
            e.preventDefault();
            show( this );
        });
    }
    if($('.submit')){
        $('.submit').click(function(e){
            e.preventDefault();
            $(this).closest('form').submit();
        })
    }
    if($('.btn-save')){
        $('.btn-save').click(function(e){
            e.preventDefault();
            var btn = $(this);
            var form = $(btn).closest($('form'));
            $.post($(form).attr('action'), $(form).serialize(),function( ret ) {
                if(ret){
                    if(ret.result == true){
                        var parentElement = btn.closest('div.row');
                        parentElement.find('.edit-part').addClass('none');
                        parentElement.find('.view-part').removeClass('none');

                        var donors = parentElement.find($('[data-donor]'));
                        if(donors){
                            donors.each(function(i, item){
                                var tag = $(item).prop('tagName');
                                if(tag == 'SELECT') {
                                    var value = $($(item).find('option:selected')).text();
                                }else{
                                    var value = $(item).val();
                                }
                                parentElement.find('[data-acceptor="'+$(item).attr('data-donor')+'"]').html(value);
                            });
                        }

                        sum = recalc_flex() * 12;
                        benefitModeller.update(sum,sum,sum);
                    }else{
                        alert(ret.msg);
                    }
                }
            }, "json");

        });
    }


    function recalc_flex(){
        var sum_val = 0;
        $.each($('input.get-value'), function (i,el){
            var el = $(el),
                val = parseFloat(el.val());
            if (el.val() != '') {
                sum_val += val;
            }
        });

        $('#total-flex-cost').html('£' + Math.round(sum_val*100)/100);
        return sum_val;
    }

    if($('#benefits')){
        $('.set-value').on('change', function(e){
            //console.log($(e.target).find('option:selected')[0]);
            var value = $($(e.target).find('option:selected')[0]).attr('rel');
            if(typeof value == 'undefined'){
                value == 0;
            }
            value = parseFloat(value);
            var salary = parseFloat($(e.target).attr('rel'));

            var option = $($(e.target).find('option:selected')[0]);
            var select = $(option.parents('select')[0]);
            var hidden = $(select.prevAll('input.get-value'));

            $(hidden).val(value);

            var donor = $(hidden).attr('data-donor');
            var parentElement = $(e.target).closest('.row');
            if(donor){
                parentElement.find('[data-acceptor="'+donor+'"]').html(value);
            }
        })
    }
    if($('#benefit_dependants')){

        if($("#editDependantModal")){
            $("#editDependantModal").on("show.bs.modal", function(e) {
                var link = $(e.relatedTarget);

                $(this).find(".modal-content").load(link.attr("data-href"), function(){
                    $('.datepicker').each(function(i, element){
                        $(element).datepicker();
                    });
                });
            });
        }
    }
    if($('#nomineeList')){

        if($("#nomineeList")){
            $("#editNomineeModal").on("show.bs.modal", function(e) {
                var link = $(e.relatedTarget);

                $(this).find(".modal-body").load(link.attr("data-href"), function(){
                    $('.datepicker').each(function(i, element){
                        $(element).datepicker();
                    });
                });
            });
        }
    }
    $('[data-toggle="tooltip"]').tooltip({html: true});


});

$(document).ready(function() {
    $('.stop_link').on('click', function (e) {
        e.preventDefault();
        alert("The file has not been assigned to the link.");
    })
});


