<?xml version="1.0" encoding="utf-8"?>
<tpl:layout xmlns:tpl="http://budkit.org/tpl" extends="index">


    <tpl:replace path="//div[@role='main']">
        <div class="container-main admin" role="main">

            <div class="container-navigation">
                <tpl:import name="admin/navigation" />
            </div>


            <div class="container-fluid padding-top">
                <!--<nav class="navbar navbar-inverse" role="navigation">
                    <div class="container-navigation">
                        <tpl:import name="search" />
                    </div>
                </nav>-->

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

                <div class="page-header">
                    <h1 class="mvn">
                        <span>Admin</span>
                        <div class="btn-group pull-right" data-toggle="buttons-checkbox" >
                            <button class="btn btn-default"><i class="fui-list-thumbnailed" /></button>
                        </div>
                    </h1>
                    <!--
                    <div class="navbar navbar-tabs navbar-sm">
                        <div class="navbar-holder">
                            <ul class="nav navbar-nav">
                                <li class="active"><a href="meetings.html">Activity</a></li>
                                <li><a href="discussions.html">Conversations <span class="label-danger label">1</span></a></li>
                                <li><a href="documents.html">Task list <span class="label-danger label">100+</span></a></li>
                            </ul>
                        </div>
                    </div> -->
                </div>
                <div class="pls prs ptl pbl">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="panel panel-announcement">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Latest Announcement</h4>
                                </div>
                                <div class="panel-body">
                                    <h2>The roll out of this portal is still on going. Further discussions on <a href="#">#techincal</a></h2>
                                    <p>Get started with this <a href="">guided tour.</a></p>
                                </div>
                            </div><!-- panel -->

                            <div class="panel panel-statistic">
                                <div class="panel-heading">
                                    <h4 class="panel-title text-success">How we are performing</h4>
                                    <p class="nomargin">Past 30 Days — Last Updated September 19, 2015</p>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-6 col-md-3">
                                            <div class="pull-left">
                                                <div class="icon icon icon-number"><i class="fa fa-bar-chart"></i> </div>
                                            </div>
                                            <div class="pull-left">
                                                <h4 class="panel-title">Member Sign-Up</h4>
                                                <h3>2.3k</h3>
                                                <h5 class="text-success">2.00% increased</h5>
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-md-3">
                                            <div class="pull-left">
                                                <div class="icon icon icon-information"><i class="fa fa-info"></i></div>
                                            </div>
                                            <h4 class="panel-title">Pageviews / Visitor</h4>
                                            <h3>38.10</h3>
                                            <h5 class="text-danger">5.70% decreased</h5>
                                        </div>
                                        <div class="col-xs-6 col-md-3">
                                            <div class="pull-left">
                                                <div class="icon icon icon-information"><i class="fa fa-info"></i></div>
                                            </div>
                                            <h4 class="panel-title">Pageviews / Visitor</h4>
                                            <h3>38.10</h3>
                                            <h5 class="text-danger">5.70% decreased</h5>
                                        </div>
                                        <div class="col-xs-6 col-md-3">
                                            <div class="pull-left">
                                                <div class="icon icon icon-time"><i class="fa fa-clock-o"></i></div>
                                            </div>
                                            <h4 class="panel-title">Refugees Matched</h4>
                                            <h3>10</h3>
                                            <h5 class="text-success">5.00% increased</h5>
                                        </div>
                                    </div><!-- row -->



                                </div><!-- panel-body -->


                            </div><!-- panel -->

                            <div class="row panel-statistics">
                                <div class="col-sm-6">
                                    <div class="panel panel-updates">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-xs-7 col-lg-8">
                                                    <h4 class="panel-title text-success">Products Added</h4>
                                                    <h3>75.7%</h3>
                                                    <div class="progress">
                                                        <div style="width: 75.7%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="75.7" role="progressbar" class="progress-bar progress-bar-success">
                                                            <span class="sr-only">75.7% Complete (success)</span>
                                                        </div>
                                                    </div>
                                                    <p>Added products for this month: 75</p>
                                                </div>
                                                <div class="col-xs-5 col-lg-4 text-right">

                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- panel -->
                                </div><!-- col-sm-6 -->

                                <div class="col-sm-6">
                                    <div class="panel panel-danger-full panel-updates">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-xs-7 col-lg-8">
                                                    <h4 class="panel-title text-warning">Products Rejected</h4>
                                                    <h3>39.9%</h3>
                                                    <div class="progress">
                                                        <div style="width: 39.9%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="39.9" role="progressbar" class="progress-bar progress-bar-warning">
                                                            <span class="sr-only">39.9% Complete (success)</span>
                                                        </div>
                                                    </div>
                                                    <p>Rejected products for this month: 45</p>
                                                </div>
                                                <div class="col-xs-5 col-lg-4 text-right">

                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- panel -->
                                </div><!-- col-sm-6 -->

                                <div class="col-sm-6">
                                    <div class="panel panel-success-full panel-updates">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-xs-7 col-lg-8">
                                                    <h4 class="panel-title text-success">Products Sold</h4>
                                                    <h3>55.4%</h3>
                                                    <div class="progress">
                                                        <div style="width: 55.4%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="55.4" role="progressbar" class="progress-bar progress-bar-info">
                                                            <span class="sr-only">55.4% Complete (success)</span>
                                                        </div>
                                                    </div>
                                                    <p>Sold products for this month: 1,203</p>
                                                </div>
                                                <div class="col-xs-5 col-lg-4 text-right">

                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- panel -->
                                </div><!-- col-sm-6 -->

                                <div class="col-sm-6">
                                    <div class="panel panel-updates">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-xs-7 col-lg-8">
                                                    <h4 class="panel-title text-danger">Products Returned</h4>
                                                    <h3>22.1%</h3>
                                                    <div class="progress">
                                                        <div style="width: 22.1%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="22.1" role="progressbar" class="progress-bar progress-bar-danger">
                                                            <span class="sr-only">22.1% Complete (success)</span>
                                                        </div>
                                                    </div>
                                                    <p>Returned products this month: 22</p>
                                                </div>
                                                <div class="col-xs-5 col-lg-4 text-right">

                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- panel -->
                                </div><!-- col-sm-6 -->

                            </div><!-- row -->



                        </div><!-- col-md-9 -->
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-sm-6 col-md-12">
                                    <div class="panel panel-danger panel-weather">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">Weather Forecast</h4>
                                        </div>
                                        <div class="panel-body background-danger light-text">
                                            <div class="row mb10">
                                                <div class="col-xs-6">
                                                    <h2 class="today-day">Monday</h2>
                                                    <h3 class="today-date">July 13, 2015</h3>
                                                </div>
                                                <div class="col-xs-6">
                                                    <i class="wi wi-hail today-cloud"></i>
                                                </div>
                                            </div>
                                            <p class="nomargin">Thunderstorm in the area of responsibility this afternoon through this evening.</p>
                                            <div class="row mt10">
                                                <div class="col-xs-7">
                                                    <strong>Temperature:</strong> 19 (Celcius)
                                                </div>
                                                <div class="col-xs-5">
                                                    <strong>Wind:</strong> 30+ mph
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- col-md-12 -->
                            </div><!-- row -->

                            <div class="row">
                                <div class="col-sm-6 col-md-12">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">Recent User Activity</h4>
                                        </div>
                                        <div class="panel-body pln prn">
                                            <tpl:import name="list" />
                                        </div>
                                    </div><!-- panel -->
                                </div>
                            </div><!-- row -->

                        </div><!-- col-md-3 -->
                    </div>

                </div>
            </div>
        </div>
    </tpl:replace>

    <!--Remove-->
    <tpl:remove path="//div[@role='aside']" />



</tpl:layout>