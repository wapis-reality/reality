Comments = Class.create({

    config: {
        "class": ".comment",
        "save_button": ".comment_save",
        "message": ".comment_message"
    },

    /**
     * Construct
     * @param options
     */
    init: function(options) {

        this.element =  $(this.config.class);

        // Set up remaining config
        this.config.module = this.element.data('module');
        this.config.parent_id = this.element.data('parent_id');

        // Init events
        this.events.init_events(this);
    },

    /**
     * Events section
     */
    events: {

        init_events: function(self) {
            this.self = self;

            this.save_button = $(this.self.config.save_button);
            this.message = $(this.self.config.message);

            this.save();
        },

        save: function() {

            this.save_button.click($.proxy(function(e) {

                e.preventDefault();

                this.self.clicked_button = $(e.target);

                //this.save_button.closest('.comment-list-wrapper').html('asd');

                var module = this.self.config.module,
                    message = this.message.val(),
                    url = this.save_button.data("ajax-url"),
                    parent_id = this.self.config.parent_id;

                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        "module": module,
                        "message": message,
                        "parent_id": parent_id
                    },

                    success: $.proxy(function(response) {

                        var obj = jQuery.parseJSON(response);

                        if(obj.result){
                            this.self.clicked_button.closest(".comment").find(".comment-list-wrapper").html(obj.data.html_output);
                            this.self.clicked_button.closest(".comment").find(".comment_message").val("");
                            var Edit = new InlineEdit({"container":".comment-list-wrapper"});
                        } else {
                            // handle error
                        }


                        //$(".comment-list-wrapper").html($(obj.data.output));

                    }, this)
                });


            },this));
        }
    }

});

var comments = new Comments();