<?xml version="1.0" encoding="utf-8"?>
<tpl:layout xmlns:tpl="http://budkit.org/tpl" name="navigation">


        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-inverse-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="navbar-collapse collapse navbar-inverse-collapse pln" role="navbar-collapse">

            <tpl:menu uid="usermenu" class="nav navbar-nav navbar-right" />

            <button class="navbar-btn btn btn-sm btn-default sidebar-toggle navbar-left" data-switch="minimized" data-target=".sidebar">
                <i class="fa fa-bars"></i>
            </button>

            <tpl:condition on="action" test="empty" is="false">
                <div class="btn-group navbar-left  mlm">
                    <tpl:loop foreach="action">
                        <a tpl:class="btn navbar-btn btn-sm btn-embossed ${class}" tpl:href="${link}"><tpl:data value="title" /></a>
                    </tpl:loop>
                </div>
            </tpl:condition>

            <tpl:block position="navbar-button" />

            <form class="navbar-form navbar-left plm prn" action="#" role="search">
                <div class="form-group">
                    <div class="input-group">
                        <input class="form-control flat" id="navbarInput-01" type="search" placeholder="Search"/>
                          <span class="input-group-btn flat">
                            <button type="submit" class="btn"><span class="fui-search"></span></button>
                          </span>
                    </div>
                </div>
            </form>

        </div>


</tpl:layout>