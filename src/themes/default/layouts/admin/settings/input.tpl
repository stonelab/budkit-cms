<tpl:layout xmlns="http://www.w3.org/1999/xhtml" xmlns:tpl="http://budkit.org/tpl">
    <div class="col-md-12">
    <div class="panel">
        <div class="panel-body clearfix row">
            <form class="form-vertical col-md-12 col-sm-12 col-lg-8" method="POST" action="/admin/settings/save">
                <fieldset class="no-margin">
                    <div class="form-group">
                        <label class="control-label" for="options[input][editor]">Content Editor</label>
                        <div class="controls">
                            <select name="options[input][editor]" class="form-control" selected="config://content.input.editor">
                                <option value="none">None</option>
                            </select>
                            <span class="help-block">By default the page title is the website name.</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="options[input][uploads-folder]">Uploads folder</label>
                        <div class="controls">
                            <input type="text" name="options[input][uploads-folder]" class="form-control" placeholder="/data" tpl:value="${config://content.input.uploads-folder}" />
                            <span class="help-block">Relative to install path. Used to store all user content, preferences etc within a username id subdirectories.</span>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="control-label" for="options[legal][copyright-notice]">Content Rights</label>
                        <div class="controls">
                            <textarea name="options[legal][copyright-notice]" class="wysiwyg form-control" ><tpl:data value="config://content.legal.copyright-notice" /></textarea>
                            <span class="help-block">A brief copyright notice displayed at the bottom of your content</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="options[legal][display]">Content Display</label>
                        <div class="controls">
                            <label class="checkbox">
                                <input type="hidden" name="options[legal][display-copyright-notice]" value="0" />
                                <tpl:input type="checkbox" name="options[legal][display-copyright-notice]" value="1" data="config://content.legal.display-copyright-notice"  />
                                <span>Display Content Rights?</span>
                            </label>
                            <label class="checkbox">
                                <input type="hidden" name="options[posts][display-author-meta]" value="0"/>
                                <tpl:input type="checkbox" name="options[posts][display-author-meta]" value="1" data="config://content.posts.display-author-meta" />
                                <span>Show Author meta-tag.</span>
                            </label>
                        </div>
                    </div>
                </fieldset>
                <input type="hidden" name="options_namespace" value="content" />
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Preferences</button>
                </div>
            </form>
        </div>
    </div>
        </div>
</tpl:layout>


