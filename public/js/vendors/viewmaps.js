function init_Preview(){
    var position = document.getElementById("position");
    var lat = document.getElementById("lat");
    var lng = document.getElementById("lng");


    //Creamos el punto a partir de la latitud y longitud de una dirección:
    var point = new google.maps.LatLng(parseFloat(lat.value), parseFloat(lng.value));

        var map = new google.maps.Map(document.getElementById("map"), {
            center: { lat: parseFloat(lat.value), lng: parseFloat(lng.value) },
            zoom: 13,
            disableDefaultUI: true,
            mapTypeId: 'roadmap'
        });

        //Creamos el mapa y lo asociamos a nuestro contenedor
        //var map = new google.maps.Map(document.getElementById("map"),  myOptions);

        //Mostramos el marcador en el punto que hemos creado
        var marker = new google.maps.Marker({
            position:point,
            map: map,
            title: position.value
        });

}

