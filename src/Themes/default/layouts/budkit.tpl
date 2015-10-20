<?xml version="1.0" encoding="utf-8"?>
<tpl:layout extends="index" xmlns:tpl="http://budkit.org/tpl">

    <!--Append-->
    <!--This path attribute needs some kind of validation to save against XSS -->
    <!--
    <tpl:append path="//tpl:block[@name='content']">
        <div class="page-header bottom-border">
            <h1>
                <span>Inbox</span>
                <div class="btn-group pull-right" data-toggle="buttons-checkbox" >
                    <button class="btn btn-default"><i class="fui-list-thumbnailed" /></button>
                </div>
            </h1>
        </div>
        <tpl:import name="stream" />
    </tpl:append>
    -->

    <!--Replace-->
    <!--
    <tpl:replace path="//div[@role='side']">
        This replaces something;
    </tpl:replace>
    -->
    <tpl:replace path="//div[@role='main']">
        <div class="container-main">
            <div class="container">
                <div class="container-fluid padding-top">
                    <!--<nav class="navbar navbar-inverse" role="navigation">
                        <div class="container-navigation">
                            <tpl:import name="search" />
                        </div>
                    </nav>-->
                    <div class="text-center mtl">
                        <img src="assets/img/logo.svg" />
                        <p class="mtm">A small framework for building powerful apps</p>
                    </div>
                    <tpl:block name="content" /> <!--use block.content var to append data to this element-->
                </div>
            </div>
        </div>
    </tpl:replace>

    <!--Remove-->
    <tpl:remove path="//div[@role='aside']" />
    <tpl:remove path="//div[@role='side']" />

    <!--Prepend-->
    <!--
    <tpl:replace path="//div[@role='side']">
        This is prepended to Path
    </tpl:replace>
    -->

</tpl:layout>
