<?xml version="1.0" encoding="utf-8"?>
<tpl:layout name="database" xmlns:tpl="http://budkit.org/tpl">
    <form action="/admin/setup/install/4" method="post" class="mbn">
        <div class="panel-heading">
            <h4 class="panel-title"> 3. Database Connection Details </h4>
        </div>
        <div class="panel-body">
            <tpl:import name="alerts" />
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="control-label"><?php echo _('Database Host') ; ?></label>


                    <input type="text" name="dbhost" id="dbhost" class="form-control" value="localhost"/>
                    <span class="help-block">The path to the server. It will most likely be localhost</span>

                </div>
                <div class="form-group col-md-3">
                    <label class="control-label"><?php echo _('Database Port') ; ?></label>

                    <input type="text" name="dbport" id="dbport" class="form-control" placeholder="3306"/>
                    <span class="help-block">The database host port number</span>

                </div>
                <div class="form-group col-md-3">
                    <label class="control-label"><?php echo _('Database Name') ; ?></label>

                    <input type="text" name="dbname" id="dbname" class="form-control"/>
                    <span class="help-block">Existing DB name required</span>

                </div>

                </div>
                <div class="row">
                    <div class="form-group  col-md-6">
                        <label class="control-label"><?php echo _('Database Server Username') ; ?></label>


                        <input type="text" name="dbusername" id="dbusername" class="form-control" placeholder="e.g root"/>
                        <span class="help-block">The Username used to access this database</span>

                    </div>
                <div class="form-group col-md-6">
                    <label class="control-label"><?php echo _('Database Server User Password') ; ?></label>


                    <input type="text" name="dbpassword" id="dbpassword" class="form-control"/>
                    <span class="help-block">The Password with which you use to access this database.</span>

                </div>


                </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="control-label"><?php echo _('DB Table Prefix') ; ?></label>


                    <input type="text" name="dbtableprefix" id="dbtableprefix" class="form-control"
                           tpl:value="${randomstring}"/>
                    <span class="help-block">This is a security feautre, and if one is not defined it will be automatically be generated</span>

                </div>

                <div class="form-group col-md-6">
                    <label class="control-label"><?php echo _('Database RDBMS Driver') ; ?></label>


                    <select id="dbdriver" data-toggle="select" class="select select-default" name="dbdriver">
                        <option value="MySQLi" selected="selected"><?php echo _('MySQLi') ; ?></option>
                        <option value="PostgreSQL" disabled="disabled"><?php echo _('PostgreSQL') ; ?></option>
                        <option value="SQLite3" disabled="disabled"><?php echo _('SQLite3') ; ?></option>
                    </select>

                </div>
            </div>
        </div>
        <div class="panel-footer clearfix">
            <a class="btn btn-default pull-left" href="/admin/setup/install/2"><i class="fa fa-arrow-left"></i> Previous: Requirements</a>
            <button type="submit" class="btn btn-primary pull-right">Next: Prepare Database <i class="fa fa-arrow-right"></i></button>
        </div>
    </form>
</tpl:layout>