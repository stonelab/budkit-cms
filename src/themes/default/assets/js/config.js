/**
 * Created by livingstonefultang on 19/12/2013.
 */

// Require JS Configuration
requirejs.config({
  "paths": {
    // "masonry": "vendor/masonry/masonry.pkgd.min",
    "jquery" : "vendor/jquery/jquery.min",
    "jquery.ui" : "vendor/jquery/jquery-ui.min",
    "mes" : "vendor/mes/mediaelement-and-player.min",
    "async" : 'vendor/requirejs/async',
    "dropzone" : 'vendor/ghost/dropzone',
    "jquery.cookie" : "vendor/cookie/jquery.cookie",
    "jquery.chosen" : "vendor/chosen/chosen.jquery.min",
    "jquery.bridget": "vendor/bridget/jquery.bridget",
    "ghostdown": "vendor/ghost/ghostdown",
    "jquery.ghostdown": "vendor/ghost/jquery.ghostdown",
    // "jquery.fullcalendar": "vendor/fullcalendar/fullcalendar",
    "jquery.bootstrap": "vendor/bootstrap/bootstrap.min",
    "bootstrap.flat-ui": "vendor/flat-ui/flat-ui.min",
    "bootstrap.typeahead": "vendor/bootstrap/typeahead",
    "bootstrap.typeahead.addresspicker": "vendor/bootstrap/typeahead-addresspicker",
    "bootstrap.suppernote":'vendor/summernote/summernote.min',
    //"jquery.budkit": "vendor/budkit/budkit",
    //"jquery.validate": "vendor/validate/jquery.validate.min",
    //"budkit.uploader" : "vendor/budkit/budkit-uploader",
    //"budkit.chat" : "vendor/budkit/budkit-chat",

    "budkit.editor" : "vendor/budkit/budkit-editor",

    //"budkit.modal" : "vendor/budkit/budkit-modal",
    "budkit.map" : "vendor/budkit/budkit-map",
    "google.maps.api" : 'vendor/google/googlemaps',
    "google.maps" : "vendor/google/gmaps",
    "google.prettify":"//cdn.rawgit.com/google/code-prettify/master/loader/run_prettify",

    "bootstrap.summernote.highlight":"vendor/summernote/summernote-ext-highlight"
  },
  shim: {
    'jquery': {
      exports: '$'
    },
    'jquery.ui' : ['jquery'],
    'ghostdown' : ['jquery','dropzone'],
    'mes' : ['jquery'],
    'jquery.chosen':    ['jquery'],
    "jquery.ghostdown": ['ghostdown', 'jquery', 'jquery.ui'],
    'jquery.cookie': ['jquery'],
    'jquery.bridget': ['jquery'],
    'jquery.fullcalendar': ['jquery'],
    'jquery.bootstrap': ['jquery'],
    'bootstrap.flat-ui': ['jquery'],
    'bootstrap.summernote': ['jquery'],
    "google.maps": {
      deps: ["jquery", "google.maps.api"],
      exports: "GMaps"
    },
    "bootstrap.typeahead":{
      exports: "Bloodhound"
    },
    "bootstrap.typeahead.addresspicker":{
      deps: ["jquery","bootstrap.typeahead"],
      exports: "AddressPicker"
    },
    //'jquery.budkit': ['jquery'],
    //'jquery.validate': ['jquery'],
    //'budkit.uploader': ['jquery'],
    'budkit.editor': ['jquery'],
    "bootstrap.summernote.highlight": ['jquery']
    //'budkit.modal': ['jquery'],
    //'budkit.chat': ['jquery'],
    //'budkit.map': ['jquery']
  }
});