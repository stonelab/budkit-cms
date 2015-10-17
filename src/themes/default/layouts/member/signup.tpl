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
            <tpl:block name="content"/>
            <!--use block.content var to append data to this element-->

            <div class="login-screen">

                <div class="text-center mtl mbm">
                    <a href="/"><img src="/theme/assets/img/logo-light.svg" width="60"/></a>
                </div>

                <tpl:condition on="config://setup.site.registration" test="boolean" is="false">
                    <div class="text-center color-info mvn">Registration is currently not allowed on this site.</div>
                </tpl:condition>
                <tpl:condition on="config://setup.site.registration" test="boolean" is="true">
                    <p class="text-center mbm color-clouds">Create an Account</p>

                    <div class="login-alerts">
                        <tpl:import name="alerts"/>
                    </div>

                    <div class="login-form">

                        <form action="/member/signup" method="post">
                            <div class="form-group">
                                <input type="text" name="user_first_name" class="form-control login-field" value=""
                                       placeholder="First name" id="login-name"/>
                                <label class="login-field-icon fui-user" for="login-name"></label>
                            </div>

                            <div class="form-group">
                                <input type="text" name="user_last_name" class="form-control login-field" value=""
                                       placeholder="Last name" id="login-name"/>
                                <label class="login-field-icon fui-user" for="login-name"></label>
                            </div>

                            <div class="form-group">
                                <input type="text" name="user_name_id" class="form-control login-field" value=""
                                       placeholder="Username" id="login-name"/>
                                <label class="login-field-icon fui-link" for="login-name"></label>
                            </div>

                            <div class="form-group">
                                <input type="email" name="user_email" class="form-control login-field" value=""
                                       placeholder="Email" id="login-name"/>
                                <label class="login-field-icon fui-mail" for="login-name"></label>
                            </div>

                            <div class="form-group">
                                <input type="password" name="user_password" class="form-control login-field" value=""
                                       placeholder="Password" id="login-pass"/>
                                <label class="login-field-icon fui-lock" for="login-pass"></label>
                            </div>

                            <div class="form-group">
                                <input type="password" name="user_password_2" class="form-control login-field" value=""
                                       placeholder="Verify Password" id="login-pass"/>
                                <label class="login-field-icon fui-lock" for="login-pass"></label>
                            </div>

                            <button type="submit" class="btn btn-primary btn-embossed btn-lg btn-block">Create Account
                            </button>
                            <!--<a class="login-link" href="#">Lost your password?</a>-->
                            <a class="login-link" href="/member/signin">Already have an account?</a>
                        </form>

                    </div>
                </tpl:condition>

            </div>
        </div>
    </tpl:replace>

    <!--Remove-->
    <tpl:remove path="//div[@role='aside']"/>
    <tpl:remove path="//div[@role='side']"/>

    <!--Prepend-->
    <!--
    <tpl:replace path="//div[@role='side']">
        This is prepended to Path
    </tpl:replace>
    -->

</tpl:layout>