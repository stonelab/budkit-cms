<?xml version="1.0" encoding="utf-8"?>
<tpl:layout name="page" extends="index" xmlns:tpl="http://budkit.org/tpl">
    <!--Remove-->
    <tpl:remove path="//div[@role='side']"/>
    <tpl:remove path="//div[@role='aside']"/>

    <tpl:replace path="//div[@role='main']">
        <div class="container-main">
            <div class="container-navigation">
                <tpl:import name="navigation" />
            </div>
            <div class="hero">
                <div class="container unit">
                    <h1>Coming Soon.</h1>
                    <p class="highlight">Finding homes for Refugees &amp; Assylum Seekers in the United Kingdom</p>
                </div>
            </div>
        </div>
    </tpl:replace>
</tpl:layout>

