<?xml version="1.0" encoding="utf-8"?>
<tpl:layout name="server"  xmlns:tpl="http://budkit.org/tpl">
    <div class="col-md-12">
    <div class="panel">
        <div class="panel-body clearfix row">
            <form class="form-vertical col-md-12 col-sm-12 col-lg-8" method="POST" action="/admin/settings/save">
                <fieldset class="no-margin">
                    <div class="form-group">
                        <label class="control-label" for="options[mail][outgoing-handler]">Mail Handler</label>
                        <div class="controls">
                            <tpl:select name="options[mail][outgoing-handler]" class="form-control" selected="config://server.mail.outgoing-handler">
                                <option value="mail">PHP Mail</option>
                                <option value="sendmail">Send Mail</option>
                                <option value="smtp">SMTP</option>
                            </tpl:select>
                            <span class="help-block">Leave as is, if not sure or ask your host provider.</span>
                        </div>
                    </div>
                    <hr />
                    <tpl:condition on="config://server.mail.outgoing-handler" test="equals" value="smtp">
                        <div class="form-group">
                            <label class="control-label" for="options[mail][outgoing-address]">From e-Mail</label>
                            <div class="controls">
                                <input type="text" name="options[mail][outgoing-address]" class="form-control" placeholder="e.g info@mydomain.com" tpl:value="${config://server.mail.outgoing-address}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="options[mail][outgoing-server]">Mail Server </label>
                            <div class="controls">
                                <input type="text" name="options[mail][outgoing-server]" class="form-control" placeholder="e.g http://webmail.mydomain.com" tpl:value="${config://server.mail.outgoing-server}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="options[mail][outgoing-server-port]">Mail  Port</label>
                            <div class="controls">
                                <input type="text" name="options[mail][outgoing-server-port]"  class="form-control" tpl:value="${config://server.mail.outgoing-server-port}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="options[mail][outgoing-server-security]">Mail Security</label>
                            <div class="controls">
                                <tpl:select name="options[mail][outgoing-server-security]" class="form-control"  selected="config://server.mail.outgoing-server-security">
                                    <option value="none">None</option>
                                    <option value="ssl">SSL</option>
                                    <option value="tls">TLS</option>
                                </tpl:select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="options[mail][outgoing-server-username]">Mail Username</label>
                            <div class="controls">
                                <input type="text" name="options[mail][outgoing-server-username]"  class="form-control" tpl:value="${config://server.mail.outgoing-server-username}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="options[mail][outgoing-server-password]"> Mail Password</label>
                            <div class="controls">
                                <input type="password" name="options[mail][outgoing-server-password]"  class="form-control" tpl:value="${config://server.mail.outgoing-server-password}" />
                            </div>
                        </div>
                        <hr />
                    </tpl:condition>
                    <div class="form-group">
                        <label class="control-label" for="options[proxy][server]">Proxy Server</label>
                        <div class="controls">
                            <input type="text" name="options[proxy][server]" class="form-control" placeholder="e.g http://proxy.mydomain.com" tpl:value="${config://server.proxy.server}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="options[proxy][server-port]">Proxy Port</label>
                        <div class="controls">
                            <input type="text" name="options[proxy][server-port]"  class="form-control" tpl:value="${config://server.proxy.server-port}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="options[proxy][server-username]">Proxy Username</label>
                        <div class="controls">
                            <input type="text" name="options[proxy][server-username]"  class="form-control" tpl:value="${config://server.proxy.server-username}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="options[proxy][server-password]">Proxy Password</label>
                        <div class="controls">
                            <input type="password" name="options[proxy][server-password]"  class="form-control" tpl:value="${config://server.proxy.server-password}" />
                        </div>
                    </div>
                    <hr />
                    <fieldset class="no-margin">
                       
                        <div class="form-group">
                            <label class="control-label" for="options[ftp][root-path]">FTP root folder</label>
                            <div class="controls">
                                <input type="text" name="options[ftp][root-path]" class="form-control" tpl:value="${config://server.ftp.root-path}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="options[ftp][server-host]">FTP Host</label>
                            <div class="controls">
                                <input type="text" name="options[ftp][server-host]" class="form-control" placeholder="e.g http://proxy.mydomain.com" tpl:value="${config://server.ftp.server-host}"  />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="options[ftp][server-port]">FTP Port</label>
                            <div class="controls">
                                <input type="text" name="options[ftp][server-port]" class="form-control"  tpl:value="${config://server.ftp.server-port}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="options[ftp][server-username]">FTP Username</label>
                            <div class="controls">
                                <input type="text" name="options[ftp][server-username]" class="form-control"  tpl:value="${config://server.ftp.server-username}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="options[ftp][server-password]">FTP Password</label>
                            <div class="controls">
                                <input type="password" name="options[ftp][server-password]" class="form-control" tpl:value="${config://server.ftp.server-password}" />
                            </div>
                        </div>
                    </fieldset>
                    <hr />

                    <div class="form-group">
                        <label class="control-label" for="options[server][protocols]">Protocols</label>
                        <div class="controls">
                            <label class="checkbox">
                                <input type="hidden" name="options[api][enable-xmlrpc]" value="0" />
                                <tpl:input type="checkbox" name="options[api][enable-xmlrpc]" value="1" data="config://server.api.enable-xmlrpc" />
                                <span>Enable XML-RPC Protocol?</span>
                            </label>
                            <label class="checkbox">
                                <input type="hidden" name="options[api][enable-restful]" value="0" />
                                <tpl:input type="checkbox" name="options[api][enable-restful]" value="1" data="config://server.api.enable-restful" />
                                <span>Enable RESTful Protocol.</span>
                            </label>
                            <label class="checkbox">
                                <input type="hidden" name="options[api][protocol-auth]" value="0" />
                                <tpl:input type="checkbox" name="options[api][protocol-auth]" value="1"  data="config://server.api.protocol-auth" />
                                <span>Require Authentication to use protocol</span>
                            </label>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="control-label" for="options[debug][error-log]">System ErrorLog</label>
                        <div class="controls">
                            <input type="text" name="options[debug][error-log]" class="form-control" tpl:value="${config://server.debug.error-log}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="options[debug][help-server-address]">Help Server</label>
                        <div class="controls">
                            <input type="text" name="options[debug][help-server-address]" class="form-control" placeholder="e.g http://api.helpserver.com" tpl:value="${config://server.debug.help-server-address}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="options[debug][error-reporting]">Error Reporting</label>
                        <div class="controls">
                            <label class="checkbox">
                                <input type="hidden" name="options[debug][error-console]" value="0" />
                                <tpl:input type="checkbox" name="options[debug][error-console]" value="1" data="config://server.debug.error-console" />
                                <span>Display debug console</span>
                            </label>
                            <label class="checkbox">
                                <input type="hidden" name="options[debug][error-send]" value="0" />
                                <tpl:input type="checkbox" name="options[debug][error-send]" value="1" data="config://server.debug.error-send" />
                                <span>Send Errors to developers to help improve platform</span>
                            </label>
                        </div>
                    </div>
                </fieldset>
                <input type="hidden" name="options_namespace" value="server" />
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Server Preferences</button>
                </div>
            </form>
        </div>
    </div>
        </div>
</tpl:layout>


