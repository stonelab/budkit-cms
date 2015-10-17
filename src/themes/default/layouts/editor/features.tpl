<?xml version="1.0" encoding="utf-8"?>
<tpl:layout xmlns:tpl="http://budkit.org/tpl">
    <form class="plm prm pts pbs mbn" action="#" role="search">
        <div class="form-group mbn">
            <div class="input-group input-group-sm">
                <input class="form-control" id="navbarInput-01" type="search" placeholder="Search Elements"/>
                <span class="input-group-btn">
                    <button type="submit" class="btn"><span class="fui-search"></span></button>
                </span>
            </div>
        </div>
    </form>
    <div class="page-features">
        <div class="row">

            <div class="col-md-12">

                <!-- PAGE HEADER -->
                <div class="page-feature col-md-6">
                    <div class="definition">
                        <i class="ion ion-information"></i>
                        <span>Title</span>
                    </div>
                    <div class="template">
                        <div class="container">
                            <div class="row text-center mb">
                                <h1 class="page-title">Click here to Edit Title
                                    <p class="small help-block">Add a descriptive page sub-title or <i>a punch line</i>
                                    </p>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- CAROUSEL -->
                <div class="page-feature col-md-6">
                    <div class="definition">
                        <i class="ion ion-images"></i>
                        <span>Slideshow</span>
                    </div>
                    <div class="template">
                        <div class="container">
                            <div class="page-carousel carousel slide" data-ride="carousel">
                                <!-- Indicators -->
                                <ol class="carousel-indicators">
                                    <li data-target="#myCarousel" data-slide-to="0" class=""></li>
                                    <li data-target="#myCarousel" data-slide-to="1" class=""></li>
                                    <li data-target="#myCarousel" data-slide-to="2" class="active"></li>
                                </ol>
                                <div class="carousel-inner" role="listbox">
                                    <div class="item">
                                        <img class="first-slide" src="//placehold.it/1024x400/auto" alt="First slide" />
                                        <div class="container">
                                            <div class="carousel-caption">
                                                <h1>Example headline.</h1>
                                                <p>Note: If you're viewing this page via a <code>file://</code> URL, the "next" and "previous" Glyphicon buttons on the left and right might not load/display properly due to web browser security rules.</p>
                                                <p><a class="btn btn-lg btn-primary" href="#" role="button">Sign up today</a></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <img class="second-slide" src="//placehold.it/1024x400/auto" alt="Second slide" />
                                        <div class="container">
                                            <div class="carousel-caption">
                                                <h1>Another example headline.</h1>
                                                <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                                                <p><a class="btn btn-lg btn-primary" href="#" role="button">Learn more</a></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item active">
                                        <img class="third-slide" src="//placehold.it/1024x400/auto" alt="Third slide" />
                                        <div class="container">
                                            <div class="carousel-caption">
                                                <h1>One more for good measure.</h1>
                                                <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                                                <p><a class="btn btn-lg btn-primary" href="#" role="button">Browse gallery</a></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TESTIMONIAL -->
                <div class="page-feature col-md-6">
                    <div class="definition">
                        <i class="ion ion-star"></i>
                        <span>Testimonial</span>
                    </div>
                    <div class="template">
                        <div class="container testimonial">
                            <blockquote>"I think it's time research collaboration and communication makes it to the 21st
                                century. LabSpot makes one giant leap in the right direction."
                            </blockquote>
                            <p class="author">Dr Christopher Green - Immunologist</p>
                        </div>
                    </div>
                </div>

                <!-- PARAGRAPH -->
                <div class="page-feature col-md-6">
                    <div class="definition">
                        <i class="ion ion-compose"></i>
                        <span>Content</span>
                    </div>
                    <div class="template">
                        <div class="container content">
                            <p>Click here to edit content </p>
                        </div>
                    </div>
                </div>

                <!-- SEPERATOR -->
                <div class="page-feature col-md-6">
                    <div class="definition">
                        <i class="ion ion-compose"></i>
                        <span>Seperator</span>
                    </div>
                    <div class="template">
                        <hr class="page-featurette-divider" />
                    </div>
                </div>


                <tpl:condition on="editor" test="equals" is="page">

                    <!-- FEATURETTE 1 -->
                    <div class="page-feature col-md-6">
                        <div class="definition">
                            <i class="ion ion-compose"></i>
                            <span>Featurette 1</span>
                        </div>
                        <div class="template">
                            <div class="container">
                                <div class="row page-featurette">
                                    <div class="col-md-5">
                                        <img class="featurette-image img-responsive center-block" src="//placehold.it/500x500" alt="Generic placeholder image" />
                                    </div>
                                    <div class="col-md-7">
                                        <h1 class="lead">First featurette heading.  <span class="text-muted">It will blow your mind.</span></h1>
                                        <p class="lead">Donec ullamcorper nulla non metus auctor fringilla. Vestibulum id ligula porta felis euismod semper. Praesent commodo cursus magna, vel scelerisque nisl consectetur. Fusce dapibus, tellus ac cursus commodo.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- FEATURETTE 2 -->
                    <div class="page-feature col-md-6">
                        <div class="definition">
                            <i class="ion ion-compose"></i>
                            <span>Featurette 2</span>
                        </div>
                        <div class="template">
                            <div class="container">
                                <div class="row page-featurette">
                                    <div class="col-md-7">
                                        <h1 class="lead">First featurette heading.  <span class="text-muted">It will blow your mind.</span></h1>
                                        <p class="lead">Donec ullamcorper nulla non metus auctor fringilla. Vestibulum id ligula porta felis euismod semper. Praesent commodo cursus magna, vel scelerisque nisl consectetur. Fusce dapibus, tellus ac cursus commodo.</p>
                                    </div>
                                    <div class="col-md-5">
                                        <img class="featurette-image img-responsive center-block" src="//placehold.it/500x500" alt="Generic placeholder image" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- FEATURETTE 3 -->
                    <div class="page-feature col-md-6">
                        <div class="definition">
                            <i class="ion ion-compose"></i>
                            <span>Featurette 3</span>
                        </div>
                        <div class="template">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <img class="img-circle" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Generic placeholder image" width="140" height="140" />
                                        <h2>Heading</h2>
                                        <p>Donec sed odio dui. Etiam porta sem malesuada magna mollis euismod. Nullam id dolor id nibh ultricies vehicula ut id elit. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Praesent commodo cursus magna.</p>
                                        <p><a class="btn btn-default" href="#" role="button">View details »</a></p>
                                    </div><!-- /.col-lg-4 -->
                                    <div class="col-lg-4 text-center">
                                        <img class="img-circle" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Generic placeholder image" width="140" height="140" />
                                        <h2>Heading</h2>
                                        <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh.</p>
                                        <p><a class="btn btn-default" href="#" role="button">View details »</a></p>
                                    </div><!-- /.col-lg-4 -->
                                    <div class="col-lg-4 text-center">
                                        <img class="img-circle" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Generic placeholder image" width="140" height="140" />
                                        <h2>Heading</h2>
                                        <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                                        <p><a class="btn btn-default" href="#" role="button">View details »</a></p>
                                    </div><!-- /.col-lg-4 -->
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Footer -->
                    <div class="page-feature col-md-6">
                        <div class="definition">
                            <i class="ion ion-compose"></i>
                            <span>Footer</span>
                        </div>
                        <div class="template">
                            <footer class="container-fluid">
                                <p>A custom footer. Click anywhere to edit.</p>
                                <p><a href="#">Back to top</a></p>
                            </footer>
                        </div>
                    </div>

                    <!-- Cover page -->
                    <div class="page-feature col-md-6">
                        <div class="definition">
                            <i class="ion ion-compose"></i>
                            <span>Cover</span>
                        </div>
                        <div class="template">
                            <div class="page-cover background-secondary">
                                <div class="page-cover-inner">
                                    <div class="cover-container">
                                        <div class="masthead clearfix">
                                            <div class="inner">
                                                <h3 class="masthead-brand">Cover</h3>
                                                <nav>
                                                    <ul class="nav masthead-nav">
                                                        <li class="active"><a href="#">Home</a></li>
                                                        <li><a href="#">Features</a></li>
                                                        <li><a href="#">Contact</a></li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        </div>
                                        <div class="inner cover">
                                            <h1 class="cover-heading">Cover your page.</h1>
                                            <p class="lead">Cover is a one-page template for building simple and beautiful home pages. Download, edit the text, and add your own fullscreen background photo to make it your own.</p>
                                            <p class="lead">
                                                <a href="#" class="btn btn-lg btn-default">Learn more</a>
                                            </p>
                                        </div>
                                        <div class="mastfoot">
                                            <div class="inner">
                                                <p>Cover template for <a href="http://getbootstrap.com">Bootstrap</a>, by <a href="https://twitter.com/mdo">@mdo</a>.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </tpl:condition>
            </div>
        </div>
    </div>
</tpl:layout>