$(document).ready(function() {
    if($('.modal-save')){
        $('.modal-save').click(function(e){
            e.preventDefault();
            var btn = $(this);
            var form = $(btn).closest($('form'));
            if(form) {
                $.post($(form).attr('action'), $(form).serialize(), function (ret) {
                    if (ret) {
                        if (ret.result == true) {
                            $('.modal').modal('hide');
                            var widgetId = $($(btn).closest('.modal-content')).attr('data-widget-id');

                            if (widgetId && $(btn).attr('data-refresh-target')) {
                                $.get('/rewardr-demo/preview/widget/' + widgetId, null, function (html) {
                                    $($(btn).attr('data-refresh-target')).html(html);
                                });
                            }
                            if ($(btn).attr('data-refresh-page')) {
                                document.location.href =  $(btn).attr('data-refresh-page');
                            }
                        } else {
                            alert(ret.msg);
                        }
                    }
                }, "json");
            }

        });
    }

    if($('.ajax-action')){
        $('.ajax-action').click(function(e){
            e.preventDefault();
            var conf = true;
            if($(this).attr('data-confirm')){
                conf = confirm($(this).attr('data-confirm'));
            }
            if(conf) {
                $.get($(this).attr('href'), null, function (json) {
                    if (json) {
                        if (json.result == true) {
                            if ($(this).attr('data-success')) {
                                if ($(this).attr('data-success') == 'modal-close') {
                                    $('.modal').modal('hide');
                                }
                                if ($(this).attr('data-refresh-target')) {

                                    if (json.data_widget_id) {
                                        $.get('/rewardr-demo/preview/widget/' + json.data_widget_id, null, function (html) {
                                            $($(this).attr('data-refresh-target')).html(html);
                                            $('.modal-backdrop.fade.in').remove();
                                        }.bind(this));
                                    }
                                }
                            }
                        } else {
                            alert(ret.msg);
                        }
                    }
                }.bind(this), "json");
            }
        });

    }
});