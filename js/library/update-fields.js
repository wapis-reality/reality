//Idiot git

UpdateFields = Class.create({

    init: function(config) {

//        this.initalize = true;

        this.element = $(config.element);
        this.where = this.element.data("where").split("|"); //class or id example: .div or #div
        this.structure = this.element.data("structure"); //class or id example: .div or #div
        this.ajax_url = this.element.data("ajax-url"); //class or id example: .div or #div
        this.btn = this.element.data("trigger-btn");
        this.id = $(this.element.data("trigger-item")).val();

        this.init_events();

    },

    init_events: function(){

        $(this.btn).click($.proxy(function (e) {

            /**
             * Get the current value of the id
             */
            var id = $(this.element.data("trigger-item")).val(),
                structure = this.structure;

            /**
             * Keeping everything in here for the moment...Sorry for this
             */
            $.ajax({
                url: this.ajax_url + "/" + id,
                type: "GET",
                success: $.proxy(function (response) {

                    var data = jQuery.parseJSON(response);
                    if(data.result === true){

                        var structure = this.structure;

                        /**
                         * Pass the received data so we can update the fields
                         */
                        $.each(data.data, function(i, item) {

                            $("#"+structure[i]).val(item);

                        });
                    } else {
                        // Error? data.result is false
                    }

                }, this)
            })

        }, this));

    }

});

$(document).ready(function() {

    //Init in each element
    $(".update-fields").each(function (i) {
        new UpdateFields({
            "element": this
        });
    });
});

