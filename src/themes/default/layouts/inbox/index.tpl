<?xml version="1.0" encoding="utf-8"?>
<tpl:layout xmlns:tpl="http://budkit.org/tpl" extends="index">

    <!--Append-->
    <!--This path attribute needs some kind of validation to save against XSS -->
    <tpl:append path="//tpl:block[@name='content']">
        <div class="page-header bottom-border">
            <h1>
                <span>Inbox</span>
                <div class="btn-group pull-right" data-toggle="buttons-checkbox" >
                    <button class="btn btn-default"><i class="fui-list-thumbnailed" /></button>
                </div>
            </h1>
            <!--
            <div class="navbar navbar-tabs navbar-sm">
                <div class="navbar-holder">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="meetings.html">Activity</a></li>
                        <li><a href="discussions.html">Conversations <span class="label-danger label">1</span></a></li>
                        <li><a href="documents.html">Task list <span class="label-danger label">100+</span></a></li>
                    </ul>
                </div>
            </div> -->
        </div>
        <tpl:import name="stream" />
    </tpl:append>

    <!--Replace-->
    <!--
    <tpl:replace path="//div[@role='side']">
        This replaces something;
    </tpl:replace>
    -->

    <!--Remove-->
    <!--
    <tpl:remove path="//div[@role='aside']" />
    -->

    <!--Prepend-->
    <!--
    <tpl:replace path="//div[@role='side']">
        This is prepended to Path
    </tpl:replace>
    -->

</tpl:layout>
