<tpl:layout xmlns="http://www.w3.org/1999/xhtml" xmlns:tpl="http://budkit.org/tpl">
    <div class="col-md-12">
    <div class="panel">
        <div class="panel-body clearfix row">
            <form class="form-vertical col-md-12 col-sm-12 col-lg-8" method="POST" action="/settings/system/save">
                <fieldset class="no-margin">
                    <div class="form-group">
                        <label class="control-label" for="options[server][outgoing-mail-handler]">Mail Handler</label>
                        <div class="controls">
                            <select name="options[server][outgoing-mail-handler]" class="form-control" value="config|server.outgoing-mail-handler">
                                <option value="mail">PHP Mail</option>
                                <option value="sendmail">Send Mail</option>
                                <option value="smtp">SMTP</option>
                            </select>
                            <span class="help-block">Leave as is, if not sure or ask your host provider.</span>
                        </div>
                    </div>
                    <hr />
                    <tpl:condition data="config|server.outgoing-mail-handler" test="equals" value="smtp">
                        <div class="form-group">
                            <label class="control-label" for="options[server][outgoing-mail-address]">From e-Mail</label>
                            <div class="controls">
                                <input type="text" name="options[server][outgoing-mail-address]" class="form-control" placeholder="e.g info@mydomain.com" tpl:value="${config://setup.server.outgoing-mail-address}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="options[server][outgoing-mail-server]">Mail Server </label>
                            <div class="controls">
                                <input type="text" name="options[server][outgoing-mail-server]" class="form-control" placeholder="e.g http://webmail.mydomain.com" tpl:value="${config://setup.server.outgoing-mail-server}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="options[server][outgoing-mail-server-port]">Mail  Port</label>
                            <div class="controls">
                                <input type="text" name="options[server][outgoing-mail-server-port]"  class="form-control" tpl:value="${config://setup.server.outgoing-mail-server-port}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="options[server][outgoing-mail-server-security]">Mail Security</label>
                            <div class="controls">
                                <select name="options[server][outgoing-mail-server-security]" class="form-control"  value="config|server.outgoing-mail-server-security">
                                    <option value="">None</option>
                                    <option value="ssl">SSL</option>
                                    <option value="tls">TLS</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="options[server][outgoing-mail-server-username]">Mail Username</label>
                            <div class="controls">
                                <input type="text" name="options[server][outgoing-mail-server-username]"  class="form-control" tpl:value="${config://setup.server.outgoing-mail-server-username}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="options[server][outgoing-mail-server-password]"> Mail Password</label>
                            <div class="controls">
                                <input type="password" name="options[server][outgoing-mail-server-password]"  class="form-control" tpl:value="${config://setup.server.outgoing-mail-server-password}" />
                            </div>
                        </div>
                        <hr />
                    </tpl:condition>
                    <div class="form-group">
                        <label class="control-label" for="options[server][proxy-server]">Proxy Server</label>
                        <div class="controls">
                            <input type="text" name="options[server][proxy-server]" class="form-control" placeholder="e.g http://proxy.mydomain.com" tpl:value="${config://setup.server.proxy-server}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="options[server][proxy-server-port]">Proxy Port</label>
                        <div class="controls">
                            <input type="text" name="options[server][proxy-server-port]"  class="form-control" tpl:value="${config://setup.server.proxy-server-port}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="options[server][proxy-server-username]">Proxy Username</label>
                        <div class="controls">
                            <input type="text" name="options[server][proxy-server-username]"  class="form-control" tpl:value="${config://setup.server.proxy-server-username}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="options[server][proxy-server-password]">Proxy Password</label>
                        <div class="controls">
                            <input type="password" name="options[server][proxy-server-password]"  class="form-control" tpl:value="${config://setup.server.proxy-server-password}" />
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="control-label" for="options[server][protocols]">Protocols</label>
                        <div class="controls">
                            <label class="checkbox">
                                <input type="hidden" name="options[server][enable-xmlrpc]" value="0" />
                                <input type="checkbox" name="options[server][enable-xmlrpc]" value="1" data="config|server.enable-xmlrpc" />
                                <span>Enable XML-RPC Protocol?</span>
                            </label>
                            <label class="checkbox">
                                <input type="hidden" name="options[server][enable-restful]" value="0" />
                                <input type="checkbox" name="options[server][enable-restful]" value="1" data="config|server.enable-restful" />
                                <span>Enable RESTful Protocol.</span>
                            </label>
                            <label class="checkbox">
                                <input type="hidden" name="options[server][protocol-auth]" value="0" />
                                <input type="checkbox" name="options[server][protocol-auth]" value="1"  data="config|server.protocol-auth" />
                                <span>Require Authentication to use protocol</span>
                            </label>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="control-label" for="options[server][error-log]">System ErrorLog</label>
                        <div class="controls">
                            <input type="text" name="options[server][error-log]" class="form-control" tpl:value="${config://setup.server.error-log}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="options[server][help-server-address]">Help Server</label>
                        <div class="controls">
                            <input type="text" name="options[server][help-server-address]" class="form-control" placeholder="e.g http://api.helpserver.com" tpl:value="${config://setup.server.help-server-address}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="options[server][error-reporting]">Error Reporting</label>
                        <div class="controls">
                            <label class="checkbox">
                                <input type="hidden" name="options[server][error-console]" value="0" />
                                <input type="checkbox" name="options[server][error-console]" value="1" data="config|server.error-console" />
                                <span>Display debug console</span>
                            </label>
                            <label class="checkbox">
                                <input type="hidden" name="options[server][error-send]" value="0" />
                                <input type="checkbox" name="options[server][error-send]" value="1" data="config|server.error-send" />
                                <span>Send Errors to developers to help improve platform</span>
                            </label>
                        </div>
                    </div>
                </fieldset>
                <input type="hidden" name="options_group" value="system-config" />
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Server Preferences</button>
                </div>
            </form>
        </div>
    </div>
        </div>
</tpl:layout>


