"use strict";

var input_lat = document.getElementById("lat");
var input_lng = document.getElementById("lng");

function initAutocomplete() {

    var map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: -34.668544, lng: -58.5531392 },
        zoom: 15,
        disableDefaultUI: true,
        mapTypeId: 'roadmap'
    });

    // Creamos Search Box y lo linkeamos al input
    var input = document.getElementById("pac-input");

    var searchBox = new google.maps.places.SearchBox(input);

   // map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    // mostramos los resultados del Search Box en el mapa
    map.addListener("bounds_changed", function () {
        searchBox.setBounds(map.getBounds());
        var pos = map.getBounds();
        navigator.geolocation.getCurrentPosition(function (position){
           // input_lat.value = position.coords.latitude;

        });

    });

    var markers = [];
    // Listen for the event fired when the user selects a prediction and retrieve
    // more details for that place.
    searchBox.addListener("places_changed", function () {
        var places = searchBox.getPlaces();
        if (places.length == 0) {
            return;
        }
        // Clear out the old markers.
        markers.forEach(function (marker) {
            marker.setMap(null);
        });
        markers = [];
        // For each place, get the icon, name and location.
        // @ts-ignore
        var bounds = new google.maps.LatLngBounds();
        //console.log(map.getBounds());
        //console.log(bounds);
        places.forEach(function (place) {
            if (!place.geometry || !place.geometry.location) {
               // console.log("Returned place contains no geometry");
                return;
            }
            // @ts-ignore
            var icon = {
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(30, 30)
            };
            // Create a marker for each place.
            // @ts-ignore
            markers.push(
            new google.maps.Marker({
                map: map,
                icon: icon,
                title: place.name,
                position: place.geometry.location
            }));
            if (place.geometry.viewport) {
                // Only geocodes have viewport.
                bounds.union(place.geometry.viewport);
            }
            else {
                bounds.extend(place.geometry.location);
            }
            input_lat.value = place.geometry.location.lat();
            input_lng.value = place.geometry.location.lng();
        });

        map.zoom = 15;
        map.fitBounds(bounds);






    });
}
window.initAutocomplete = initAutocomplete;

