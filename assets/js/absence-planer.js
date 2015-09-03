

$(document).ready(function() {
    if($('#absenceplanner')){


        $('.update').click(function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            var target = $(this).attr('data-target');

            if(!target || !$(target)[0]){
                console.log('Element '+target+' not found, content cannot be placed');
                return false;
            }
            $.ajax({
                method: "GET",
                dataType: "html",
                url: url,
                success: function (data) {
                    $(target).html(data);
                },
                error: function (xhr, status) {
                    $(target).html('');
                    console.log('Failed load content from "'+url+'"');
                }
            });
        });

        $("#editModal").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);

            $(this).find(".modal-body").load(link.attr("data-href"), function(){
                if($('.btn-save-modal')){
                    $('.btn-save-modal').click(function(e){
                        e.preventDefault();
                        var btn = $(this);
                        var form = $(btn).closest($('form'));
                        $.post($(form).attr('action'), $.merge( $(form).serialize(), {'btn-date' :btn.attr('data-date')}), function( ret ) {
                            if(ret){
                                if(ret.result == true){


                                    $.get( $('.refresh_planer').attr('href') , null, function(data){
                                        $("#editModal").modal('hide');
                                        $("#editModal").remove();
                                        //$('.modal-backdrop.fade.in').removeClass('in');
                                        $('.modal-backdrop.fade.in').remove();
                                        $('#absenceplanner').html(data);

                                    });
                                }else{
                                    alert(ret.msg);
                                }
                            }
                        }, "json");

                    });
                }

                $('.datepicker').each(function(i, element){
                    $(element).datepicker({"defaultDate":($(element).val() != '' ? new Date($(element).val()) : null)});
                });
            });
        });
    }
});
