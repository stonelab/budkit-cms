<?xml version="1.0" encoding="utf-8"?>
<tpl:layout xmlns:tpl="http://budkit.org/tpl" name="navigation">
    <div class="navbar navbar-primary  mvn" role="navigator">
        <div class="container" role="navbar-container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-inverse-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand pts plm" href="/" role="navbar-brand"><img src="/theme/assets/img/logo-dark-full.svg" height="40" /></a>
            </div>
            <div class="navbar-collapse collapse navbar-inverse-collapse" role="navbar-collapse">
                <tpl:menu uid="usermenu" class="nav navbar-nav navbar-right" />
            </div>
        </div>
    </div>
</tpl:layout>