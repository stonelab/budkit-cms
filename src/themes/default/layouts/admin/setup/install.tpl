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
                           <tpl:block position="form" />
                        </div><!-- panel -->
                    </div><!-- col-md-9 -->
                </div>
            </div>
        </div>
    </tpl:replace>
</tpl:layout>
