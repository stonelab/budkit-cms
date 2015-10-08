<tpl:layout xmlns="http://www.w3.org/1999/xhtml" xmlns:tpl="http://budkit.org/tpl">
    <div class="col-md-12">
    <div class="panel">
        <div class="panel-body">
            <form class="form-vertical col-md-12 col-sm-12 col-lg-8">
                <div class="form-group">

                    <div class="controls">

                        <span class="help-block">It is important that you keep your site healthy.</span>
                        <p class="margin-top-half"><strong>Performing a system check will..</strong></p>
                        <ol class="help-block margin-top-half">
                            <li>Check for new versions of the platform as well as installed extensions</li>
                            <li>Clear the <em>/downloads</em> folder.</li>
                            <li>Check for any database inconsistencies</li>
                        </ol>
                        <a href="/settings/system/maintenance/check" class="btn btn-success margin-top-half" >Check Now</a>
                    </div>
                </div>
                <hr />
                <div class="form-group">

                    <div class="controls">
                        <span class="help-block">Use this if for some reasons you want to clear all sessions on the system...</span>
                        <p class="margin-top-half"><strong>Closing all sessions will</strong></p>
                        <ol class="help-block margin-top-half">
                            <li>Delete all enteries in the sessions table</li>
                            <li>Force all user's to sign in again</li>
                            <li>You will be logged out</li>
                        </ol>
                        <a href="/settings/system/maintenance/purge" class="btn btn-danger margin-top-half" >End All Sessions</a>
                    </div>
                </div>
                <hr />
                <div class="form-group">

                    <div class="controls">
                        <p class="margin-top-quater"><strong>To Manually Reset...</strong></p>
                        <ol class="help-block">
                            <li>Rename the setup.ini file in your config folder to setup.ini.old</li>
                            <li>Delete your database manually if required. BK won't delete it for you</li>
                            <li>Delete the contents of the <em>/users</em> folder.</li>
                        </ol>
                        <p class="margin-top-half"><strong>Are you sure you want to do this?</strong></p>
                        <p class="help-block">If you are then click the button below. There will be no undo button</p>
                        <a href="/settings/system/maintenance/reset" class="btn btn-danger margin-top-half" >Factory Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
        </div>
</tpl:layout>

