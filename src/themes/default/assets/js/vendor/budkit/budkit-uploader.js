/* ===================================================
 * budkit-uploader.js v0.0.1
 * http://budkit.org/docs/uploader
 * ===================================================
 * Copyright 2012 The BudKit Team
 *
 * This source file is subject to version 3.01 of the GNU/GPL License
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/gpl.txt  If you did not receive a copy of
 * the GPL License and are unable to obtain it through the web, please
 * send a note to support@stonyhillshq.com so we can mail you a copy immediately.
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================== */

!function($) {
    "use strict"
    var BKUploader = function(field, options) {

        var $uploader       = this,
            $uploadbtnclass    = $(field).attr('data-class');
        this.options = $.extend({}, $.fn.bkuploader.defaults, options);
        this.field = $(field);
        this.form = $(field).closest('form');

        this.selector = $('<a />').addClass('add-on btn '+$uploadbtnclass).text(this.field.attr("data-label") || "Choose File").insertAfter(this.field);
        this.datalist = this.field.attr('data-display');

        //Hide the remove type="file"
        this.field.hide();
        this.multiple = false;

        //Click to upload
        this.selector.on("click", function(event) {
            event.preventDefault();
            $uploader.field.click();
        });

        if (typeof this.field.attr("multiple") !== 'undefined' && this.field.attr("multiple") !== false) {
            this.multiple = true;
        }
        this.field.on('change', $.proxy((this.multiple) ? this.getSelectedFiles : this.getSelectedFile, this));
        $(this.form.find('.upload-start')).on('click', $.proxy(this.beginUpload, this));
    };
    //Uploader Class
    BKUploader.prototype = {
        Constructor: BKUploader,
        getUploadCount: function() {
        },
        getDroppedFiles: function() {
        },
        getUploadedFiles: function() {
        },
        getSelectedFile: function(event) {
            event.preventDefault();
            this.selector.text(this.field.val());
        },
        getSelectedFiles: function(event) {
            event.preventDefault();

            //Disable the upload button
           if(!this.multiple) this.selector.off('click').addClass("disabled");
            //this.field.off('change')
            var files = this.field.prop('files'),
                ul = ( $(this.datalist).has('.nav-files').length )? $(this.datalist).find('.nav-files:last')  : $('<ul />')
            ;


            console.log(this.datalist);
            console.log(ul);

            ul.addClass('nav nav-files');
            if (this.datalist) {
                for (var x = 0; x < files.length; x++) {
                    var pb = $('<div />').addClass('upload-progress'),
                        file = files[x],
                        imageType = /image.*/,
                        img = $('<img />'),
                        imgName = $('<span />').text(file.name),
                        imgPreview = $('<a />');
                    ;
                    if (file.type.match(imageType)) {
                        img = $("<img />");
                        imgPreview.append(img);
                        var reader = new FileReader();
                        reader.onload = (function(aImg) {
                            return function(e) {
                                aImg.attr('src', e.target.result).attr('width', 130);
                            };
                        })(img);
                        reader.readAsDataURL(file);
                    }

                    ul.append($('<li/>').append(imgPreview.append(pb)));
                }
                $(this.datalist).append(ul).addClass('text-left');
            }
            //@todo if autoupload
            //@todo maybe use a data-autoupload attribute?
            if (typeof this.field.attr("autoload") !== 'undefined' && this.field.attr("autoload") !== false) {
                this.beginUpload(event);
            }
        },
        validate: function() {
        },
        beginUpload: function(event) {
            event.preventDefault();


            var uploadurl = $(this.datalist).attr('data-src'),
                progresskey = $(this.datalist).attr('data-progress'),
                bucketlist = $(this.datalist).find('ul'),
                bucket = $(this.datalist),
                selector = this.selector,
                field = this.field,
                filecount = field[0].files.length,
                form = this.form

                ;

            //Can we send the files asynchronously?
            $.each(field[0].files, function(i, file) {
                var data = new FormData(),
                    preview = bucketlist.eq(i).find('a'),
                    indicator = $('<i />').appendTo(preview.find('div.upload-progress'));
                data.append('bkattachment', file);
                data.append(progresskey, 'bkattachment-' + i);

                $(indicator).addClass('icon-refresh icon-spin');
                $.ajax({
                    url: uploadurl + "create/bkattachment.json",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function(response) {
                        var newField = $('<input type="hidden" name="attachment[]" />').val(response.data.object.uri);
                        form.append(newField);
                        $(indicator).removeClass('icon-refresh icon-spin');
                        $(indicator).addClass('icon-ok');
                        $(indicator).closest('.upload-progress').addClass("completed");
                    },
                    complete: function() {

                        //Remove the progress bar, replace with a tick;


                        //The last request is complete
                        if (parseInt(i + 1) == filecount) {
                            //Move the uploaded files outof the bucket;
                            //Clear the val of this.field
                            field.val("");
                            //field.on("change");
                            //Re-enable the upload form
                        }
                    }
                });
            })
        },
        onUpload: function() {
        },
        beforeUpload: function() {
        },
        afterUpload: function() {
        },
        abortUpload: function() {
        }
    };
    //Plugin Defintion
    $.fn.bkuploader = function(option) {
        return this.each(function() {
            var $this = $(this)
                , data = $this.data('bkuploader')
                , options = typeof option == 'object' && option;
            if (!data)
                $this.data('bkuploader', (data = new BKUploader(this, options)));
        });
    };
    $.fn.bkuploader.defaults = {};
    $.fn.bkuploader.Constructor = BKUploader;

}(window.jQuery);
