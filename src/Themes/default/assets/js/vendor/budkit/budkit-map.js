/**
 * Created by livingstonefultang on 18/09/15.
 */

require(["jquery", "bootstrap.typeahead.addresspicker", "google.maps"], function($, AddressPicker, GMaps) {


        var gmap;
        var mapStyles = [{
            "featureType": "landscape.man_made",
            "elementType": "geometry",
            "stylers": [{"color": "#f7f1df"}]
        }, {
            "featureType": "landscape.natural",
            "elementType": "geometry",
            "stylers": [{"color": "#d0e3b4"}]
        }, {
            "featureType": "landscape.natural.terrain",
            "elementType": "geometry",
            "stylers": [{"visibility": "off"}]
        }, {
            "featureType": "poi",
            "elementType": "labels",
            "stylers": [{"visibility": "off"}]
        }, {
            "featureType": "poi.business",
            "elementType": "all",
            "stylers": [{"visibility": "off"}]
        }, {
            "featureType": "poi.medical",
            "elementType": "geometry",
            "stylers": [{"color": "#fbd3da"}]
        }, {
            "featureType": "poi.park",
            "elementType": "geometry",
            "stylers": [{"color": "#bde6ab"}]
        }, {
            "featureType": "road",
            "elementType": "geometry.stroke",
            "stylers": [{"visibility": "off"}]
        }, {
            "featureType": "road",
            "elementType": "labels",
            "stylers": [{"visibility": "off"}]
        }, {
            "featureType": "road.highway",
            "elementType": "geometry.fill",
            "stylers": [{"color": "#ffe15f"}]
        }, {
            "featureType": "road.highway",
            "elementType": "geometry.stroke",
            "stylers": [{"color": "#efd151"}]
        }, {
            "featureType": "road.arterial",
            "elementType": "geometry.fill",
            "stylers": [{"color": "#ffffff"}]
        }, {
            "featureType": "road.local",
            "elementType": "geometry.fill",
            "stylers": [{"color": "black"}]
        }, {
            "featureType": "transit.station.airport",
            "elementType": "geometry.fill",
            "stylers": [{"color": "#cfb2db"}]
        }, {"featureType": "water", "elementType": "geometry", "stylers": [{"color": "#a2daf2"}]}]
        // jQuery code is in here
        $(document).ready(function () {
            //prettyPrint();
            gmap = new GMaps({
                //scrollwheel: false,
                navigationControl: false,
                mapTypeControl: false,
                //scaleControl: false,
                //draggable: false,
                div: '#map',
                lat: -12.043333,
                lng: -77.028333
            });

            gmap.addStyle({
                styledMapName: "Styled Map",
                styles: mapStyles,
                mapTypeId: "map_style"
            });

            gmap.setStyle("map_style");

            GMaps.geolocate({
                success: function (position) {
                    gmap.setCenter(position.coords.latitude, position.coords.longitude);
                },
                error: function (error) {
                    //alert('Geolocation mtlfailed: '+error.message);
                },
                not_supported: function () {
                    //alert("Your browser does not support geolocation");
                },
                always: function () {
                    //alert("Done!");
                }
            });

            var addressPicker = new AddressPicker();

            $('.places-search').typeahead(null, {
                displayKey: 'description',
                source: addressPicker.ttAdapter()
            });

            addressPicker.bindDefaultTypeaheadEvent($('.places-search'));
            $(addressPicker).on('addresspicker:selected', function (event, result) {
                gmap.setCenter(result.lat(), result.lng());
            });
        });

        $( window ).resize(function() {
            $("#map").css("height", "100%");
            gmap.refresh();
        });
});