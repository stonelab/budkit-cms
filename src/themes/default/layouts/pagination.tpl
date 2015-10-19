<?xml version="1.0" encoding="utf-8"?>
<tpl:layout name="pagination" xmlns:tpl="http://budkit.org/tpl">

    <tpl:condition on="pagination.pages" test="empty" is="false">

        <div class="btn-group">

            <tpl:condition on="pagination.previous" test="empty" is="false">
                <a tpl:href="${pagination.previous}" class="btn btn-sm btn-default">Previous</a>
            </tpl:condition>

            <tpl:loop foreach="pagination.pages">
                <a tpl:href="${page_link}" tpl:class="btn btn-sm btn-default ${page_state}"><tpl:data value="page_title" /></a>
            </tpl:loop>

            <tpl:condition on="pagination.next" test="empty" is="false">
                <a tpl:href="${pagination.next}" class="btn btn-sm btn-default">Next</a>
            </tpl:condition>

        </div>

    </tpl:condition>

</tpl:layout>
