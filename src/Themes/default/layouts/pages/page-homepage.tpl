<?xml version="1.0" encoding="utf-8"?>
<tpl:layout name="page" extends="index" xmlns:tpl="http://budkit.org/tpl">
    <!--Remove-->
    <tpl:remove path="//div[@role='side']"/>
    <tpl:remove path="//div[@role='aside']"/>

    <tpl:replace path="//div[@role='main']">
        <div class="container-main">
            <div class="container-block">
                <div class="hero">
                    <div class="container unit">
                      <h1>Welcome</h1>
                      <p class="highlight">Create a new home page from the admin console</p>
                        <div class="mbl"><a href="/member/dashboard" class="btn btn-embossed btn-danger btn-lg">Visit Dashboard</a></div>
                  </div>
                </div>
            </div>
        </div>
    </tpl:replace>
</tpl:layout>

