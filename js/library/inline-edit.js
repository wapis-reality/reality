InlineEdit = Class.create({
    config: {
        "container": "",
        "class": ".inline-edit",
        "edit_button_class": "inline-edit-btn",
        "close_button_class": "inline-close-btn",
        "save_button_class": "inline-save-btn",
        "delete_button_class": "inline-delete-btn",
        "input": "inline-input",
        "text": "inline-text",
        "type": "text",
        "remove": null
    },

    init: function(options) {
        if(typeof options != "undefined") { this.config.container = $(options.container).find(".inline-edit"); }

        this.elements =  $(this.config.class);

        if(this.config.container != "") {
            this.elementsTemp = $(options.container).find(this.config.class);
        } else {
            this.elementsTemp = $(this.config.class);
        }

        this.elementsTemp.addClass("inactive");
        //this.elements.addClass("inactive");
        //this.elements.each($.proxy(function(i,element) {
        this.elementsTemp.each($.proxy(function(i,element) {
            if(typeof $(element).data("inited") == "undefined") {
                this.generate(element);
                $(element).attr("data-inited","true");
            }
        },this));

        this.events.init_events(this);
    },

    generate: function(element) {
        this.element = $(element);
        this.type = (typeof this.element.attr("data-type") == "undefined") ? this.config.type : this.element.attr("data-type");
        this.remove = (typeof this.element.attr("data-remove") == "undefined") ? this.config.remove : this.element.attr("data-remove"); // Find the parent element to remove in case we need to delete the item
        this.html();
    },

    html: function() {
        var text = this.element.html(),
            raw_text = strip_tags(text);

        var html = '<div style="float: left;">';
                html += '<span class="'+this.config.text+'">'+text.replace(/(\r\n|\n|\r)/gm,"")+'</span>';
            html += '</div>';


            html += '<div class="type_wrapper" style="float: left;">';
                if(this.type == "textarea") {
                    html += '<span style="float: left;"><textarea class="'+this.config.input+' form-control" value="'+raw_text+'" rows="5" name="test" style="display: none;"></textarea></span>';
                } else {
                    html += '<span style="float: left;"><input type="text" class="'+this.config.input+' input" value="'+text+'" name="test" style="display: none;"/></span>';
                }
                html += '<span class="'+this.config.save_button_class+'" style="display: none; float: left; margin-top: 5px; margin-left: 11px;"><a href="#"><i class="fa fa-check"></i></a></span>';
                html += '<span class="'+this.config.close_button_class+'" style="display: none; float: left; margin-top: 5px; margin-left: 5px;"><a href="#"><i class="fa fa-times"></i></a></span>';

                if(this.type == "textarea") {
                    html += '<span class="'+this.config.edit_button_class+'" style="" title="Edit this item"><a href="#"><i class="fa fa-pencil-square-o"></i></a></span>';
                    if(this.remove !== null){
                        // Only show the remove button if we have the data-remove value
                        html += '<span class="'+this.config.delete_button_class+'" style="" title="Remove this item" data-toggle="tooltip" ><a href="#"><i class="fa fa-times"></i></a></span>';
                    }
                } else {
                    if(this.remove !== null){
                        // Only show the remove button if we have the data-remove value
                        html += '<span class="'+this.config.delete_button_class+'" style="float: right; margin-top: 0px; margin-left: 5px;" title="Remove this item" data-toggle="tooltip" ><a href="#"><i class="fa fa-times"></i></a></span>';
                    }
                    html += '<span class="'+this.config.edit_button_class+'" style="margin-left: 10px;" title="Edit this item"><a href="#"><i class="fa fa-pencil-square-o"></i></a></span>';
                }






                html += '<div style="clear: both;"></div>';
            html += '</div>';
            html += '<div style="clear: both;"></div>';
        this.element.html(html);

    },

    /**
     * Events section
     */
    events: {
        init_events: function(self) {
            this.self = self;

            this.edit_button = $("."+this.self.config.edit_button_class);
            this.save_button = $("."+this.self.config.save_button_class);
            this.close_button = $("."+this.self.config.close_button_class);
            this.delete_button = $("."+this.self.config.delete_button_class);
            this.input = $("."+this.self.config.input);
            this.text = $("."+this.self.config.text).find("span");

            this.edit();
            this.close();
            this.save();
            this.delete_item();
        },


        save: function() {
            this.save_button.click($.proxy(function(e) {
                e.preventDefault();
                var current_element = $(e.target).closest(this.self.elements)
                var field = current_element.attr("data-field");
                var key = current_element.attr("data-key");
                var value = current_element.find(this.input).val();
                var that = this;

                $.ajax({
                    url: "/ajax/update_field",
                    type: "POST",
                    data: {"field": field, "key": key,"value": value},
                    success: function(response) {
                        console.log(response);

                        var obj = jQuery.parseJSON(response);

                        current_element.removeClass("active");
                        current_element.addClass("inactive");

                        current_element.find(that.text).html((obj.data.value).replace(/\n\r?/g, '<br />'));
                        current_element.find(that.save_button).css({"display": "none"});
                        current_element.find(that.close_button).css({"display": "none"});
                        current_element.find(that.input).css({"display": "none"});
                        current_element.find(that.text).css({"display": "inline-block"});
                    }
                });


            },this));
        },

        delete_item: function() {
            this.delete_button.click($.proxy(function(e) {
                e.preventDefault();
                if (confirm('Are you sure you want to delete this item?')) {

                    var current_element = $(e.target).closest(this.self.elements)
                    var field = current_element.attr("data-field");
                    var key = current_element.attr("data-key");
                    var value = current_element.find(this.input).val();
                    var that = this;

                    $.ajax({
                        url: "/ajax/delete_field",
                        type: "POST",
                        data: {"field": field, "key": key, "value": value},
                        success: function(response) {
                            var obj = jQuery.parseJSON(response);
                            if(obj.result){
                                console.log(that.self.remove);
                                $(e.target).closest(that.self.remove).remove();
                            } else {
                                // handle error
                            }
                            console.log(response);
                        }
                    });

                }

            },this));
        },

        edit: function() {
            this.edit_button.click($.proxy(function(e) {
                console.log("asd");
                e.preventDefault();
                var current_element = $(e.target).closest(this.self.elements);
                    console.log(current_element);
                    current_element.removeClass("inactive");
                    current_element.addClass("active");

                    //Remove event
                    $(current_element).find(this.edit_button).css({"display": "none"});
                        console.log(current_element.find(this.input));
                    //Add elements
                    current_element.find(this.input).val(current_element.find(this.text).html().replace(/<br\s?\/?>/g,"\n"));
                    current_element.find(this.save_button).css({"display": "block"});
                    current_element.find(this.close_button).css({"display": "block"});
                    current_element.find(this.close_button).css({"display": "block"});
                    current_element.find(this.input).css({"display": "block"});
                    current_element.find(this.text).css({"display": "none"});
            },this));
        },

        close: function() {
            this.close_button.click($.proxy(function(e) {
                e.preventDefault();
                var current_element = $(e.target).closest(this.self.elements);
                    current_element.removeClass("active");
                    current_element.addClass("inactive");

                current_element.find(this.save_button).css({"display": "none"});
                current_element.find(this.close_button).css({"display": "none"});
                current_element.find(this.input).css({"display": "none"});
                current_element.find(this.text).css({"display": "inline-block"});
            },this));
        }
    },

});

/**
 * Helper function...I dont know where to put it so I'll keep it here for the moment
 * @param input
 * @param allowed
 * @returns {XML|string|*}
 */
function strip_tags(input, allowed) {
    //  discuss at: http://phpjs.org/functions/strip_tags/
    // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // improved by: Luke Godfrey
    // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    //    input by: Pul
    //    input by: Alex
    //    input by: Marc Palau
    //    input by: Brett Zamir (http://brett-zamir.me)
    //    input by: Bobby Drake
    //    input by: Evertjan Garretsen
    // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // bugfixed by: Onno Marsman
    // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // bugfixed by: Eric Nagel
    // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // bugfixed by: Tomasz Wesolowski
    //  revised by: Rafał Kukawski (http://blog.kukawski.pl/)
    //   example 1: strip_tags('<p>Kevin</p> <br /><b>van</b> <i>Zonneveld</i>', '<i><b>');
    //   returns 1: 'Kevin <b>van</b> <i>Zonneveld</i>'
    //   example 2: strip_tags('<p>Kevin <img src="someimage.png" onmouseover="someFunction()">van <i>Zonneveld</i></p>', '<p>');
    //   returns 2: '<p>Kevin van Zonneveld</p>'
    //   example 3: strip_tags("<a href='http://kevin.vanzonneveld.net'>Kevin van Zonneveld</a>", "<a>");
    //   returns 3: "<a href='http://kevin.vanzonneveld.net'>Kevin van Zonneveld</a>"
    //   example 4: strip_tags('1 < 5 5 > 1');
    //   returns 4: '1 < 5 5 > 1'
    //   example 5: strip_tags('1 <br/> 1');
    //   returns 5: '1  1'
    //   example 6: strip_tags('1 <br/> 1', '<br>');
    //   returns 6: '1 <br/> 1'
    //   example 7: strip_tags('1 <br/> 1', '<br><br/>');
    //   returns 7: '1 <br/> 1'

    allowed = (((allowed || '') + '')
        .toLowerCase()
        .match(/<[a-z][a-z0-9]*>/g) || [])
        .join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
    var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
        commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
    return input.replace(commentsAndPhpTags, '')
        .replace(tags, function($0, $1) {
            return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
        });
}

var Edit = new InlineEdit();