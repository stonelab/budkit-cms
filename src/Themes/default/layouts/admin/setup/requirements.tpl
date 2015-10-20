<?xml version="1.0" encoding="utf-8"?>
<tpl:layout name="requirements" xmlns:tpl="http://budkit.org/tpl">

    <div class="panel-heading">
        <h4 class="panel-title"> 2. System Requirements </h4>
    </div>
    <div class="panel-body">
        <tpl:import name="alerts" />
        <p class="pbl">Please ensure that your system passes all the test below. Unless of course you know what you are doing :)</p>

        <tpl:loop foreach="requirements">
            <div class="table-responsive pbl">
                <table class="table table-striped mbl">
                    <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="55%"><tpl:data value="title" /></th>
                        <th width="15%">Required</th>
                        <th width="15%">Status</th>
                        <th width="10%">Test</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tpl:loop foreach="tests">
                        <tr>
                            <td scope="row"><i class="fa fa-puzzle-piece"></i></td>
                            <td><tpl:data value="title" /></td>
                            <td><tpl:data value="name" /></td>
                            <td><tpl:data value="current" /></td>
                            <td>
                                <tpl:condition on="test" test="equals" is="Passed">
                                    <strong class="color-green-sea">Passed</strong>
                                </tpl:condition>
                                <tpl:condition on="test" test="equals" is="Failed">
                                    <strong class="color-alizarin">Failed</strong>
                                </tpl:condition>
                            </td>
                        </tr>
                    </tpl:loop>
                    </tbody>
                </table>
            </div>
        </tpl:loop>
    </div>
    <div class="panel-footer clearfix">
        <a class="btn btn-default pull-left" href="/admin/setup/install/1"><i class="fa fa-arrow-left"></i> Previous: License</a>
        <a class="btn btn-primary pull-right" href="/admin/setup/install/3">Next: Database <i class="fa fa-arrow-right"></i></a>
    </div>
</tpl:layout>
