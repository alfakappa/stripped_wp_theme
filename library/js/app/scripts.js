/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can
 * always reference jQuery with $, even when in .noConflict() mode.
 *
 * Google CDN, Latest jQuery
 * To use the default WordPress version of jQuery, go to lib/config.php and
 * remove or comment out: add_theme_support('jquery-cdn');
 * ======================================================================== */

(function($) {
var Roots;
    var responsive_viewport = $(window).width(); //if you want to use responsive JS
var nua = navigator.userAgent;
var is_android = ((nua.indexOf('Mozilla/5.0') > -1 && nua.indexOf('Android ') > -1 &&     nua.indexOf('AppleWebKit') > -1) && !(nua.indexOf('Chrome') > -1));
// Use this variable to set up the common and page specific functions. If you
// rename this variable, you will also need to rename the namespace below.
Roots = {
    // All pages
    common: {
        init: function () {
            // Deferred loading example with grunticon
            // $.when(
            //     $.cachedScript("/wp-content/themes/stripped/images/dest/loader.js"),
            //     $.Deferred(function (deferred) {
            //         $(deferred.resolve);
            //     })
            // ).done(function () {
            //         grunticon(["/wp-content/themes/stripped/images/dest/icons.data.svg.css", "/wp-content/themes/stripped/images/dest/icons.data.png.css", "/wp-content/themes/stripped/images/dest/icons.fallback.css"], grunticon.svgLoadedCallback);
            // });
          clickableContainer();
          smoothScrollLinks();
        }
    },
    // Home page
    home: {
        init: function () {
            // JavaScript to be fired on the home page
        }
    }
    // Add specific pages for specific js loading
};

// Functions
function clickableContainer() {
    // Make whole div clickable
    $( ".clickable" ).click( function () {
        window.location = $( this ).find( "a" ).attr( "href" );
        return false;
    } );
}
//Function to scroll to section that belongs to the link
function smoothScrollLinks() {
    $('.smoothscroll').on('click', function(event){
        event.preventDefault();
        $(this).stop();
        if($(this).attr('href') !== '' ) {
            smoothScroll($(this.hash));
        }
    });
}
function smoothScroll(target) {
    if($.browser.mozilla === true) {
        $('body,html').stop();
        $('body,html').animate(
          {'scrollTop':target.offset().top-50
        },
          600
        );
    // if ie use html
    } else if($.browser.msie === true) {
      $('html').stop();
      $('html').animate({
          'scrollTop':target.offset().top-80
        },600
      );
    } else {
        $('body').stop();
        $('body').animate(
          {'scrollTop':target.offset().top-80},
          600
        );
    }
}
// The routing fires all common scripts, followed by the page specific scripts.
// Add additional events for more control over timing e.g. a finalize event
var UTIL = {
  fire: function(func, funcname, args) {
    var namespace = Roots;
    funcname = (funcname === undefined) ? 'init' : funcname;
    if (func !== '' && namespace[func] && typeof namespace[func][funcname] === 'function') {
      namespace[func][funcname](args);
    }
  },
  loadEvents: function() {
    UTIL.fire('common');

    $.each(document.body.className.replace(/-/g, '_').split(/\s+/),function(i,classnm) {
      UTIL.fire(classnm);
    });
  }
};

$(document).ready(UTIL.loadEvents);

// getScript with cache - automatically caches scripts
// http://api.jquery.com/jquery.getscript/
jQuery.cachedScript = function (url, options) {

    // Allow user to set any option except for dataType, cache, and url
    options = $.extend(options || {}, {
        dataType: "script",
        cache: true,
        url: url
    });

    // Use $.ajax() since it is more flexible than $.getScript
    // Return the jqXHR object so we can chain callbacks
    return jQuery.ajax(options);
};

})(jQuery); // Fully reference jQuery after this point.
