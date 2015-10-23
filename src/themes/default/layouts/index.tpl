<?xml version="1.0" encoding="utf-8"?>
<tpl:layout xmlns:tpl="http://budkit.org/tpl" name="index">
    <html lang="en">
        <head>
            <tpl:import name="head" />
        </head>
        <body tpl:class="${page.body.class}">
            <div class="container-side minimized sidebar" role="side"><!-- add "minimized" class to minimize-->
                <tpl:import name="sidebar" />
            </div>
            <div class="container-aside sidebar" role="aside">
                <tpl:import name="asidebar" />
            </div>
            <div class="container-main" role="main">
                <div class="container-navigation">
                    <tpl:import name="navigation" />
                </div>
                <div class="container-fluid padding-top">
                    <!--<nav class="navbar navbar-inverse" role="navigation">
                        <div class="container-navigation">
                            <tpl:import name="search" />
                        </div>
                    </nav>-->
                    <tpl:block name="content" /> <!--use block.content var to append data to this element-->
                </div>
            </div>
        </body>
    </html>
</tpl:layout>