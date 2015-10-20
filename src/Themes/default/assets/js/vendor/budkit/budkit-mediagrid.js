!function ($) {

 /* MEDIAGRID CLASS DEFINITION
  * ==================== */

  var Mediagrid = function (element) {
    this.element = $(element)
  }

  Mediagrid.prototype = {

    constructor: Mediagrid

  , show: function () {
      var $this = this.element
        , $ul = $this.closest('ul:not(.dropdown-menu)')
        , selector = $this.attr('data-target')
        , $toggle = $this.attr('data-toggle')
        , previous
        , $target
        , e

      if (!selector) {
        selector = $this.attr('href')
        selector = selector && selector.replace(/.*(?=#[^\s]*$)/, '') //strip for ie7
      }

      if ( $this.parent('li').hasClass('active') ) return

      previous = $ul.find('.active:last a')[0]

      e = $.Event('show', {
        relatedTarget: previous
      })

      $this.trigger(e)

      if (e.isDefaultPrevented()) return

      $target = $(selector)
      
      $target.removeClass( ($toggle==="media-list")? "media-grid" : "media-list" ).addClass( $toggle );
      $active = $ul.find('> .active')
      $active
          .removeClass('active')
          .find('> .dropdown-menu > .active')
          .removeClass('active')
          
     $this.parent('li').addClass('active')
    }
  }


 /* MEDIAGRID PLUGIN DEFINITION
  * ===================== */

  var old = $.fn.mediagrid

  $.fn.mediagrid = function ( option ) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('mediagrid')
      if (!data) $this.data('mediagrid', (data = new Mediagrid(this)))
      if (typeof option == 'string') data[option]()
    })
  }

  $.fn.mediagrid.Constructor = Mediagrid


 /* MEDIAGRID NO CONFLICT
  * =============== */

  $.fn.mediagrid.noConflict = function () {
    $.fn.mediagrid = old
    return this
  }


 /* MEDIAGRID DATA-API
  * ============ */
  
  $(document).ready(function(){
      var activeMediagrids = {};
        if($.cookie("activeMediagrids")){
            activeMediagrids = JSON.parse($.cookie("activeMediagrids"));
            $.each(activeMediagrids, function(nid, active){
                $('#'+nid).find('[data-toggle="'+active+'"]').mediagrid('show') //Click to show the tab
            });
        }
        $.each($('.nav-mediagrid'), function(index, nav){
            var navId = $(nav).attr('id');
            if(navId){
                $(nav).find('[data-toggle="media-list"],[data-toggle="media-grid"]').bind('click', function(e) {
                    activeMediagrids[navId] = $(this).attr('data-toggle');                  
                    $.cookie("activeMediagrids", JSON.stringify(activeMediagrids, null, 2) , {
                        expires: 90, 
                        path: '/'
                    } );            
                });
            }
        });
  })
  
  $(document).on('click.tab.data-api', '[data-toggle="media-grid"], [data-toggle="media-list"]', function (e) {
    e.preventDefault()
    $(this).mediagrid('show');
  })
  
}(window.jQuery);


