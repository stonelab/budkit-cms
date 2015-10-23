<?xml version="1.0" encoding="utf-8"?>
<tpl:layout name="editor" xmlns:tpl="http://budkit.org/tpl">
    <html lang="en">
    <head>
        <tpl:import name="head"/>
        <link href="/theme/assets/js/vendor/ghost/ghostdown.css" rel="stylesheet"/>

    </head>
    <body class="editing">
        <div class="container-side minimized sidebar" role="side"><!-- add "minimized" class to minimize-->
            <tpl:import name="sidebar" />
        </div>
        <div class="container-main admin" role="main" style="z-index: 1">
            <form tpl:action="/page/${object_uri}/update" method="post" class="container-block mbn clearfix">
            <div class="container-block no-scroll">
                <div class="container-navigation">
                    <tpl:import name="editor/toolbar"/>
                </div>
                <div class="features container-block no-scroll">
                    <section class="editor container-block no-scroll">
                        <div class="outer container-block no-scroll">
                            <div><tpl:import name="alerts" /></div>
                            <div class="editorwrap">
                                <section class="entry-markdown">
                                    <section class="entry-markdown-content">
                                        <textarea name="media_content" id="entry-markdown" tpl:placeholder="Type ${editor} content here in markdown">
                                            <tpl:data value="editing.media_content" />
                                        </textarea>
                                    </section>
                                    <input type="hidden" name="object_uri" tpl:value="${object_uri}" />
                                    <input type="hidden" name="csrftoken" tpl:value="${csrftoken}" />
                                    <input type="hidden" name="_method" value="PATCH" />
                                </section>
                                <section class="entry-preview active">
                                    <section class="entry-preview-content">
                                        <div class="rendered-markdown"></div>
                                    </section>
                                </section>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            </form>
        </div>

    <script data-main="/theme/assets/js/main">
        //<![CDATA[
        require(['config'], function () {
            require(['jquery', 'ghostdown'], function ($) {
               // $(".editor").ghostDown();
            });
        });
        //]]>
    </script>
    </body>
    </html>
</tpl:layout>