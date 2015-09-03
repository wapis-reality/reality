
file_uploader = function(json) {
    this.constructor.files = [];
    this.constructor.finished = 0;

    this.config = {
        className: ".file-upload-container",
        element: "",
        file: "",
        progressBar: ".progress",
        browse: "#file_uploader",
        files: [],
        finished: 0,
        inline: "default"

    }

    this.init();
}

file_uploader.prototype.init = function() {

    this.progressIndex = 0;
    this.element = jQuery(this.config.className);
    this.dropableElement = this.element.find(".file-uploader");
    this.table = this.element.find('table > tbody');
    this.progressBar = ".progress";
    this.inline = (typeof this.element.attr("data-inline") == "undefined") ? this.config.inline : "view";
//        this.progressBar = this.element.find(".progress");

    this.append_events();
}

/**
* Creating the events for clicking and dragging
*/
file_uploader.prototype.append_events = function() {
    this.dropableElement.on('dragenter', $.proxy(function (e) {
        e.stopPropagation();
        e.preventDefault();
        this.dropableElement.css({"background": "#E8F7FF"},500);
    },this));

    this.dropableElement.on('dragover', $.proxy(function (e) {
        e.stopPropagation();
        e.preventDefault();
        this.dropableElement.css({"background": "#E8F7FF"},500);
    },this));

    this.dropableElement.on('drop', $.proxy(function (e) {
        e.preventDefault();
        this.config.file = e.originalEvent.dataTransfer.files;
        this.dropableElement.css({"background": "#FFF"},500);
        this.start();
    },this));

    $(document).on('dragenter', $.proxy(function (e) {
        e.stopPropagation();
        e.preventDefault();
        this.dropableElement.css({"background": "#FFF"},500);
    },this));

    $(document).on('dragover', $.proxy(function (e) {
        e.stopPropagation();
        e.preventDefault();
        this.dropableElement.css({"background": "#FFF"},500);
    },this));

    //If the user clicked to the browse file
    //$("body").delegate(".browse","click", $.proxy(function() {
    $(".browse").click($.proxy(function(e) {
        e.preventDefault();
        $(this.config.browse).click();
    },this));

    //If the user changed the file
    jQuery(this.config.browse).change($.proxy(function() {
        this.config.file = jQuery(this.config.browse).prop('files');
        this.start();
    },this));

    //Remove one file
    //$("body").delegate(".remove-file","click", $.proxy(function(e) {
    $(".remove-file").click($.proxy(function(e) {
        e.preventDefault();
        var remove = false;

        if (confirm('Are you sure you want to remove this file?')) {
            remove = true;
        }

        if(remove) { //If he really want to remove the file
            var id = ($(e.target).prop("tagName") != "A") ? $(e.target).parent().attr("href").split("/") : $(e.target).attr("href").split("/");
            id = id[id.length - 1];

            this.ajaxCall("/ajax/remove_file", {"id": id}, function (response) {
                response = $.parseJSON(response);

                if (response.result === true) {
                    $(e.target).closest("tr").remove();
                } else {
                    alert(response.data.error);
                }
            });
        }

    },this));



}

/**
* Creating the formdate in for, if you have more images.
*/
file_uploader.prototype.start = function() {
    for(i = 0; i < this.config.file.length; i++) {
        var formData = new  FormData();
        formData.append('file',this.config.file[i]);

        this.constructor.files.push({"formData": formData, "name":this.config.file[i].name, "size": this.config.file[i].size});
        this.loadView(formData);
    }
    this.element.append("<input type='hidden' name='data[employees][fileuploader1]' value='12'/>");
    console.log(this.constructor.files);
}

file_uploader.prototype.isThereFileForUpload = function(){
    return (this.constructor.files.length > 0);
}

file_uploader.prototype.submitted = function(id,callback) {
    for(var i = 0; i < this.constructor.files.length; i++) {
        this.createStatusBar();

        this.set_size(this.constructor.files[i].name, this.constructor.files[i].size);

        this.upload(this.constructor.files[i].formData, id,callback);
    }
}

/**
* Creating dinamicly the status file for each file
*/
file_uploader.prototype.createStatusBar = function() {
    $(this.progressBar+":eq("+this.progressIndex+")").after($(this.progressBar).clone());
    this.progressIndex++;
}

/**
* Call ajax, you can pass the url,data and callback
*/
file_uploader.prototype.ajaxCall = function(url,data,callback) {
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success:function(response) {
            callback(response);
        }
    });
}

/**
* Set size, in this case you can write the uploaded size into the browser
* @param name
* @param size
*/
file_uploader.prototype.set_size = function(name,size) {
    sizeStr="";
    sizeKB = size/1024;
    if(parseInt(sizeKB) > 1024) {
        sizeMB = sizeKB/1024;
        sizeStr = sizeMB.toFixed(2)+" MB";
    } else {
        sizeStr = sizeKB.toFixed(2)+" KB";
    }

    this.filename = name;
    this.size = sizeStr;
}

/**
* Progress bar
* @param percent
*/
file_uploader.prototype.progress_bar = function(percent) {
    $(this.progressBar+":eq("+this.progressIndex+")").fadeIn(100);
    $(this.progressBar+":eq("+this.progressIndex+")").find(".progress-bar").css({ width: percent+"%"});
    $(this.progressBar+":eq("+this.progressIndex+")").find(".progress-bar").html(percent + "% ");

    $(this.progressBar+":eq("+this.progressIndex+")").find(".progress-bar").bind("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd", $.proxy(function(){
        if(parseInt(percent) >= 100) {
            $(this.progressBar+":eq("+this.progressIndex+")").fadeOut(300, $.proxy(function() {
                $(this.progressBar+":eq("+this.progressIndex+")").find(".progress-bar").css({ width: "0%"});
            },this));
        }
    },this));
}

/**
* Uploading the file
* @param formdata
*/
file_uploader.prototype.upload = function(formdata, id,callback) {

    var jqXHR = $.ajax({
        xhr: $.proxy(function () {
            var xhrobj = $.ajaxSettings.xhr();

            if (xhrobj.upload) {
                xhrobj.upload.addEventListener('progress', $.proxy(function (event) {
                    var percent = 0;
                    var position = event.loaded || event.position;
                    var total = event.total;
                    if (event.lengthComputable) {
                        percent = Math.ceil(position / total * 100);
                    }
                    this.progress_bar(percent);

                },this), false);
            }
            return xhrobj;
        },this),

        url: "/ajax/file_upload/"+id+"/"+$(".file-upload-module-name").val(),
        type: "POST",
        contentType: false,
        processData: false,
        cache: false,
        data: formdata,
        success: $.proxy(function (response) {
            this.progress_bar(100);
            response = $.parseJSON(response);

            if(response.result === true) {
                console.log(response.data);
                this.table.append(response.data.htmlRow);
            } else {
                alert("No answer");
            }

            this.config.finished++;

            if (this.config.finished == this.constructor.files.length) {
                if (callback) {
                    callback(true);
                }
            }
        },this)
    });
}

file_uploader.prototype.loadView = function(formdata) {
    index = this.constructor.files.length-1;

    var jqXHR = $.ajax({
        xhr: $.proxy(function () {
            var xhrobj = $.ajaxSettings.xhr();

            if (xhrobj.upload) {
            }

            return xhrobj;
        },this),

        url: "/ajax/get_view/"+index,
        type: "POST",
        contentType: false,
        processData: false,
        cache: false,
        data: formdata,
        success: $.proxy(function (response) {
            response = $.parseJSON(response);

            if(response.result === true) {
                this.table.append(response.data.htmlRow);
            } else {
                alert("No answer");
            }

            $(".remove-view").unbind();//Remove event

            $(".remove-view").click($.proxy(function(e) {
                e.preventDefault();
                var id = ($(e.target).prop("tagName") != "A") ? $(e.target).parent().attr("href").split("/") : $(e.target).attr("href").split("/");
                id = id[id.length - 1];

                delete this.constructor.files[id];
                this.constructor.files = this.reindex_array_keys(this.constructor.files);

                $(e.target).closest("tr").remove();
            },this));
        },this)
    });
}

file_uploader.prototype.reindex_array_keys = function(array, start){
    var temp = [];
    start = typeof start == 'undefined' ? 0 : start;
    start = typeof start != 'number' ? 0 : start;
    for(i in array){
        temp[start++] = array[i];
    }
    return temp;
}


$(document).ready(function() {
    fileUploader = new file_uploader();
})