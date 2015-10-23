<?xml version="1.0" encoding="utf-8"?>
<tpl:layout name="page" extends="index" xmlns:tpl="http://budkit.org/tpl">
    <tpl:remove path="//div[@role='side']"/>
    <tpl:remove path="//div[@role='aside']"/>
    <tpl:replace path="//div[@role='main']">
        <div class="container-main">
            <div class="container-navigation lg">
                <tpl:import name="navigation" />
            </div>
            <div class="container-block">
                <div id="content" class="container page-content">
                    <tpl:condition on="reading.media_title" test="empty" is="false">
                        <div class="row text-center mb">
                            <h1 class="page-header bottom-border">
                                <tpl:data value="reading.media_title" />
                                <tpl:condition on="reading.object_updated_on" test="empty" is="false">
                                     <p class="small help-block">Last updated <tpl:datetime value="reading.object_updated_on" format="diff" /></p>
                                </tpl:condition>
                            </h1>
                        </div>
                    </tpl:condition>
                      <div class="row">
                          <tpl:data value="reading.media_content" markup="true" />
                      </div>
                </div>
                <tpl:import name="foot"/>
            </div>
        </div>
    </tpl:replace>
</tpl:layout>

