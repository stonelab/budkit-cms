/**
 * Created by livingstonefultang on 10/27/13.
 */
require(["jquery","mes","jquery.cookie","jquery.chosen","jquery.bootstrap","bootstrap.flat-ui"], function() {

    var activeTabs = {};

    if($.cookie("activeTabs")){
        activeTabs = JSON.parse($.cookie("activeTabs"));
        $.each(activeTabs, function(index, el){
            // console.log(index);
            $(index).removeClass().addClass(el.className);
           // $(window).trigger('resize');
        });
    }
    $('[data-remember-event]').each(function(index, el){
        var elEvent = $(el).data('remember-event'),
            elId    = $(el).attr('id');
        $(el).on(elEvent, function(e) {
            //alert('toggled');
            activeTabs['#'+elId] = {className:$(el).attr('class')};
            $.cookie("activeTabs", JSON.stringify(activeTabs, null, 2) , {
                expires: 90,
                path: '/'
            } );
        });
    });

    $("select:not(select.native)").chosen({disable_search_threshold: 10,allow_single_deselect: true})

    //the jquery.alpha.js and jquery.beta.js plugins have been loaded.
    $(function(){
        var $window = $(window);
        var $body   = $(document.body);
        $("[data-toggle]").on('click', function(e){
            e.preventDefault();
            var  target = $(this).attr('data-target'),
                detail = $(this).attr('data-toggle');

            $(target).toggleClass(detail);
            $(target).trigger('toggle');
            $(window).trigger('resize');
        })
    });

    //Media Players
    $('[data-target="media-player"]').each(function(){
//            var msource = $(this).attr("data-source"),
//                mformat = $(this).attr("data-formats"),
//                mstyler = $(this).attr("data-ancestor"),
//                media   = {};
        var $templatePath = $(this).next('[name="pluginpath"]').attr("value"),
            $figure       = $(this).parent('figure')
            ;

        $(this).mediaelementplayer({
            alwaysShowControls: false,
            preload:true,
            plugins: ['flash','silverlight'],
            pluginPath: $templatePath+'/assets/js/mes/',
            pluginWidth: $figure.width(),
            flashName: 'flashmediaelement.swf',
            silverlightName: 'silverlightmediaelement.xap',
            features: ['playpause','current','progress','duration','tracks','volume','fullscreen'],
            // method that fires when the Flash or Silverlight object is ready
        });
    });

    // Add segments to a slider
    $.fn.addSliderSegments = function (amount, orientation) {
        return this.each(function () {
            if (orientation === 'vertical') {
                var output = '';
                var i;
                for (i = 1; i <= amount - 2; i++) {
                    output += '<div class="ui-slider-segment" style="top:' + 100 / (amount - 1) * i + '%;"></div>';
                }
                $(this).prepend(output);
            } else {
                var segmentGap = 100 / (amount - 1) + '%';
                var segment = '<div class="ui-slider-segment" style="margin-left: ' + segmentGap + ';"></div>';
                $(this).prepend(segment.repeat(amount - 2));
            }
        });
    };



        // Todo list
        $('.todo').on('click', 'li', function () {
            $(this).toggleClass('todo-done');
        });

        $('[data-switch]').click(function(){
            console.log("minimize button clicked");
            $( $(this).attr("data-target")).toggleClass(  $(this).attr("data-switch") );
        })

        // Custom Selects
        if ($('[data-toggle="select"]').length) {
            $('[data-toggle="select"]').select2();

        }

        // Checkboxes and Radio buttons
        $(':checkbox').radiocheck();
        $('[data-toggle="radio"]').radiocheck();

        $(".dropdown-toggle").dropdown();

        // Tooltips
        $('[data-toggle=tooltip]').tooltip('show');

        // jQuery UI Sliders
        var $slider = $('#slider');
        if ($slider.length > 0) {
            $slider.slider({
                min: 1,
                max: 5,
                value: 3,
                orientation: 'horizontal',
                range: 'min'
            }).addSliderSegments($slider.slider('option').max);
        }

        var $verticalSlider = $('#vertical-slider');
        if ($verticalSlider.length) {
            $verticalSlider.slider({
                min: 1,
                max: 5,
                value: 3,
                orientation: 'vertical',
                range: 'min'
            }).addSliderSegments($verticalSlider.slider('option').max, 'vertical');
        }

        // Focus state for append/prepend inputs
        $('.input-group').on('focus', '.form-control', function () {
            $(this).closest('.input-group, .form-group').addClass('focus');
        }).on('blur', '.form-control', function () {
            $(this).closest('.input-group, .form-group').removeClass('focus');
        });

        // Make pagination demo work
        $('.pagination').on('click', 'a', function () {
            $(this).parent().siblings('li').removeClass('active').end().addClass('active');
        });

        $('.btn-group').on('click', 'a', function () {
            $(this).siblings().removeClass('active').end().addClass('active');
        });

        // Disable link clicks to prevent page scrolling
        $(document).on('click', 'a[href="#fakelink"]', function (e) {
            e.preventDefault();
        });

        // Switches
        if ($('[data-toggle="switch"]').length) {
            $('[data-toggle="switch"]').bootstrapSwitch();
        }

        // make code pretty
        window.prettyPrint && prettyPrint();

});
