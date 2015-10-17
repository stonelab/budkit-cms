<tpl:layout xmlns="http://www.w3.org/1999/xhtml" xmlns:tpl="http://budkit.org/tpl">
    <div class="col-md-12">
    <div class="panel">
        <div class="panel-body clearfix row">
            <form class="form-vertical col-md-12 col-sm-12 col-lg-8" method="POST" action="/settings/system/save">
                <div class="panel-group" id="email-template-collapse">
                    <div class="panel panel-default">
                        <div class="panel-heading">

                                <a  class="panel-title" data-toggle="collapse" data-parent="#email-template-collapse" href="#email-verification">
                                Email verification template
                            </a>
                        </div>
                        <div id="email-verification" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <fieldset>
                                    <div class="form-group">
                                        <label class="control-label" for="options[emails][verification-subject]">Email Verification Subject</label>
                                        <div class="controls">
                                            <input type="text" name="options[emails][verification-subject]" class="form-control" tpl:value="${config://email.template.verification-subject}" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="options[emails][verification-body]">Email Verification</label>
                                        <div class="controls">
                                            <textarea name="options[emails][verification-body]" rows="8" class="form-control">
                                                <tpl:data value="config://email.template.verification-body" />
                                            </textarea>
                                            <span class="help-block"><strong>Available variables:</strong> @{link}</span>
                                        </div>
                                    </div>

                                </fieldset>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">Save Template</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <a class="panel-title" data-toggle="collapse" data-parent="#email-template-collapse" href="#notification-email">
                                Notification email template
                            </a>
                        </div>
                        <div id="notification-email" class="panel-collapse collapse">
                            <div class="panel-body">
                                <fieldset>
                                    <div class="form-group">
                                        <label class="control-label" for="options[emails][notification-subject]">Notification Email Subject</label>
                                        <div class="controls">
                                            <input type="text" name="options[emails][notification-subject]" class="form-control" tpl:value="${config://email.template.notification-subject}" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="options[emails][notification-body]">Notification Email Body</label>
                                        <div class="controls">
                                            <textarea name="options[emails][notification-body]" rows="8" class="form-control" >
                                                <tpl:data value="config://email.template.notification-body" />
                                            </textarea>
                                            <span class="help-block"><strong>Available variables:</strong>@{subject}, @{summary}, @{link} </span>
                                        </div>
                                    </div>
                                </fieldset>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">Save Template</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="options_group" value="system-config" />
            </form>
        </div>
    </div>
        </div>
</tpl:layout>


