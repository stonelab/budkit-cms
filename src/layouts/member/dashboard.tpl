<?xml version="1.0" encoding="utf-8"?>
<tpl:layout xmlns:tpl="http://budkit.org/tpl" extends="index">
    <tpl:remove path="//div[@role='aside']" />
    <tpl:replace path="//div[@role='main']">
        <div class="container-main admin" role="main">
            <div class="container-navigation">
                <tpl:import name="admin/navigation" />
            </div>
            <div class="container-fluid padding-top">
                <div class="page-header">
                    <h1 class="mvn">
                        <span><tpl:data value="title" /></span>
                        <tpl:condition on="action" test="empty" is="false">
                            <div class="btn-group mlm">
                                <tpl:loop foreach="action">
                                    <a tpl:class="btn btn-sm btn-embossed ${class}" tpl:href="${link}"><tpl:data value="title" /></a>
                                </tpl:loop>
                            </div>
                        </tpl:condition>

                        <tpl:condition on="alerts" test="empty" is="false">

                                <tpl:loop foreach="alerts">
                                    <p tpl:class="color-${type} mvn"> <tpl:data value="message" /></p>
                                </tpl:loop>

                        </tpl:condition>
                    </h1>
                </div>
                <div class="pls prs ptl pbl">
                    <div class="row">
                        <tpl:block position="main" />
                    </div>
                </div>
            </div>
        </div>
    </tpl:replace>
</tpl:layout>
