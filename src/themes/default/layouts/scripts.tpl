<?xml version="1.0" encoding="utf-8"?>
<tpl:layout name="scripts" xmlns:tpl="http://budkit.org/tpl">

    <script data-main="/theme/assets/js/main" src="/theme/assets/js/require.js"></script>

    <tpl:loop foreach="scripts">
        <script tpl:src="${src}" />
    </tpl:loop>

</tpl:layout>
