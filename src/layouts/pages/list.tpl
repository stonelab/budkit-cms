<?xml version="1.0" encoding="utf-8"?>
<tpl:layout name="pages" xmlns:tpl="http://budkit.org/tpl">
    <div class="col-md-12">
        <div class="panel panel-list">

            <div class="panel-heading clearfix">
                <form class="form-inline col-md-12 col-sm-12" method="POST" action="/settings/system/save">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>
                                <select aria-controls="table1" class="form-control flat native  input-sm">
                                    <option value="">List length ...</option>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">

                    </div>
                    <div class="col-md-3">
                        <div class="form-group pull-right">
                            <label>
                                <div class="input-group input-group-sm">
                                    <input class="form-control input-sm flat" type="search" placeholder="Filter..."/>
                                      <span class="input-group-btn flat">
                                        <button type="submit" class="btn"><span class="fui-search"></span></button>
                                      </span>
                                </div>
                            </label>

                        </div>
                    </div>
                </form>
            </div>
            <div class="panel-body clearfix row">

                <div class="table-responsive">
                    <table class="table table-striped table-vertical-aligned    ">
                        <thead>
                        <tr>

                            <th width="2%"></th>
                            <th width="38%">Title</th>
                            <th>Created</th>
                            <th>Author</th>
                            <th width="18%">Permalink</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tpl:loop foreach="pages.items">
                            <tr>
                                <td>
                                    <label class="checkbox">
                                        <input type="checkbox" name="page_uri[]" tpl:value="${object_uri}" />
                                    </label>
                                </td>
                                <td>

                                    <a tpl:href="/page/${object_uri}">
                                        <tpl:data value="media_title" />


                                    </a>
                                        <tpl:condition on="object_created_on" test="not" is="object_updated_on">
                                            <span class="label label-default">Modified</span>
                                        </tpl:condition>

                                </td>
                                <td><tpl:datetime value="object_created_on" format="diff" /></td>

                                <td><a tpl:href="/member/${media_owner.uri}">@<tpl:data value="media_owner.uri" /></a></td>
                                <td>
                                    /page/<tpl:data value="object_uri" />
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group" aria-label="...">
                                        <a tpl:href="/page/${object_uri}/edit" class="btn btn-default">Edit</a>
                                        <a tpl:href="/page/${object_uri}/delete" class="btn btn-primary">Publish</a>
                                        <a tpl:href="/page/${object_uri}/delete" class="btn btn-danger">Trash</a>

                                    </div>
                                </td>
                            </tr>
                        </tpl:loop>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer">
                <ul class="pagination-plain mtn mbn">
                    <li class="previous">
                        <a href="#"><i class="fui-arrow-left"></i> Previous</a>
                    </li>
                    <li><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li class="next">
                        <a href="#">Next <i class="fui-arrow-right"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</tpl:layout>


