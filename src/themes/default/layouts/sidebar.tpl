<?xml version="1.0" encoding="UTF-8"?>
<tpl:layout xmlns:tpl="http://budkit.org/tpl" name="index">
    <div class="container-block">
    <div class="profile">
         <a href="/">
            <img src="/theme/assets/img/logo-light.svg" class="dp img-circle" width="90" />
         </a>
     </div>
    <br />
    <tpl:menu uid="dashboardmenu" type="nav-stacked" show-icons="true" class="nav menu" />
    <hr />
    <ul class="nav nav-stacked menu">
        <li>
            <a href="/admin/tags/urgent">
                <i class="menu-icon  ion ion-android-radio-button-on color-alizarin" />
                <span class="menu-text">Urgent</span>
            </a>
        </li>
        <li>
            <a href="/admin/tags/task">
                <i class="menu-icon  ion ion-android-radio-button-on color-carrot" />
                <span class="menu-text">Task</span>
            </a>
        </li>
        <li>
            <a href="/admin/tags/information">
                <i class="menu-icon  ion ion-android-radio-button-on color-belize-hole" />
                <span class="menu-text">Information</span>
            </a>
        </li>
        <li>
            <a href="/admin/tags/complete">
                <i class="menu-icon  ion ion-android-radio-button-on color-green-sea" />
                <span class="menu-text">Result</span>
            </a>
        </li>
        <li>
            <a href="#">
                <i class="menu-icon ion ion-android-add-circle" />
                <span class="menu-text">New Label</span>
            </a>
        </li>
    </ul>
    </div>
</tpl:layout>