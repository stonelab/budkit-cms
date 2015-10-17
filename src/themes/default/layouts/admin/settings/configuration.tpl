<?xml version="1.0" encoding="utf-8"?>
<tpl:layout name="configuration" xmlns:tpl="http://budkit.org/tpl">
    <div class="col-md-12">
        <div class="panel">
        <div class="panel-body clearfix row">
            <form class="form-vertical col-md-12 col-sm-12 col-lg-8" method="POST" action="/admin/settings/save">
                <fieldset class="no-margin">
                    <div class="form-group">
                        <label class="control-label" for="options[site][name]">Website Name</label>
                        <div class="controls">
                            <input type="text" name="options[site][name]"  class="form-control" placeholder="e.g MySocialNetwork" tpl:value="${config://setup.site.name}" />
                            <span class="help-block">A unique catchy name to identify your website. This will show as the default page titles</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="options[site][name]">Home Page URI</label>
                        <div class="controls">
                            <input type="text" name="options[site][homepage]"  class="form-control"  tpl:value="${config://setup.site.homepage}" />
                            <span class="help-block">Enter a unique page URI to use as the default homepage</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="options[site][meta-description]">Website description</label>
                        <div class="controls">
                            <textarea name="options[site][meta-description]" class="wysiwyg form-control" rows="8" ><tpl:data value="config://setup.site.meta-description" />
                            </textarea>
                            <span class="help-block">Describe your community, its interest and purpose. </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="options[site][meta-keywords]">Website Keywords</label>
                        <div class="controls">
                            <textarea name="options[site][meta-keywords]" class="wysiwyg form-control" ><tpl:data value="config://setup.site.meta-keywords" />
                            </textarea>
                            <span class="help-block">Lists as many keywords that may promote your listing in some search engines</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="options[site][registration]">Registration</label>
                        <div class="controls">
                            <label class="checkbox">
                                <input type="hidden" name="options[site][registration]" value="0" />
                                <tpl:input type="checkbox" name="options[site][registration]" value="1" data="config://setup.site.registration" />
                                <span>Allow new user registration?</span>
                            </label>
                            <label class="checkbox">
                                <input type="hidden" name="options[site][inviteonly]" value="0" />
                                <tpl:input type="checkbox" name="options[site][inviteonly]"  value="1" data="config://setup.site.inviteonly" />
                                <span>New user registration by invite only.</span>
                            </label>
                            <label class="checkbox">
                                <input type="hidden" name="options[site][verify-email]" value="0" />
                                <tpl:input type="checkbox" name="options[site][verify-email]" value="1" data="config://setup.site.verify-email" />
                                <span>Verify newly registered user by email</span>
                            </label>
                        </div>
                    </div>


                    <hr />
                    <div class="form-group">
                        <label class="control-label" for="options[site][page-title]">Page titles</label>
                        <div class="controls">
                            <tpl:select name="options[site][page-title]" class="form-control" selected="config://setup.site.page-title">
                                <option value="as-is">Leave as is</option>
                                <option value="name-last">Prepend website name</option>
                                <option value="name-first">Append website name</option>
                            </tpl:select>
                            <span class="help-block">By default the page title is the website name.</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="options[site][robots]">Search Robot Instructions</label>
                        <div class="controls">
                            <tpl:select name="options[site][robots]"  class="form-control" selected="config://setup.site.robots">
                                <option value="index-follow">Index, Follow</option>
                                <option value="no-index-follow">No Index, Follow</option>
                                <option value="index-no-follow">Index, No Follow</option>
                                <option value="no-index-no-follow">No Index, No Follow</option>
                            </tpl:select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="options[site][seo]">SEO</label>
                        <div class="controls">
                            <label class="checkbox">
                                <input type="hidden" name="options[site][url-suffix]" value="0" />
                                <tpl:input type="checkbox" name="options[site][url-suffix]" data="config://setup.site.url-suffix" value="1" />
                                <span>Add output format suffix to URLs?</span>
                            </label>
                            <label class="checkbox">
                                <input type="hidden" name="options[site][unicode-aliases]" value="0" />
                                <tpl:input type="checkbox" name="options[site][unicode-aliases]" data="config://setup.site.unicode-aliases" value="1" />
                                <span>Use Unicode Aliasis.</span>
                            </label>
                        </div>
                    </div>
                </fieldset>
                <input type="hidden" name="options_namespace" value="setup" />
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-embossed">Save Preferences</button>
                </div>
            </form>
        </div>
    </div>
    </div>
</tpl:layout>


