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


            <div class="login-screen">

                <div class="text-center mtl mbm">
                    <a href="/"><img src="/theme/assets/img/logo-light.svg" width="60" /></a>
                </div>
                <p class="text-center mbm color-clouds"><tpl:data value="title" /></p>
                <div class="login-alerts"><tpl:import name="alerts" /></div>

                <div class="login-form">
                    <tpl:block name="content" /> <!--use block.content var to append data to this element-->

                    <form action="/member/signin" method="post">
                        <div class="form-group">
                            <input type="text" name="user_name_id" class="form-control login-field" value="" placeholder="Enter your email or username" id="login-name"/>
                            <label class="login-field-icon fui-user" for="login-name"></label>
                        </div>

                        <div class="form-group">
                            <input type="password" name="user_password" class="form-control login-field" value="" placeholder="Password" id="login-pass"/>
                            <label class="login-field-icon fui-lock" for="login-pass"></label>
                        </div>

                        <button class="btn btn-primary btn-lg btn-embossed btn-block" type="submit">Log in</button>
                        <a class="login-link" href="/member/signin/reset">Lost your password?</a> <a class="login-link" href="/member/signup">Don't have an account?</a>
                    </form>
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