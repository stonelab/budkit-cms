<?xml version="1.0" encoding="utf-8"?>
<tpl:layout xmlns:tpl="http://budkit.org/tpl" extends="index">

    <!--Append-->
    <!--This path attribute needs some kind of validation to save against XSS -->
    <tpl:append path="//tpl:block[@name='content']">
        <div class="page-header profile-header">
            <div class="profile-cover hero" />
            <tpl:link rel="person" wrap="span" status="online"  src="assets/img/avatars/hamilton.jpeg" class="person" width="100" height="100"/>
            <h1>
                <span>Livingstone Fultang</span>
            </h1>
            <div class="navbar navbar-tabs navbar-sm">
                <div class="navbar-holder">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="summary.html">Summary</a></li>
                        <li><a href="project.html">Activity</a></li>
                        <li><a href="tasks.html">Tasks</a></li>
                        <li><a href="calendar.html">Calendar</a></li>
                        <li><a href="meetings.html">Meetings</a></li>
                        <li><a href="documents.html">Files</a></li>
                        <li><a href="editor.html">Notes</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="#"><span class="glyphicon glyphicon-cog"></span> Edit Profile</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!--<tpl:import name="stream" />-->
    </tpl:append>
    <tpl:replace path="//tpl:import[@name='asidebar']">
       <tpl:import name="person/asidebar" />
    </tpl:replace>
</tpl:layout>