Read = Class.create({
    config: {
        "elements": {
            "clickable": ".readLib",
            "readauto": ".readLibAuto"
        },
        "params": {
            "container": "read-container",
            "from": "read-from",
            "where": "read-where",
            "type": "read-type", //It can be text or input
            "hidden": "read-hidden", //If you wan't to copy id from that value
            "hidden_url": "read-hidden-url",
            "append_in": "read-in",
            "tags": "read-add",
            "urls": "read-url",
            "class_input": "read-input-class"
        },
        "data": {
            "hidden": false //Default valu is false, I don't wan't id just simple text
        }
    },

    init: function() {
        this.config.data.clickable = $(this.config.elements.clickable);
        this.config.data.readauto = $(this.config.elements.readauto);

        if(this.config.data.readauto.length >= 1) { //Read automatically
            this.auto();
        }

        this.events.init(this); //Init events
    },

    auto: function() {
        this.config.data.readauto.each($.proxy(function(i,target) {
            this.config.data.clicked = $(target);
                this.getDatas();
                this.fetchDatas();
        }, this));
    },

    /**
     * Get the datas from element, add generate the options
     */
    getDatas: function() {
        this.config.data.container =  $(this.config.data.clicked.data(this.config.params.container));
        this.config.data.from =  this.config.data.clicked.data(this.config.params.from);
        this.config.data.where =  $(this.config.data.clicked.data(this.config.params.where));
        this.config.data.type =  this.config.data.clicked.data(this.config.params.type);
        this.config.data.hidden =  ((typeof this.config.data.clicked.data(this.config.params.hidden) != "undefined") && (this.config.data.clicked.data(this.config.params.hidden) === true)) ? true : this.config.data.hidden;
        this.config.data.hidden_url =  this.config.data.clicked.data(this.config.params.hidden_url);
        this.config.data.append_in =  this.config.data.clicked.data(this.config.params.append_in);
        this.config.data.tags =  this.config.data.clicked.data(this.config.params.tags);
        this.config.data.urls =  this.config.data.clicked.data(this.config.params.urls);
        this.config.data.class_input =  (typeof this.config.data.clicked.data(this.config.params.class_input) != "undefined") ? this.config.data.clicked.data(this.config.params.class_input) : "" ;

        if(this.config.data.append_in.indexOf("|") > -1) {
            this.config.data.append_in = this.config.data.append_in.split("|");
        }

        if(this.config.data.tags.indexOf("|") > -1) {
            this.config.data.tags = this.config.data.tags.split("|");
        }
    },

    /**
     * Search and fetch each data
     */
    fetchDatas: function() {
        this.config.data.container.each($.proxy(function(i,target) {
            this.config.data.value = (this.config.data.type == "input") ? $(target).find(this.config.data.from).val() : $($(target).find(this.config.data.from)).text();

            //Looking for the hidden value
            if(this.config.data.hidden === true) {
                this.config.data.hiddenValue = '<input type="hidden" name="data' + this.config.data.hidden_url + '" value="'+$(target).find("input:hidden").val()+'" />'
            }

            this.html();
        },this))
    },

    html: function() {
        var html = "";
        var explode = "";

        for(var i = 0; i < this.config.data.append_in.length; i++) {
            explode = this.config.data.append_in[i].split(",");

            html += explode[0];
                if(typeof this.elements[this.config.data.tags[i]] !== "undefined") {
                    html += this.elements[this.config.data.tags[i]](this);
                }
            html += explode[1];
        }

        this.config.data.where.append(html);
    },

    elements: {
        input: function(self) {
            var html = "";
                html += '<input type="text" name="data'+self.config.data.urls+'" class="'+self.config.data.class_input+'" />';
            return html;
        },

        value: function(self) {
            var html = "";
                html += self.config.data.value;
                html += self.config.data.hiddenValue;
            return html;
        }
    },

    events: {
        init: function(self) {
            this.self = self;
            this.click();
        },

        click: function() {
            this.self.config.data.clickable.click($.proxy(function(e) {
                this.self.config.data.clicked = e.target;

                this.self.getDatas();
            },this));
        }
    }
});