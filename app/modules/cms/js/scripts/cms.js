var module_controller = 'angular_requests';


    var header =
        '<div class="row full-height no-margin b-b b-grey bg-white   relative" style="min-height: 90px !important;">' +
        '<div class="b-grey b-a logo_upload_2 ui-widget-content" data-system-setting-param="logo_position_1" data-system-setting-param-load="size_position" data-resizable="true" style="position: absolute; left: 10px; top:5px; ">' +
        '<img src="" data-system-setting-param="logo1" data-system-setting-param-load="src" style="height:75px; min-width:70px; min-height:25px;" height="100%"/>' +
        '</div>' +
        '</div>' +
        '<div class="navigation b-grey b-t hint-text text-center font-montserrat relative">' +
        '<ul class="no-style clearfix">' +
        '<li class="col-sm-2 selected"><a href="#" class="bt_primary_color f_primary_color">Menu item 1</a></li>' +
        '<li class="col-sm-2"><a href="#">Menu item 2</a></li>' +
        '<li class="col-sm-2"><a href="#">Menu item 3</a></li>' +
        '<li class="col-sm-2"><a href="#">Menu item 4</a></li>' +
        '</ul>' +
        '<i class="fa fa-gear top-right" data-toggle="modal" data-target="#configPanel"></i>' +
        '</div>' +
        '<div class="row full-height no-margin b-b b-grey bg-white llelement  relative" style="min-height: 70px !important;" data-position="1">' +

        '</div>';



    var footer =     '<div class="row full-height no-margin b-b b-t b-grey relative llelement" style="min-height: 70px !important;" data-position="2">' +
        '<div class="b-grey b-a logo_upload_2 ui-widget-content" data-system-setting-param="logo_position_2" data-system-setting-param-load="size_position" data-resizable="true" style="position: absolute; left: 10px; top:5px; ">' +
        '<img src="" data-system-setting-param="logo2" data-system-setting-param-load="src" style="height:55px; min-width:70px; min-height:25px;" height="100%"/>' +
        '</div>' +
        '</div>';


var layouts = {
    '1': header + '<div class="row full-height no-margin"><div class="col-md-9 no-padding b-r b-grey sm-b-b full-height"><div class="bg-white full-height llelement p-t-5 p-b-5" style="min-height: 270px !important;" data-position="3"></div></div><div class="col-md-3 no-padding full-height" style="min-height: 270px !important;"><div class="placeholder full-height llelement p-t-5 p-b-5" style="min-height: 270px"  data-position="4"></div></div></div>' + footer,
    '2': header + '<div class="row full-height no-margin"><div class="col-md-3 no-padding full-height" style="min-height: 270px !important;"><div class="placeholder full-height llelement p-t-5 p-b-5" data-position="3" style="min-height: 270px !important;"></div></div><div class="col-md-6 no-padding b-r b-l b-grey sm-b-b full-height"><div class="bg-white full-height llelement p-t-5 p-b-5" style="min-height: 270px !important;" data-position="4"></div></div><div class="col-md-3 no-padding full-height" style="min-height: 270px !important;"><div class="placeholder full-height llelement p-t-5 p-b-5" style="min-height: 270px"  data-position="5"></div></div></div>' + footer,
    '3': header + '<div class="row full-height no-margin"><div class="col-md-12 no-padding b-r b-l b-grey sm-b-b full-height"><div class="bg-white full-height llelement p-t-5 p-b-5" style="min-height: 270px !important;" data-position="3"></div></div></div>' + footer
};

function imageToDataUri(img, width, height) {

    img_width = img.width;
    img_height = img.height;

    if( img_width > width || img_height > height ) {
        if( img_width > img_height ) {
            width = width
            height = width * img_height / img_width;
        } else {
            height = width
            width = height * img_width / img_height;
        }
    } else {
        width = img_width;
        height = img_height;
    }


    // create an off-screen canvas
    var canvas = document.createElement('canvas'),
        ctx = canvas.getContext('2d');

    // set its dimension to target size
    canvas.width = width;
    canvas.height = height;

    // draw source image into the off-screen canvas:
    ctx.drawImage(img, 0, 0, width, height);

    // encode image to data-uri with base64 version of compressed image
    return canvas.toDataURL();
}

function uniqid(prefix, more_entropy) {
    //  discuss at: http://phpjs.org/functions/uniqid/
    // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    //  revised by: Kankrelune (http://www.webfaktory.info/)
    //        note: Uses an internal counter (in php_js global) to avoid collision
    //        test: skip
    //   example 1: uniqid();
    //   returns 1: 'a30285b160c14'
    //   example 2: uniqid('foo');
    //   returns 2: 'fooa30285b1cd361'
    //   example 3: uniqid('bar', true);
    //   returns 3: 'bara20285b23dfd1.31879087'

    if (typeof prefix === 'undefined') {
        prefix = '';
    }

    var retId;
    var formatSeed = function (seed, reqWidth) {
        seed = parseInt(seed, 10)
            .toString(16); // to hex str
        if (reqWidth < seed.length) { // so long we split
            return seed.slice(seed.length - reqWidth);
        }
        if (reqWidth > seed.length) { // so short we pad
            return Array(1 + (reqWidth - seed.length))
                    .join('0') + seed;
        }
        return seed;
    };

    // BEGIN REDUNDANT
    if (!this.php_js) {
        this.php_js = {};
    }
    // END REDUNDANT
    if (!this.php_js.uniqidSeed) { // init seed with big random int
        this.php_js.uniqidSeed = Math.floor(Math.random() * 0x75bcd15);
    }
    this.php_js.uniqidSeed++;

    retId = prefix; // start with prefix, add current milliseconds hex string
    retId += formatSeed(parseInt(new Date()
            .getTime() / 1000, 10), 8);
    retId += formatSeed(this.php_js.uniqidSeed, 5); // add seed hex string
    if (more_entropy) {
        // for more entropy we add a float lower to 10
        retId += (Math.random() * 10)
            .toFixed(8)
            .toString();
    }

    return retId;
}

function rtrim(str, chr) {
    if (typeof str == "undefined") {
        return false;
    }
    var rgxtrim = (!chr) ? new RegExp('\\s+$') : new RegExp(chr + '+$');
    return str.replace(rgxtrim, '');
}

var App = Class.create({
    name: 'AppClass',
    request: function (url, get, data, onComplete) {
        var internal_url = rtrim(url, '/'),
            type = 'GET';

        if ($.isArray(get)) {
            internal_url = internal_url + '/' + get.join('/') + '/';
        } else {
            internal_url = internal_url + '/' + get + '/';
        }

        if (!data) {
            data = {}
        } else {
            type = 'POST';
        }

        $.ajax({
            type: type,
            dataType: "json",
            url: internal_url,
            data: data
        }).done($.proxy(function (json) {
            if (json) {
                if (json.result == true) {
                    if (onComplete) {
                        onComplete(json);
                    }
                }
            }
        }, this))
    },

    init: function () {

    },

    init_events: function () {
        $('#cancel_submit_params').on('click', $.proxy(this.close_preference, this));
        $('#submit_params').on('click', $.proxy(this.submit_preference, this));
    },

    /**
     * Open widget preference panel
     */
    open_preference: function () {
        $('#tabPreference').removeClass('none');
        this.load_preference_data($('#preferenePanelWidgetId').val());
    },

    /**
     * Close widget preference panel
     */
    close_preference: function () {
        $('#tabPreference').addClass('none');
    },

    load_preference_data: function (widgetId) {
        var Form = $('#preferenePanelForm');

        this.request(module_controller+'/load_preference/', widgetId, {}, function (json) {
            if (json.data) {
                $.each(json.data, function (param, value) {
                    var el = Form.find('[data-param=' + param + ']');
                    if (el.attr('type') == 'checkbox') {
                        if (value == 1) {
                            el.prop("checked", true);
                        } else {
                            el.prop("checked", false);
                        }
                    } else {
                        el.val(value);
                    }

                })
            }
        })
    },

    update_widget_params: function (json) {
        var id = json.data.widget_id;
        var type = json.data.widget_type,
            widgetCls = pages.layout.widget_list[id];

        switch (type) {
            case null: //map
                this.init_gmap(id, "", json.data.params);
                break;
            case "youtube":
                jQuery(".widget[data-widget-id='" + id + "']").find("img").attr("src", this.video_preview(json.data.params.url, jQuery(".widget[data-widget-id='" + id + "']")));
                break;

            case "vimeo":
                this.video_preview(json.data.params.url, jQuery(".widget[data-widget-id='" + id + "']"));
                break;
            default:
                widgetCls.apply_params(widgetCls.element, json.data.params);
                break;
        }
    },

    /**
     * submit preference
     */
    submit_preference: function (e) {
        e.preventDefault();
        var WidgetId = $('#preferenePanelWidgetId').val(),
            Form = $('#preferenePanelForm');

        this.request(module_controller+'/submit_preference/', null, Form.serialize(), $.proxy(function (json) {
            this.update_widget_params(json);
            this.close_preference();

        }, this))

    },

    video_preview: function (url, element) {
        if (url.indexOf("youtube") > -1) {
            url = url.match("v=([a-zA-Z0-9]+)&?");
            if (url) {
                video_id = url[1];
                return "http://img.youtube.com/vi/" + video_id + "/0.jpg";
            }


        } else {
            video_id = url.substring(url.lastIndexOf('/') + 1);

            $.ajax({
                type: 'GET',
                url: 'http://vimeo.com/api/v2/video/' + video_id + '.json',
                jsonp: 'callback',
                dataType: 'jsonp',
                success: function (data) {
                    element.find("img").attr("src", data[0].thumbnail_large);
                }
            });
        }
    },

    init_gmap: function (id, element, params) {
        var address = "London";
        var marker = "London";
        var zoom = 15

        if (id != "") {
            element = $(".widget[data-widget-id='" + id + "']");

            parent = element.find(".map-container").parent();
            element.find(".map-container").remove();
            parent.append("<div class='map-container'></div>");

            address = params.address;
            marker = params.marker;
            zoom = parseInt(params.zoom);
        }

        //var map = this.element.find(".map-container");
        var map = element.find(".map-container");

        map.css({"min-height": "400px"});

        map.gmap3({
            map: {
                address: address,
                options: {
                    zoom: zoom,
                    mapTypeControl: true,
                    mapTypeControlOptions: {
                        style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
                    },
                    navigationControl: true,
                    scrollwheel: true,
                    streetViewControl: true,
                    scrollwheel: false,
                    draggable: true
                }
            },
            marker: {
                address: marker
            }
        });
    }
});

window.App = new App();

var WidgetClass = Class.create({
        name: 'WidgetClass',
        html: '<div class="m-l-5 m-r-5 m-b-5"></div>',
        db_id: null,
        element: null,
        name: null,
        urls: {
            'remove_widget': '/'+module_controller+'/removeLayoutWidgetEditor/null/',
            'update_param': '/'+module_controller+'/setWidgetParamEditor/null/',
            'add_default_params': '/'+module_controller+'/addDefaultParamsEditor/null/',
            'remove_widget_param_group': '/'+module_controller+'/remove_widget_param_group/',
            'update_position': '/'+module_controller+'/updatePositionEditor/null/'
        },
        /**
         * @todo ... it's not needed, remove!!! and fix the code!!
         */
        default_params: {
            'carousel': {
                'slides': [{
                    text: 'Lorem Ipsum',
                    image: ''
                }]
            },
            'extras': {
                'slides': [{
                    text: 'Lorem Ipsum',
                    image: ''
                }]
            }
        },

        removeWidget: function () {
            var url = this.urls['remove_widget'] + this.db_id;
            if (confirm('Would you like to remove this element?')) {
                window.App.request(this.urls['remove_widget'], this.db_id, null, $.proxy(function () {
                    this.element.remove()
                }, this));
            }
        },

        openWidgetPreference: function () {
            var sett = setting.widget_settings[this.name]

            if (sett && sett.setting_html) {
                $('#setting_content').html(sett.setting_html);
                $('#preferenePanelWidgetId').val(this.db_id);
                window.App.open_preference();
            }

        },

        dragElement: function () {

            $(".llelement").sortable({
                handle: ".fa-arrows-alt",
                connectWith: ".llelement",
                cursor: "move",
                cursorAt: {right: 10},
                items: ".widget",
                tolerance: "pointer",
                //revert: true,
                stop: $.proxy(function (e, element) {
                    this.element = $(element.item[0]);

                    //this.element.find(".map-container").gmap3('get');
                    //
                    this.generate_position();
                    this.save_position();
                }, this)
            });
        },

        save_position: function () {
            var widget = [];

            $.each($(".widget"), function () {
                var widget_id = $(this).attr("data-widget-id");
                var position = $(this).attr("data-order");
                var layout_id = $(this).closest(".llelement").attr("data-position");

                widget_temp = {"id": widget_id, "position": position, "layout_id": layout_id};
                widget.push(widget_temp);
            });

            window.App.request(this.urls['update_position'], "", {'data[widget]': widget});
        },

        addSubElement: function () {
            var template = setting.widget_settings[this.name].editor,
                tmpObj,
                foreaches,
                widget_wrapper = this.element.find('.widget_wrapper'),
                selected = this.element.find('[name=slider]:checked');


            if (template) {
                tmpObj = $(template);
                foreaches = tmpObj.find('[data-foreach]');

                max = 0;
                $.each(this.element.find('[data-widget-param-group]'), function (k, el) {
                    var p = $(el).attr('data-widget-param-group');
                    if (p > max) {
                        max = p;
                    }
                });
                max++;

                $.each(foreaches, jQuery.proxy(function (i, foreach) {
                    var obj = $(foreach),
                        key = obj.attr('data-foreach-rel');

                    if (typeof key !== typeof undefined && key !== false) {
                        var clone = $(obj.html());
                        clone.attr('data-widget-param-group', max);

                        set = {'id': 'slider_ID_' + max};
                        this.apply_params(clone, set, '');
                        this.widget_events(clone);

                        $.each(clone.find('[data-widget-param^=DATA-FOREACH-VALUE-]'), $.proxy(function (i, p) {
                            var obj = $(p),
                                attr = obj.attr('data-widget-param'),
                                val = attr.substring(19);

                            $(p).attr('data-widget-param', val);

                            this.apply_params($(p), setting.widget_settings[this.name].default_parameters.values);
                        }, this));
                        this.element.find('[data-foreach-rel=' + key + ']').append(clone);
                    }
                }, this));


                window.App.request(this.urls['add_default_params'], [this.db_id, this.name, max], {}, $.proxy(function () {
                    nextC = selected.next();
                    nextC.prop("checked", true);
                    nextC.attr("checked", 'checked');
                    selected.prop("checked", false);
                    selected.removeAttr('checked');

                    widget_wrapper.attr('data-pos', 'pos' + (parseInt(selected.parent().find('input[type]').length)));
                }, this));
            }
        },

        removeSubElement: function () {
            var selected = this.element.find('[name=slider]:checked'),
                widget_wrapper = this.element.find('.widget_wrapper'),
                group = selected.attr('data-widget-param-group'),
                all = this.element.find('[data-widget-param-group=' + group + ']');


            if (confirm('Would you like to remove this slide?')) {

                window.App.request(this.urls['remove_widget_param_group'], [this.db_id, group], {}, $.proxy(function () {
                    var prev = selected.prev();
                    prev.prop("checked", true);
                    prev.attr("checked", 'checked');
                    widget_wrapper.attr('data-pos', 'pos' + (parseInt(selected.parent().find('input[type]').index(prev) + 1)));
                    all.remove();
                }, this));

            }
        },

        /**
         *
         */
        createToolbar: function () {
            var toolEl = $('<div class="top-right toolEl" style="z-index:99999; "></div>');
            if (setting.widget_settings[this.name].toolbar && setting.widget_settings[this.name].toolbar.button) {
                $.each(setting.widget_settings[this.name].toolbar.button, $.proxy(function (i, tool) {
                    switch (tool) {
                        case 'addSubElement':
                            toolEl.append($('<i class="fa fa-plus" ></i>').on('click', $.proxy(this.addSubElement, this)));
                            break;
                        case 'removeSubElement':
                            toolEl.append($('<i class="fa fa-trash-o"></i>').on('click', $.proxy(this.removeSubElement, this)));
                            break;
                        default:
                            console.error('unsupported')
                    }
                }, this))
            }

            //toolEl.append($('<i class="fa fa-remove" ></i>').on('click', $.proxy(this.removeWidget, this)));
            toolEl.append($('<i class="fa fa-gear"></i>').css({right: '20px'}).on('click', $.proxy(this.openWidgetPreference, this)));
            //toolEl.append($('<i class="fa fa-arrows-alt"></i>').css({right: '20px'}));

            this.element.append(toolEl);
        },

        loadSetting: function (widget) {
            if (setting.widget_settings[widget]){
                var def = setting.widget_settings[widget].default_parameters;
                if (def.type && def.type == 'array') {
                    this.default_params[widget][def.key] = [def.values];
                } else {
                    this.default_params[widget] = def;
                }
            } else {
                console.warn('Exception: Undefined widget: ' + widget);
            }


        },

        init: function (widget, params) {//alert('sdsdsd');$("#testid").val('44');
            if (widget == 'carousel')
            console.log(params);
            if (setting.widget_settings[widget]) {
                this.loadSetting(widget);
                this.element = $(this.html);
                this.name = widget;
                this.element.addClass('widget').css({left: 'auto', position: 'relative', top: 'auto'});

                this.generate_position(); //Generating the position for widgets

                if (widget && setting.widget_settings[widget].editor) {
                    this.element.append($(setting.widget_settings[widget].editor));
                    this.createToolbar();
                }

                if (params && Object.keys(params).length > 0) {
                    this.apply_params(this.element, params);
                } else if (this.default_params[widget]) {
                    this.apply_params(this.element, this.default_params[widget]);
                }

                this.dragElement();
                //console.log(this.name);
                if (this.name == "map") {

                    App.init_gmap("", this.element);
                } else if (this.name == "video") {
                    //console.log(params[1].param)
                    //this.search_inarray(params,"param");
                }

                this.widget_events();
                return true;
            } else {
                console.warn('Exception: Undefined widget: ' + widget);
                return false;
            }
        },

        search_inarray: function (array, search) {
            for (i = 0; i < array.length; i++) {
                arr = array[i];

                if (typeof arr.window[search] != "undefined") {

                }
            }
        },


        generate_position: function () {
            $.each($(".llelement"), function (i) {
                $.each($(".llelement:eq(" + i + ") .widget"), function (i) {
                    $(this).attr("data-order", i);
                });
            });
        },


        apply_params: function (obj, params, prefix) {
            $.each(params, $.proxy(function (k, param) {
                    var variable = param.param || k,
                        value = param.value || param,
                        elms,
                        load_type;

                    if ($.type(prefix) == 'undefined') {
                        prefix = '';
                    }

                    elms = $(obj.find('[data-widget-param=' + prefix + variable + ']'));

                    if (elms.length == 0) {
                        self = obj.attr('data-widget-param');
                        if (typeof self !== typeof undefined && self !== false && self == prefix + variable) {
                            elm = obj;
                        }
                    }

                    $.each(elms, $.proxy(function (i, elm) {
                            var elm = $(elm);
                            elm.attr('data-widget-param', variable);
                            load_type = elm.attr('data-widget-param-load');
                            switch (load_type) {
                                case 'html':
                                    elm.html(value);
                                    break;
                                case 'src':
                                    elm.attr('src', value);
                                    break;
                                case 'attrId':
                                    elm.attr('id', value);
                                    break;
                                case 'attrFor':
                                    elm.attr('for', value);
                                    break;
                                case 'data-option':
                                    elm.attr('data-option', value);
                                    break;
                                case 'video':
                                    elm.attr('src', window.App.video_preview(value, this.element));
                                    break;
                                case 'position':
                                    elm.css($.parseJSON(value));
                                    break;
                                case 'css':
                                    elm.attr('class', value);
                                    break;
                                case 'visibility':
                                    if (value == '1') {
                                        if ($.inArray(elm.prop("tagName"), ['TD', 'TH']) > -1) {
                                            elm.css('display', 'table-cell');
                                        } else {
                                            elm.css('display', 'inherit');
                                        }
                                    }
                                    else
                                        elm.css('display', 'none');
                                    break;
                            }
                        }, this)
                    )
                }, this)
            );

            if (this.name == 'carousel') {
                $(obj.find('input[type=radio]')[0]).prop('checked', true);
                $(obj.find('input[type=radio]')[0]).attr('checked', 'checked');
                $(obj.find('.carousel')[0]).attr('data-pos', 'pos1');
            }


            var foreachElms = obj.find('[data-foreach]');
            if (foreachElms.length > 0) {
                $.each(foreachElms, $.proxy(function (i, foreach) {
                    var foreach = $(foreach),
                        inner = $(foreach.html()),
                        param = foreach.attr('data-foreach');

                    foreach.empty();


                    if (params[param]) {
                        $.each(params[param], $.proxy(function (k, set) {
                            c = inner.clone();
                            c.attr('data-widget-param-group', k);
                            set['id'] = 'slider_ID_' + k;
                            this.apply_params(c, set, 'DATA-FOREACH-VALUE-');
                            foreach.append(c);
                        }, this));
                    }

                }, this));
            }
//
//
//}
        },

        /**
         * set DB id of the widget
         * @param elId
         */
        set_widget_id: function (elId) {
            this.db_id = elId;
            if (this.element !== null) {
                this.element.attr('data-widget-id', elId);
                return true;
            } else {
                return false;
            }
        }
        ,

        widget_events: function (obj) {

            if (!obj) {
                obj = this.element;
            }

            obj.find('[data-button=removeSelf]').on('click', $.proxy(function (e) {
                e.preventDefault();
                var el = $(e.target),
                    get = [this.db_id, 'position'],
                    group = $(el.parents('[data-widget-param-group]')[0]),
                    groupId = group.attr('data-widget-param-group'),
                    all = this.element.find('[data-widget-param-group=' + groupId + ']');


                if (confirm('Would you like to remove this element?')) {
                    window.App.request(this.urls['remove_widget_param_group'], [this.db_id, groupId], {}, $.proxy(function () {
                        all.remove();
                    }, this));

                }

            }, this));

            obj.find('[data-draggable=true]').draggable({
                cancel: "div[contentEditable]",
                stop: $.proxy(function (e, ui) {
                    var el = $(e.target),
                        get = [this.db_id, 'position'],
                        group = $(el.parents('[data-widget-param-group]')[0]);

                    if (group && group.length != 0) {
                        get.push(group.attr('data-widget-param-group'));
                    }
                    window.App.request(this.urls['update_param'], get, {'data[position]': JSON.stringify(ui.position)});
                }, this)
            });

            obj.find('[contentEditable=true]').on('blur', $.proxy(function (event) {
                var el = $(event.target),
                    param = el.attr('data-widget-param'),
                    group = $(el.parents('[data-widget-param-group]')[0]),
                    get = [this.db_id, param],
                    t = {};

                t['data[' + param + ']'] = el.html();
                if(module_controller == "newsletter_campaigns") {
                    NewsletterCampaignId = $("#NewsletterCampaignId").val();
                    t["data[newsletter_id]"] =  NewsletterCampaignId;
                }

                if (group && group.length != 0) {
                    get.push(group.attr('data-widget-param-group'));
                }

                if (window.openedToolbar == false) {
                    window.App.request(this.urls['update_param'], get, t);
                    $('.wisiwyg_tool').addClass('none');
                }
            }, this)).on('focus', function (event) {
                $('.wisiwyg_tool').removeClass('none');
                window.selectedEditor = $(this);
            })


            var images = obj.find('.image_upload');
            if (obj.hasClass('image_upload')) {
                images = $.merge(obj, images);
            }

            images.on('dragover', function (event) {
                event.stopPropagation();
                event.preventDefault();
            }).on('drop', function (event, ui) {
                event.stopPropagation();
                event.preventDefault();

                var obj = $(this),
                    files = event.originalEvent.dataTransfer.files,
                    file = files[0];

                if (file.type.indexOf("image") == 0) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        var img = $(obj.find('img')[0]);


                        img.attr('src', e.target.result);


                        imgData = imageToDataUri(img[0], 1024, 450);
                        //imgData = e.target;
                        var
                            parent = $(obj.parents('.widget')[0]),
                            widgetId = parent.attr('data-widget-id'),
                            param = img.attr('data-widget-param'),
                            NewsletterCampaignId = $("#NewsletterCampaignId").val(),
                            url = '/'+module_controller+'/setWidgetParamEditor/null/' + widgetId + '/' + param,
                            t = {},
                            group = obj.attr('data-widget-param-group');


                        if (typeof group !== typeof undefined && group !== false) {
                            url += '/' + group;
                        }

                        t['data[' + param + ']'] = imgData;
                        t["data[newsletter_id]"] =  NewsletterCampaignId;


                        $.ajax({
                            dataType: "json",
                            url: url,
                            type: "POST",
                            data: t
                        }).done(function (json) {
                            if (json) {
                                if (json.result === true) {

                                }
                            }
                        });

                        obj.removeClass('padding-10');


                    }
                    reader.readAsDataURL(file);
                }
            })


        }
    })
    ;


var layoutClass = Class.create({
    name: 'LayoutClass',
    widget_list: {},
    /**
     * the list of all widgets in the current layout
     */
    widgets: [],

    init: function () {

    },
    changeLayout: function () {
    },
    addWidget: function () {
    },

    apply_layout: function (rel) {
        var lay = $('#layout');
        if (!rel){
            rel = 1;
        }
        if (lay) {
            lay.empty().append(layouts[rel]);
        }


        $('.logo_upload_2').on('dragover', function (event) {
            event.stopPropagation();
            event.preventDefault();
        }).on('drop', function (event, ui) {
            event.stopPropagation();
            event.preventDefault();

            var obj = $(this),
                files = event.originalEvent.dataTransfer.files,
                file = files[0];


            if (file.type.indexOf("image") == 0) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var img = $(obj.find('img'));
                    img.attr('src', e.target.result);
                    // obj.html('<img height="43" src="' + e.target.result + '" />');
                    // obj.removeClass('padding-10');

                    var param = img.attr('data-system-setting-param'),
                        ClientId = $('#ClientId').val(),
                        url = '/'+module_controller+'/clientSettingEditor/' + ClientId + '/' + param + '/',
                        post = {data: {}};


                    post.data[param] = e.target.result;

                    $.ajax({
                        dataType: "json",
                        url: url,
                        type: "POST",
                        data: post
                    }).done(function (json) {
                        if (json) {
                            if (json.result === true) {

                            }
                        }
                    });

                }
                reader.readAsDataURL(file);
            }

        })

        $('.llelement').droppable({
            accept: '.hint-text',
            drop: function (event, ui) {
                var el = $(this),
                    clone = $(ui.helper[0]).clone(),
                    rel = clone.attr('rel'),
                    params = null;


                clone.remove();
                widget = new WidgetClass(rel, {});

                el.append(widget.element);

                var position = el.attr('data-position'),
                    type = rel,
                    ClientId = $('#ClientId').val(),
                    ClientPageId = $('#ClientPageId').val(),

                    ClientVersionPageId = $('#ClientVersionPageId').val(),
                    url = '/'+module_controller+'/addElementLayoutEditor/' + ClientId + '/' + ClientPageId + '/' + ClientVersionPageId + '/' + position + '/' + type;



                $.ajax({
                    dataType: "json",
                    url: url
                }).done(function (json) {
                    if (json) {
                        if (json.result === true) {
                            widget.set_widget_id(json.data);
                            tmp = {};
                            tmp[json.data] = widget;
                            this.widget_list = $.extend(this.widget_list, tmp);
                            delete tmp;

                            widget.generate_position(); //Generating the position for widgets
                            widget.save_position(); //Generating the position for widgets
                        }
                    }
                });
            }

        });
    },

    loadLayout: function (e,version) {
        if ($.type(version) == 'undefined'){
            $('#ClientVersionPageId').empty();
        }
        var ClientId = $('#ClientId').val(),
            SelectVersion = $('#ClientVersionPageId'),
            ClientPageId = $('#ClientPageId').val(),
            VersionId = SelectVersion.val(),
            url;

        if($.type (VersionId) == 'null'){
            VersionId = '';
        }


        console.log($('#ClientPageId').val());
        url = '/' + module_controller + '/loadLayoutEditor/' + ClientId + '/' + ClientPageId + '/';



        if($.type(version) !== 'undefined'){
            url += VersionId;
        }



        $.ajax({
            dataType: "json",
            url: url
        }).done($.proxy(function (json) {
            if (json) {
                if (json.result === true) {
                    this.apply_layout(json.data.layout_type);
                    if (json.data.widgets) {
                        console.log(json.data);
                        $.each(json.data.widgets, $.proxy(function (i, w) {

                                var widget = w.ClientMenuLayoutElementModel.widget,
                                    position = w.ClientMenuLayoutElementModel.position,
                                    layout = $('#layout'),
                                    positionEl = null,
                                    widgetEl = null,
                                    elId = w.ClientMenuLayoutElementModel.id,
                                    params = w.ClientWidgetParamModel;


                            widget = new WidgetClass(widget, params);
                            widget.set_widget_id(elId);

                            tmp = {};
                            tmp[elId] = widget;
                            this.widget_list = $.extend(this.widget_list, tmp);
                            delete tmp;


                            layoutPosiiton = $(layout.find('.llelement[data-position=' + position + ']'))
                            layoutPosiiton.append(widget.element);
                        }, this));
                    }

                    if (json.data.setting) {
                        $.each(json.data.setting, function (key, value) {
                            var settingEl = $($('#layout').find('[data-system-setting-param=' + key + ']')[0]),
                                typeUpdate;

                            if (settingEl) {
                                typeUpdate = settingEl.attr('data-system-setting-param-load');

                                if (typeUpdate === 'src') {
                                    settingEl.attr('src', value);
                                } else if (typeUpdate === 'size_position') {
                                    settingEl.css($.parseJSON(value));
                                }
                            }

                            if (key == 'primary_color'){
                                window.updatePrimaryColor(value);
                                $('.primary_color.colorpicker-element').val(value);
                            }
                            if (key == 'secondary_color'){
                                window.updateSecondaryColor(value);
                                $('.secondary_color.colorpicker-element').val(value);
                            }
                        });
                    }

                    if (json.data.versions && $.type(version) == 'undefined') {
                        $.each(json.data.versions, function(id, name){
                            SelectVersion.append($('<option value="' + id + '" >' + name +'</option>'));
                        })
                    }

                    $.each($('#layout').find('[data-resizable=true]'), function (i, r) {
                        $(r).resizable({
                            stop: $.proxy(function (e, ui) {
                                var el = $(e.target),
                                    ClientId = $('#ClientId').val(),
                                    param = el.attr('data-system-setting-param'),
                                    get = [ClientId, param],
                                    obj = {};

                                obj['data[' + param + ']'] = JSON.stringify({
                                    width: ui.size.width,
                                    height: ui.size.height,
                                    top: el.css('top') + 'px',
                                    left: el.css('left') + 'px'
                                });

                                window.App.request('/'+module_controller+'/client_setting/', get, obj);
                            }, this)
                        }).draggable({
                            containment: 'parent',
                            cancel: "div[contentEditable]",
                            stop: $.proxy(function (e, ui) {
                                var el = $(e.target),
                                    ClientId = $('#ClientId').val(),
                                    param = el.attr('data-system-setting-param'),
                                    get = [ClientId, param],
                                    obj = {};

                                obj['data[' + param + ']'] = JSON.stringify({
                                    width: el.css('width'),
                                    height: el.css('height'),
                                    top: ui.position.top + 'px',
                                    left: ui.position.left + 'px'
                                });


                                window.App.request('/'+module_controller+'/client_setting/', get, obj);
                            }, this)
                        })
                    });
                }
            }
        }, this));
    }
});

var pages = {
    name: 'pages',
    layout: null,

    'clientDesign': function () {
        console.log('clientDesign');
        var layout = new layoutClass();
        layout.loadLayout();
        this.layout = layout;


        $($('#tabThemes').parents('.modal-dialog')[0]).css({width: '90%'});

        $('.layout_option').on('click', function (e) {
            var el = $(this),
                LayoutType = el.attr('rel'),
                ClientId = $('#ClientId').val(),
                ClientPageId = $('#ClientPageId').val(),
                ClientVersionPageId = $('#ClientVersionPageId').val(),
                url = '/'+module_controller+'/update_layout/' + ClientId + '/' + ClientPageId + '/' + ClientVersionPageId + '/' + LayoutType;


            $.ajax({
                url: url
            }).done(function () {
                layout.loadLayout(LayoutType);
            });

        });

        $('#ClientPageId').on('change', $.proxy(layout.loadLayout, layout));
        $('#ClientVersionPageId').on('change', $.proxy(
                function(e){
                    layout.loadLayout(e,true);
                })
        );

        var elements = $('.copy_drag > div');
        $('.copy_drag').disableSelection();

        elements.draggable({
            zIndex: 9999999,
            helper: "clone",
            appendTo: "body",
            cursor: "move",
            revert: "invalid",
            cursorAt: {right: 10},
            stop: function (event, ui) {
            },
            start: function (event, ui) {
            },
            drag: function (event, ui) {
            },
            create: function () {
            }

        });

        window.App.init_events();
    }
}

var Browser = {
    ie: false,
    chrome: true,
    firefox: false,
    safari: false
}

var Selection = {

    insertHTML:function (html) {
        var selRange = this.getRng();
        //alert(Browser.Engine.gecko);
        if (Browser.firefox || Browser.chrome) {
            var sel = this.getSel();
            selRange.setStart(sel.anchorNode, sel.anchorOffset);
            selRange.setEnd(sel.focusNode, sel.focusOffset);
            selRange.deleteContents();

            var selecthelper = document.createTextNode('');
            selRange.insertNode(selecthelper);
            var div = new Element('div');
            div.innerHTML = html;
            for (var count = div.childNodes.length - 1; count >= 0; count--) {
                selRange.insertNode(div.childNodes[count]);
            }
            selRange.setEndAfter(selecthelper);
            selRange.collapse(false);
        } else {
            sel = document.selection;
            if (sel != null) {
                var rng = sel.createRange();
                if (rng != null)
                    rng.pasteHTML(html)
            }
        }
    },

    getFocusElement:function () {
        var rng, elm, sel;
        if (Browser.ie) {
            rng = this.getRng();
            elm = rng.item ? rng.item(0) : rng.parentElement();
        } else {
            sel = this.getSel();
            rng = this.getRng();
            if (!sel || !rng) return null;
            elm = rng.commonAncestorContainer;
            if (!rng.collapsed)
                if (rng.startContainer == rng.endContainer)
                    if (rng.startOffset - rng.endOffset < 2)
                        if (rng.startContainer.hasChildNodes())
                            elm = rng.startContainer.childNodes[rng.startOffset];
                        else
                            elm = elm.parentNode;
        }
        return elm;
    },

    getSel:function () {
        if (Browser.ie) {
            return document.selection;
        }
        return window.getSelection();
    },

    getRng:function () {
        var s = this.getSel();
        if (s == null) return null;
        if (Browser.ie) return s.createRange();
        if (Browser.chrome && s.rangeCount > 0){
            return s.getRangeAt(0);
            //return s.getRangeAt();
        }
        if (Browser.safari  && !s.getRangeAt) return '' + window.getSelection();
        if (s.rangeCount > 0) return s.getRangeAt(0);
        return null;

    },

    selRgn:function (rng) {
        if (rng.select) rng.select();
        else {
            var s = this.getSel();
            if (s.removeAllRanges && s.addRange) {
                s.removeAllRanges();
                s.addRange(rng);
            }
        }
    }
};

w = {

    link_popup: function () {

        function getAHref(obj) {
            elm = Selection.getFocusElement();
            elm2 = $($(document.body).parents('a')[0]);
            if (elm.nodeName == 'A') {
                elm = elm;
            } else if (elm2 != null && elm2.nodeName == 'A') {
                elm = elm2;
            } else {
                elm = null;
            }
            return elm;
        }

        var selText = Selection.getRng(), box = this.OW, elm = Selection.getFocusElement();
        this.curr_position = this.getCursorPosition();
        console.log(elm);

        $('#tabWys').removeClass('none');
        box = $('#tabWys');
        box.find('input[type=text], select').val('');


        /**
         * Seznam kotev
         */

        if (elm.nodeName == 'A') {
            $(box.find('.link_add_edit_title')).val($(elm).attr('title'));
            $(box.find('.link_add_edit_targetlist')).val($(elm).attr('target'));
            $(box.find('.link_add_edit_href')).val($(elm).attr('href'));
        }


        $(box.find('.close_button_strict')).on('click', $.proxy(function () {
            $('#tabWys').addClass('none');
        },this));

        $(box.find('.close_button')).on('click', (function () {
            if ($(box.find('.link_add_edit_href')).val() == '') {
                alert("You have to enter URL");
                $(box.find('.link_add_edit_href')).focus();
                return;
            }

            if ($(box.find('.link_add_edit_title')).val() == '') {
                alert("Link");
                $(box.find('.link_add_edit_title')).focus();
                return;
            }

            this.setCursorPosition(this.curr_position);
            elm = getAHref(this);

            if (elm == null) {
                document.execCommand('createlink', false, '_TEMP_LINK');
                elm = $($(document.body).find('a[href$=_TEMP_LINK]'));
            }

            /* anchor, target, rel, class */
            $(elm).attr({
                href:  $(box.find('.link_add_edit_href')).val(),
                title: $(box.find('.link_add_edit_title')).val()
            });

            if ($(box.find('.link_add_edit_targetlist')).val() != "") $(elm).attr('target', $(box.find('.link_add_edit_targetlist')).val());
            $('#tabWys').addClass('none');
            $(window.selectedEditor).focus();


        }).bind(this))
    },
    setCursorPosition:function (pos) {
        //	alert(Browser.Engine.gecko);
        if (Browser.firefox || Browser.chrome) {
            Selection.selRgn(pos);
        } else {
            this.focus();
            var range = document.selection.createRange();
            range.collapse(true);
            range.moveEnd('character', pos.end);
            range.moveStart('character', pos.start);
            range.select();
        }
    },
    getCursorPosition:function () {
        if (Browser.firefox || Browser.chrome) {
            result = Selection.getRng();
        } else {
            var result = { start:0, end:0 };
            this.focus();
            if (this.selection && this.selection.createRange) {
                var range = this.selection.createRange();
                var range2 = range.duplicate();
                range2.moveToElementText(this);
                var bookmark = range.getBookmark();
                var bookmark2 = range2.getBookmark();
                result.start = (bookmark.charCodeAt(2) - bookmark2.charCodeAt(2));
                result.end = result.start + range.text.length;
            }
        }

        return result;
    }
}

$('.btnTool').on('mousedown', function (e) {
    var o = $(this),
        rel = o.attr('rel');

    console.log(rel);
    switch (rel){
        case 'link':
            w.link_popup();
            console.log('sasas');
            break;
        case 'foreColor':

            break;
        case 'image':
            var imgSrc = prompt('Enter image location', '');
            if(imgSrc != null){
                document.execCommand('insertimage', false, imgSrc);
            }
            break;

        case 'insertParagraph':
            document.execCommand('formatblock', '<p>', false);
            break;
        default:
            document.execCommand(rel, null, false);
            break;
    }

    window.openedToolbar = true;

}).on('mouseup', function (e) {
    var o = $(this),
        rel = o.attr('rel');

    if (rel != 'foreColor' && rel != 'link') {
        $(window.selectedEditor).focus();
        window.openedToolbar = false;
    }
}).on('change', function (e) {
    var o = $(this),
        rel = o.attr('rel');

    if (rel == 'foreColor') {
        document.execCommand(rel, false, o.val());
        $(window.selectedEditor).focus();
        window.openedToolbar = false;
    }
})
