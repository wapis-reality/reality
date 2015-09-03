//Idiot git

AppendPlus = Class.create({
    init: function(config) {
        this.initalize = true;

        this.element = $(config.element);
        this.what = this.element.data("what"); //class or id example: .div or #div
        this.where = this.element.data("where"); //class or id example: .div or #div
        this.when = this.element.data("when"); //class or id example: .div or #div
        this.option = this.element.data("option"); //sortable, spec or checkbox can be
        this.remove = this.element.data("remove"); //can be true or false
        this.module = this.element.data("module"); //can be true or false
        this.autoload = this.element.data("load");
        this.view = this.element.data("view");
        this.callback = this.element.data("callback");

        this.elements = this.element.data("elements"); //input name
        this.spec_select = (typeof this.element.data("select") == "undefined") ? "" : this.element.data("select"); //select values
        this.whatText = "";
        this.whatValue = "";

        if(typeof this.module == "undefined") { console.log("You have to specify the data-module attribute"); return false; }
        if(typeof this.option == "undefined") { console.log("You have to specify the data-option attribute"); return false; }
        if(typeof this.where == "undefined") { console.log("You have to specify the data-where attribute"); return false; } else { this.where = $(this.where); }
        if(typeof this.when == "undefined") { console.log("You have to specify the data-when attribute"); return false; } else { this.when = $(this.when); }
        if(typeof this.what == "undefined") {  } else { this.what = $(this.what); }
        if(typeof this.remove == "undefined") { this.remove = false  } else { this.remove = true }
        if(typeof this.view == "undefined") { this.view = "default"  } else { this.view = this.view }
        if(typeof this.callback == "undefined") { this.callback = "default"  } else { this.callback = this.callback }
        if((typeof this.autoload == "undefined") || (this.autoload != "auto")) { this.initalize = true  } else { this.initalize = false; }


        this.start();
        this.click();
    },

    start: function() {
        this.options(this.option);
    },

    /**
     * It will decide which function we have to use.
     */
    options: function(option) {

        if((option == "") || (option == "undefined")) { return false; } //Stopped
        if(typeof this[option] !== "undefined") {
            this[option].init(this);
        } else {
            console.log("The function not exists.");
            return false;
        }
    },

    /**
     * It will get the html codes, after this just appending
     * @param data
     */
    getRow: function(data,callback) {
        $.ajax({
            url: "system/add_append_plus",
            type: "POST",
            data: data,
            success: $.proxy(function (response) {
                callback(response);
            }, this)
        })
    },

    /**
     * Add inside the function and when you remove the element, this function automaticly call the function for spec
     */
    removeCallbackSpec: function(target) {},

    /**
     * Add inside the function and when you remove the element, this function automaticly call the function for sortable
     */
    removeCallbackSortable: function(target,callback) {
        if((typeof callback != "undefined") && (typeof window[callback] == "function")) {
            callback(target);
        }

        if(typeof removeCssFixed != "undefined") {
            removeCssFixed(target);
        }
    },

    sortableCallback: function(event, ui) {
        orderCsv(event, ui);
    },

    /**
     * Global click events each element
     */

    click: function() {
        this.when.unbind();

        this.when.click($.proxy(function (e) {

            //If the element is select, we have to get the values
            if(typeof this.what != "undefined") {
                if(this.what.is(":checkbox") != true) { e.preventDefault(); }

                if (this.what.is("select") == true) {
                    this.whatText = this.what.find("option:selected").text();
                    this.whatValue = this.what.find("option:selected").val();
                } else {
                    if(this.what.is(":checkbox") == true) {
                        this.whatText = "";
                        this.whatValue = "";
                    } else {
                        this.whatText = this.what.val();
                        this.whatValue = this.what.val();
                    }
                }
            } else {
                e.preventDefault();
                this.whatText = "";
                this.whatValue = "";
            }

            this.elements = this.element.data("elements"); //input name
            this.spec_select = (typeof this.element.data("select") == "undefined") ? "" : this.element.data("select"); //select values
            option = this.element.data("option"); //sortable, spec or checkbox can be

            this.initalize = false;

            this.options(option);

        }, this));
    },

    /**
     * Sortable section
     */
    sortable: {
        init: function(self) { //Init the sortable function for the element
            this.self = self;
            this.sortable();
            this.remove();

            if(!this.self.initalize) {
                this.html();
            }
        },

        /**
         * Remove element
         */
        remove: function() {
            $(".remove-appended").unbind();
            this.self.element.find(".remove-appended").click($.proxy(function(e) {
                    var remove = false;

                    if (confirm('Are you sure you want to remove this element?')) {
                        remove = true;
                    }

                if(remove) { //If he really want to remove the file
                    $(e.target).parent().parent().remove();
                    this.self.removeCallbackSortable($($(e.target).parent()),this.self.callback);
                }
            },this));
        },

        /**
         * Get the html for sortable
         */
        html: function() {
            var data = {"option": "sortable","text": this.self.whatText,"value": this.self.whatValue, "link": this.self.module, "class": this.self.element.data("what")+"_"+this.self.element.data("where"),"remove": this.self.remove};

            this.self.getRow(data,$.proxy(function(response) {
                if(typeof response != "undefined") {
                    response = $.parseJSON(response);
                    this.self.where.append(response.html);
                    this.remove();
                }

                this.sortable();

                var obj = [];
                //I hate angularjs now.
                $(".field-container .append-plus-sortable").each(function() {
                    obj.push({text: $(this).find(".field-select_field-container_text").val(), name: $(this).find(".field-select_field-container_value").val()})
                });

                $(".field-sortable-angular").val(JSON.stringify(obj));
                $(".field-sortable-angular").trigger('input');

            },this));
        },

        /*
         * Do sortable on elements
         */
        sortable: function() {
            this.self.element.sortable({
                cursor: "move",
                forceHelperSize: true,
                forcePlaceholderSize: true,
                items: ".append-plus-sortable",
                stop: $.proxy(function(event, ui ) {
                    this.self.sortableCallback(event, ui);
                },this)
            });
        }
    },

    /**
     * The same design like sortable, but without sortable
     */
    static: {
        init: function(self) { //Init the sortable function for the element
            this.self = self;
            this.remove();

            if(!this.self.initalize) {
                this.html();
            }
        },

        /**
         * Remove element
         */
        remove: function() {
            $(".remove-appended").unbind();
            this.self.element.find(".remove-appended").click($.proxy(function(e) {
                var remove = false;

                if (confirm('Are you sure you want to remove this element?')) {
                    remove = true;
                }

                if(remove) { //If he really want to remove the file
                    $(e.target).parent().parent().remove();
                    this.self.removeCallbackSortable($($(e.target).parent()));
                }
            },this));
        },

        /**
         * Get the html for sortable
         */
        html: function() {
            var data = {"option": "sortable","text": this.self.whatText,"value": this.self.whatValue, "link": this.self.module, "class": this.self.element.data("what")+"_"+this.self.element.data("where"),"remove": this.self.remove};

            this.self.getRow(data,$.proxy(function(response) {
                if(typeof response != "undefined") {
                    response = $.parseJSON(response);
                    this.self.where.append(response.html);
                    this.remove();
                }

            },this));
        }
    },

    /**
     * Spec section
     */
    spec: {

        init: function(self) {
            this.self = self;
            this.remove();
            if(!this.self.initalize) {
                this.html();
            }
        },

        /**
         * Get elements
         */
        html: function() {
            var data = {"option": "spec","text": this.self.whatText,"value": this.self.whatValue, "link": this.self.module, "class": this.self.element.data("what")+"_"+this.self.element.data("where"),"remove": this.self.remove,"elements": this.self.elements, "select_value": this.self.spec_select,"view": this.self.view};

            this.self.getRow(data,$.proxy(function(response) {
                if(typeof response != "undefined") {
                    response = $.parseJSON(response);
                    this.self.where.append(response.html);
                    this.remove();


                    var obj = [];
                    //I hate angularjs now.
                    $(".condition-container .spec-container").each(function() {
                        obj.push({text: $(this).find(".condition-select_condition-container_text").val(), name: $(this).find(".condition-select_condition-container_value").val(), condition: $(this).find("select").val(), value: $(this).find(".condition-select_condition-container_text1").val()})
                    });

                    $(".condition-angular").val(JSON.stringify(obj));
                    $(".condition-angular").trigger('input');
                }
            },this));
        },

        /**
         * Remove the current element
         */
        remove: function() {
            $(".remove-appended").unbind();
            $(".remove-appended").click($.proxy(function(e) {
                var remove = false;

                if (confirm('Are you sure you want to remove this element?')) {
                    remove = true;
                }

                if(remove) { //If he really want to remove the file
                    if (this.self.view == "table") {
                        $(e.target).closest("tr").remove();
                    } else {
                        $(e.target).parent().parent().remove();
                    }

                    this.self.removeCallbackSpec($($(e.target).parent()));
                }
            },this));
        }
    },

    /**
     * Checkbox section
     */
    checkbox: {
        init: function(self) {
            this.self = self;
            this.html();
        },

        /**
         * Get elements
         */
        html: function() {
            if(this.self.what.is(":checked")) {
                this.self.where.css({"display":"block"});
            } else {
                this.self.where.css({"display":"none"});
            }
        },

        remove: function() {}
    }
});

$(document).ready(function() {
    //Init in each element
    $(".append-plus").each(function (i) {
        new AppendPlus({
            "element": this
        });
    });
});

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

    var obj = [];
    //I hate angularjs now.
    $(".field-container .append-plus-sortable").each(function() {
        obj.push({text: $(this).find(".field-select_field-container_text").val(), name: $(this).find(".field-select_field-container_value").val()})
    });

    $(".field-sortable-angular").val(JSON.stringify(obj));
    $(".field-sortable-angular").trigger('input');

}


function funnyBlur() {
    var obj = [];
    //I hate angularjs now.
    $(".condition-container .spec-container ").each(function() {
        obj.push({text: $(this).find(".condition-select_condition-container_text").val(), name: $(this).find(".condition-select_condition-container_value").val(), condition: $(this).find("select").val(), value: $(this).find(".condition-select_condition-container_text1").val()})
    });

    $(".condition-angular").val(JSON.stringify(obj));
    $(".condition-angular").trigger('input');
}
