<?xml version="1.0" encoding="utf-8"?>
<tpl:layout xmlns:tpl="http://budkit.org/tpl" name="alerts">
        <tpl:condition on="alerts" test="empty" is="false">
            <div class="mtm mbm">
                <tpl:loop foreach="alerts">
                    <p tpl:class="color-${type} mvn"> <tpl:data value="message" /></p>
                </tpl:loop>
            </div>
        </tpl:condition>

</tpl:layout>