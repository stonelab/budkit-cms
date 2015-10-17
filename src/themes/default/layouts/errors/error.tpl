<?xml version="1.0" encoding="utf-8"?>
<tpl:layout name="error" xmlns:tpl="http://budkit.org/tpl" extends="index">

    <!--Remove-->
    <tpl:remove path="//div[@role='aside']" />
    <tpl:remove path="//div[@role='side']" />

    <!--Replace-->
    <tpl:replace path="//div[@role='main']">
        <div class="container-main admin" role="main">

            <div class="container padding-top">
                <section>
                    <div class="error-panel">
                        <h1>500!</h1>
                        <h3>Something Broke!</h3>
                        <h4>An internal error has occurred implying we were unable to complete your request.<br />Our gurus have been notified.</h4>

                        <hr class="darken" />
                        <a href="/">Try our homepage</a>

                    </div><!-- error-panel -->

                </section>
            </div>
        </div>
    </tpl:replace>

</tpl:layout>