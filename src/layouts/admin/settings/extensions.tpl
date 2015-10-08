<tpl:layout xmlns="http://www.w3.org/1999/xhtml" xmlns:tpl="http://budkit.org/tpl">
    <div class="padding">  
        <div class="clearfix">
            <form class="pull-right input-prepend">   
                <i class="icon-search add-on"></i>
                <input type="text" class="search-query span2" placeholder="Search for extensions" />
            </form>
            <ul class="nav nav-pills no-margin">
                <li class="highlighted"><a href="/system/content/create" >Submit New</a></li>
            </ul>
        </div>
        <hr />
        <div class="panel">
            <div class="panel-head"><span class="panel-title">Repository</span><a class="panel-close" data-dismiss="panel"><i class="icon-refresh"></i></a></div>
            <div class="panel-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="span1">Icon</th>
                            <th class="span2">Name</th>    
                            <th class="span2">Rating</th>
                            <th class="span5">Description</th>
                            <th class="span1">Version</th>
                            <th class="span1">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><img src="http://placehold.it/50x50/eeeeee" class="thumbnail" /></td>
                            <td><strong>Campus</strong><span class="help-block">By Livingstone Fultang</span></td>  
                            <td>
                                <span class="rating">
                                    <span class="star"></span><span class="star"></span><span class="star active"></span><span class="star"></span><span class="star"></span>
                                </span>
                                <a href="#"><span class="help-block">250 comments</span></a>
                            </td>
                            <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean massa nibh, congue non tempus ut, congue in dui. Pellentesque accumsan dolor eu libero blandit quis sagittis nisl mattis.</td>
                            <td>1.0.0</td>
                            <td><a href="#" class="btn btn-small">Install</a></td>
                        </tr>
                        <?php for ($i = 0; $i < 2; $i++): ?>
                        <tr>
                            <td><img src="http://placehold.it/50x50/eeeeee" class="thumbnail" /></td>
                            <td><strong>Campus</strong><span class="help-block">By Livingstone Fultang</span></td>  
                            <td>
                                <span class="rating">
                                    <span class="star"></span><span class="star"></span><span class="star active"></span><span class="star"></span><span class="star"></span>
                                </span>
                                <a href="#"><span class="help-block">250 comments</span></a>
                            </td>
                            <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean massa nibh, congue non tempus ut, congue in dui. Pellentesque accumsan dolor eu libero blandit quis sagittis nisl mattis.</td>
                            <td>1.0.0</td>
                            <td><a href="#" class="btn btn-small btn-danger">Uninstall</a></td>
                        </tr>
                        <?php endfor; ?>
                        <tr class="warning">
                            <td><img src="http://placehold.it/50x50/eeeeee" class="thumbnail" /></td>
                            <td><strong>Campus</strong><span class="help-block">By Livingstone Fultang</span></td>  
                            <td>
                                <span class="rating">
                                    <span class="star"></span><span class="star"></span><span class="star active"></span><span class="star"></span><span class="star"></span>
                                </span>
                                <a href="#"><span class="help-block">250 comments</span></a>
                            </td>
                            <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean massa nibh, congue non tempus ut, congue in dui. Pellentesque accumsan dolor eu libero blandit quis sagittis nisl mattis.</td>
                            <td>1.0.0</td>
                            <td><a href="#" class="btn btn-small btn-success">Update</a></td>
                        </tr>
                        <?php for ($i = 0; $i < 2; $i++): ?>
                        <tr>
                            <td><img src="http://placehold.it/50x50/eeeeee" class="thumbnail" /></td>
                            <td><strong>Campus</strong><span class="help-block">By Livingstone Fultang</span></td>  
                            <td>
                                <span class="rating">
                                    <span class="star"></span><span class="star"></span><span class="star active"></span><span class="star"></span><span class="star"></span>
                                </span>
                                <a href="#"><span class="help-block">250 comments</span></a>
                            </td>
                            <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean massa nibh, congue non tempus ut, congue in dui. Pellentesque accumsan dolor eu libero blandit quis sagittis nisl mattis.</td>
                            <td>1.0.0</td>
                            <td><a href="#" class="btn btn-small">Install</a></td>
                        </tr>
                        <?php endfor; ?>
                        <tr>
                            <td><img src="http://placehold.it/50x50/eeeeee" class="thumbnail" /></td>
                            <td><strong>Campus</strong><span class="help-block">By Livingstone Fultang</span></td>  
                            <td>
                                <span class="rating">
                                    <span class="star"></span><span class="star"></span><span class="star active"></span><span class="star"></span><span class="star"></span>
                                </span>
                                <a href="#"><span class="help-block">250 comments</span></a>
                            </td>
                            <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean massa nibh, congue non tempus ut, congue in dui. Pellentesque accumsan dolor eu libero blandit quis sagittis nisl mattis.</td>
                            <td>1.0.0</td>
                            <td><a href="#" class="btn btn-small">Install</a></td>
                        </tr>
                        <tr>
                            <td><img src="http://placehold.it/50x50/eeeeee" class="thumbnail" /></td>
                            <td><strong>Campus</strong><span class="help-block">By Livingstone Fultang</span></td>  
                            <td>
                                <span class="rating">
                                    <span class="star"></span><span class="star"></span><span class="star active"></span><span class="star"></span><span class="star"></span>
                                </span>
                                <a href="#"><span class="help-block">250 comments</span></a>
                            </td>
                            <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean massa nibh, congue non tempus ut, congue in dui. Pellentesque accumsan dolor eu libero blandit quis sagittis nisl mattis.</td>
                            <td>1.0.0</td>
                            <td><a href="#" class="btn btn-small">Install</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>            
        </div>
        <tpl:import layout="pagination" />
    </div>
</tpl:layout>