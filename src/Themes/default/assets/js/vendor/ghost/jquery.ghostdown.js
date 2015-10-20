(function ($, ShowDown, CodeMirror) {
    "use strict";

    $.widget( "b4m.ghostDown", {
        editor: null,
        markdown: null,
        html: null,
        converter: null,
        options: null,
        _create: function() {
            this.converter = new ShowDown.converter();
            this.editor = CodeMirror.fromTextArea(this.element.find('textarea')[0], {
                mode: 'markdown',
                tabMode: 'indent',
                lineWrapping: true
            });

            this.editor.on("change", $.proxy(function () {
                this._updatePreview();
            }, this));

            $('.entry-markdown header, .entry-preview header', this.element).click(function (e) {
                $('.entry-markdown, .entry-preview', this.element).removeClass('active');
                $(e.target, this.element).closest('section').addClass('active');
            });

            $('.CodeMirror-scroll', this.element).on('scroll', $.proxy(function (e) {
                this._syncScroll(e);
            }, this));

            // Shadow on Markdown if scrolled
            $('.CodeMirror-scroll', this.element).scroll(function(e) {
                if ($(e.target).scrollTop() > 10) {
                    $('.entry-markdown', this.element).addClass('scrolling');
                } else {
                    $('.entry-markdown', this.element).removeClass('scrolling');
                }
            });
            // Shadow on Preview if scrolled
            $('.entry-preview-content', this.element).scroll(function(e) {
                if ($('.entry-preview-content', $(e.target).scrollTop()).scrollTop() > 10) {
                    $('.entry-preview', this.element).addClass('scrolling');
                } else {
                    $('.entry-preview', this.element).removeClass('scrolling');
                }
            });

            this._updatePreview();
        },
        _updatePreview: function() {
            var preview = this.element.find('.rendered-markdown');
            this.markdown = this.editor.getValue();
            this.html = this.converter.makeHtml(this.markdown);
            preview.html(this.html);
            console.log('the options', this.options);
            if (this.options !== null && typeof this.options.imagePostPath !== 'undefined'){
              this._updateImagePlaceholders(preview.innerHTML, this.options);
            }
            this._updateWordCount();
        },
        getHtml: function () {
            return this.html;
        },
        getMarkdown: function () {
            return this.markdown;
        },
        _syncScroll: function (e) {
            // vars
            var $codeViewport = $(e.target),
                $previewViewport = $('.entry-preview-content'),
                $codeContent = $('.CodeMirror-sizer'),
                $previewContent = $('.rendered-markdown'),
                // calc position
                codeHeight = $codeContent.height() - $codeViewport.height(),
                previewHeight = $previewContent.height() - $previewViewport.height(),
                ratio = previewHeight / codeHeight,
                previewPostition = $codeViewport.scrollTop() * ratio;

            // apply new scroll
            $previewViewport.scrollTop(previewPostition);
        },
        _updateWordCount: function() {
            var wordCount = this.element.find('.entry-word-count'),
            editorValue = this.markdown;
            if (editorValue.length) {
                wordCount.html(editorValue.match(/\S+/g).length + ' words');
            }
        },
        _updateImagePlaceholders: function(content, options) {
          var imgPlaceholders = $(document.getElementsByClassName('rendered-markdown')[0]).find('p').filter(function() {
            return (/^(?:\{<(.*?)>\})?!(?:\[([^\n\]]*)\])(?:\(([^\n\]]*)\))?$/gim).test($(this).text());
          });

          console.log('the options', options);
          var postUrl = options.imagePostPath, postHeaders;
          if (options.imagePostHeaders){
            postHeaders = options.imagePostHeaders;
            console.log('post headers', postHeaders);
          } else {
            postHeaders = {};
          }

          var editor = this.editor;

          $(imgPlaceholders).each(function( index ) {
            
            var elemindex = index,
              self = $(this),
              altText = self.text();

            (function(){
              
              self.dropzone({ 
                url: postUrl,
                paramName: 'image',
                headers: postHeaders,
                withCredentials: true,
                method: "post",
                success: function( file, response ){              
                  var holderP = $(file.previewElement).closest("p"),

                    // Update the image path in markdown
                    imgHolderMardown = $(".CodeMirror-code").find('pre').filter(function() {
                        return (/^(?:\{<(.*?)>\})?!(?:\[([^\n\]]*)\])(?:\(([^\n\]]*)\))?$/gim).test(self.text()) && (self.find("span").length === 0);
                    }),

                    // Get markdown
                    editorOrigVal = editor.getValue(),
                    nth = 0,
                    newMarkdown = editorOrigVal.replace(/^(?:\{<(.*?)>\})?!(?:\[([^\n\]]*)\])(:\(([^\n\]]*)\))?$/gim, function (match, i, original){
                      nth++;
                      return (nth === (elemindex+1)) ? (match + "(" + response['photos'][0]['url'] +")") : match;
                    });
                    editor.setValue( newMarkdown );

                  // Set image instead of placeholder
                  console.log('the response', response['photos'][0]['url']);
                  holderP.removeClass("dropzone").html('<img src="'+ response['photos'][0]['url'] +'"/>');
                }
              }).addClass("dropzone");
            }());
          })
        }
    });
}(jQuery, Showdown, CodeMirror));