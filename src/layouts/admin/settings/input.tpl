<tpl:layout xmlns="http://www.w3.org/1999/xhtml" xmlns:tpl="http://budkit.org/tpl">
    <div class="col-md-12">
    <div class="panel">
        <div class="panel-body clearfix row">
            <form class="form-vertical col-md-12 col-sm-12 col-lg-8" method="POST" action="/settings/system/save">
                <fieldset class="no-margin">
                    <div class="form-group">
                        <label class="control-label" for="options[content][content-editor]">Content Editor</label>
                        <div class="controls">
                            <select name="options[content][content-editor]" class="form-control" value="config|content.content-editor">
                                <option value="none">None</option>
                                <option value="tinymce">TinyMCE</option>
                                <option value="codemirror">CodeMiror</option>
                            </select>
                            <span class="help-block">By default the page title is the website name.</span>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="control-label" for="options[content][copyright-notice]">Content Rights</label>
                        <div class="controls">
                            <textarea name="options[content][copyright-notice]" class="wysiwyg form-control" >
                                <tpl:data value="config://setup.site.copyright-notice" />
                            </textarea>
                            <span class="help-block">A brief copyright notice displayed at the bottom of your content</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="options[content][display]">Content Display</label>
                        <div class="controls">
                            <label class="checkbox">
                                <input type="hidden" name="options[content][display-copyright-notice]" value="0" />
                                <input type="checkbox" name="options[content][display-copyright-notice]" value="1" data="config|content.display-copyright-notice"  />
                                <span>Display Content Rights?</span>
                            </label>
                            <label class="checkbox">
                                <input type="hidden" name="options[content][display-author-meta]" value="0"/>
                                <input type="checkbox" name="options[content][display-author-meta]" value="1" data="config|content.display-author-meta" />
                                <span>Show Author meta-tag.</span>
                            </label>
                        </div>
                    </div>
                </fieldset>
                <hr />
                <fieldset class="no-margin">
                    <div class="form-group">
                        <label class="control-label" for="options[general][site-users-folder]">Users folder</label>
                        <div class="controls">
                            <input type="text" name="options[general][site-users-folder]" class="form-control" placeholder="/" tpl:value="${config://setup.site.users-folder}" />
                            <span class="help-block">Used to store all user content, preferences etc within a usernameid subdirectories.</span>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="control-label" for="options[content][FTP-root-path]">FTP root folder</label>
                        <div class="controls">
                            <input type="text" name="options[content][FTP-root-path]" class="form-control" tpl:value="${config://setup.server.FTP-root-path}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="options[content][FTP-server-host]">FTP Host</label>
                        <div class="controls">
                            <input type="text" name="options[content][FTP-server-host]" class="form-control" placeholder="e.g http://proxy.mydomain.com" tpl:value="${config://setup.server.FTP-server-host}"  />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="options[content][FTP-server-port]">FTP Port</label>
                        <div class="controls">
                            <input type="text" name="options[content][FTP-server-port]" class="form-control"  tpl:value="${config://setup.server.FTP-server-port}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="options[content][FTP-server-username]">FTP Username</label>
                        <div class="controls">
                            <input type="text" name="options[content][FTP-server-username]" class="form-control"  tpl:value="${config://setup.server.FTP-server-username}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="options[content][FTP-server-password]">FTP Password</label>
                        <div class="controls">
                            <input type="password" name="options[content][FTP-server-password]" class="form-control" tpl:value="${config://setup.server.FTP-server-password}" />
                        </div>
                    </div>
                </fieldset>
                <input type="hidden" name="options_group" value="system-config" />
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Preferences</button>
                </div>
            </form>
        </div>
    </div>
        </div>
</tpl:layout>


