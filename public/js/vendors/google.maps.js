

var input_lat = document.getElementById("lat");
var input_lng = document.getElementById("lng");



var dirmsg = document.getElementById('dir-validate-msg');
// Creamos Search Box y lo linkeamos al input
var input = document.getElementById("pac-input");

function valid(input, msg){
    input.classList.remove('invalid');

    msg.classList.remove('invalid-feedback');
    msg.classList.add('valid-feedback');
    msg.innerHTML=' <i class="fa-solid fa-check me-2"></i></i> Se geo-posicionó correctamente.';
    msg.style.display = 'block';
}
function invalid(input, msg){
    input.classList.add('invalid');

    msg.classList.remove('valid-feedback');
    msg.classList.add('invalid-feedback');
    msg.innerHTML=' <i class="fa-solid fa-circle-exclamation me-2"></i> Seleccione su direccion de la lista desplegable.';
    msg.style.display = 'block';
}






function initAutocomplete() {

    var map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: -34.668544, lng: -58.5531392 },
        zoom: 15,
        disableDefaultUI: true,
        mapTypeId: 'roadmap'
    });


    var searchBox = new google.maps.places.SearchBox(input);



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
            input_lat.classList.add('text-dark-gray');
            input_lng.classList.add('text-dark-gray');
            valid(input, dirmsg);
        });

        map.zoom = 15;
        map.fitBounds(bounds);






    });


}






input.addEventListener("change", function (){

    if(lat.value == '' && lng.value == ''){
        invalid(this, dirmsg);
    }else{
        valid(this, dirmsg);
    }
});
window.initAutocomplete = initAutocomplete;

