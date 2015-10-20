<?xml version="1.0" encoding="utf-8"?>
<tpl:layout name="user" xmlns:tpl="http://budkit.org/tpl">
    <form action="/admin/setup/install/5" method="post" class="mbn">
        <div class="panel-heading">
            <h4 class="panel-title"> 4. Create Super User </h4>
        </div>
        <div class="panel-body">
            <tpl:import name="alerts" />
            <p class="pbl">A "super user" is the root admin account with which to manage all platform features. :)</p>

            <div class="form-group row">

                <div class="controls  col-md-6">
                    <label class="control-label" for="user_name">First Name<em class="mandatory">*</em></label>

                    <input class="form-control focused" id="first_name" name="user_first_name" type="text"
                           placeholder="First Name"/>
                </div>
            </div>
            <div class="form-group row">

                <div class="controls  col-md-6">
                    <label class="control-label" for="user_name">Last Name<em class="mandatory">*</em></label>

                    <input class="form-control" id="last_name" name="user_last_name" type="text"
                           placeholder="Last Name"/>
                </div>
            </div>
            <div class="form-group row">


                <div class="controls  col-md-6">
                    <label class="control-label" for="user_name_id">Username</label>
                    <input class="form-control" id="user_name_id" name="user_name_id" size="20" type="text"
                           placeholder="username" value=""/>
                    <span class="help-block">Alpha-numeric only (aA-zZ,0-9)</span>
                </div>
            </div>
            <!-- /form-group -->
            <div class="form-group row">


                <div class="controls  col-md-6">
                    <label class="control-label" for="user_email">Email address</label>
                    <div class="input-prepend">
                        <input class="form-control" id="user_email" name="user_email" size="100" type="text"
                               value=""/>
                    </div>
                    <span class="help-block">Its important that this be valid</span>
                </div>
            </div>
            <!-- /form-group -->

            <div class="form-group row">


                <div class="controls  col-md-6">
                    <label class="control-label" for="user_password">New password</label>
                    <input class="form-control" id="user_password" name="user_password" size="30" type="password"/>
                </div>
            </div>
            <!-- /form-group -->
            <div class="form-group row">


                <div class="controls  col-md-6">
                    <label class="control-label" for="user_password_2">Verify new password</label>
                    <input class="form-control" id="user_password_2" name="user_password_2" size="30" type="password"/>
                </div>
            </div>
            <!-- /form-group -->
        </div>
        <div class="panel-footer clearfix">
            <a class="btn btn-default pull-left" href="/admin/setup/install/3"><i class="fa fa-arrow-left"></i> Previous: Database</a>
            <button type="submit" class="btn btn-primary pull-right">Next: Finish <i class="fa fa-arrow-right"></i></button>
        </div>
    </form>

</tpl:layout>
