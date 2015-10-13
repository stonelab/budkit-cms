<?xml version="1.0" encoding="utf-8"?>
<tpl:layout xmlns:tpl="http://budkit.org/tpl" extends="navigation">

    <tpl:replace path="//a[@role='navbar-brand']">
        <button class="navbar-btn btn btn-default sidebar-toggle mlm mts" data-switch="minimized" data-target=".sidebar">
            <i class="fa fa-bars"></i>
        </button>
        <tpl:block position="navbar-button" />
    </tpl:replace>

    <tpl:replace path="//div[@role='navbar-collapse']">
        <div class="navbar-collapse collapse navbar-inverse-collapse" role="navbar-collapse">

            <tpl:menu uid="usermenu" class="nav navbar-nav navbar-right" />


            <form class="navbar-form navbar-left" action="#" role="search">
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
    </tpl:replace>
</tpl:layout>