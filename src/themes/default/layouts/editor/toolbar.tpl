<?xml version="1.0" encoding="utf-8"?>
<tpl:layout xmlns:tpl="http://budkit.org/tpl" extends="navigation">
    <tpl:replace path="//div[@role='navigator']">

        <div class="navbar navbar-default  mvn" role="navigator">
            <form class="navbar-form" action="#">


                <div class="navbar-collapse collapse navbar-inverse-collapse pln prm" role="navbar-collapse">

                    <div class="mbn col-md-3 pln">
                        <input class="form-control input-md mts" name="media_title" tpl:value="${editing.media_title}" type="text" placeholder="Title"/>
                    </div>

                    <tpl:condition on="editor" test="equals" is="page">
                        <div class="mbn col-md-3 mts">
                            <select name="media_template"  class="form-control" selected="editing.media_template">
                                <option value="default">default-page</option>
                                <tpl:loop foreach="templates">
                                    <div><!-- This div is a wierd hack. loop does not allow another tpl:* in its root -->
                                        <tpl:condition on="current" is="yes" test="equals">
                                            <option tpl:value="${name}" selected="selected"><tpl:data value="name" /></option>
                                        </tpl:condition>
                                        <tpl:condition on="current" is="no" test="equals">
                                            <option tpl:value="${name}"><tpl:data value="name" /></option>
                                        </tpl:condition>
                                    </div>
                                </tpl:loop>
                            </select>
                        </div>
                    </tpl:condition>


                    <a class="navbar-btn btn btn-md btn-embossed btn-danger mts mlm navbar-right">Delete</a>
                    <a class="navbar-btn btn btn-md btn-embossed btn-primary mts mlm navbar-right">Publish <span class="entry-word-count">0 words</span></a>
                    <button type="submit" class="navbar-btn btn btn-md btn-embossed btn-success mts mlm navbar-right">Save <tpl:data value="editor" /></button>
                    <a class="navbar-btn btn btn-md btn-secondary btn-embossed mts mlm navbar-right" href="/member/dashboard">Cancel</a>
                    <!-- <button class="navbar-btn btn btn-md btn-embossed btn-default mts mlm navbar-right">Preview</button>
                     <button class="navbar-btn btn btn-md btn-embossed btn-default mts mlm navbar-right">View Source</button> -->
                </div>

            </form>
        </div>

    </tpl:replace>


</tpl:layout>