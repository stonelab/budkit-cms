<?xml version="1.0" encoding="utf-8"?>
<tpl:layout name="install" xmlns:tpl="http://budkit.org/tpl" extends="index">

    <!--Remove-->
    <tpl:remove path="//div[@role='aside']" />
    <tpl:remove path="//div[@role='side']" />


    <!--Replace-->
    <tpl:replace path="//div[@role='main']">
        <div class="container-main admin" role="main">

            <div class="container padding-top">
                <!--<nav class="navbar navbar-inverse" role="navigation">
                    <div class="container-navigation">
                        <tpl:import name="search" />
                    </div>
                </nav>-->

                <div class="row mtl">
                    <div class="col-md-12">
                        <div class="panel panel-canvas">
                            <tpl:condition on="step" test="equals" is="1" >
                                <!--if test match replace node with children, if false remove node-->
                                <tpl:import name="admin/setup/license" />
                            </tpl:condition>

                            <tpl:condition on="step" test="equals" is="2" >
                                <!--if test match replace node with children, if false remove node-->
                                <tpl:import name="admin/setup/requirements" />
                            </tpl:condition>

                            <tpl:condition on="step" test="equals" is="3" >
                                <!--if test match replace node with children, if false remove node-->
                                <tpl:import name="admin/setup/database" />
                            </tpl:condition>

                            <tpl:condition on="step" test="equals" is="4" >
                                <!--if test match replace node with children, if false remove node-->
                                <tpl:import name="admin/setup/user" />
                            </tpl:condition>

                        </div><!-- panel -->
                    </div><!-- col-md-9 -->
                </div>
            </div>
        </div>
    </tpl:replace>
</tpl:layout>
