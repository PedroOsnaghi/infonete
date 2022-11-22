const items = document.querySelectorAll("li[opt]");
const sections = document.querySelectorAll("li[seccion]");
const container = document.getElementById("viewer-content");

sections.forEach(function (menu){
    menu.addEventListener('click', function (){
        console.log('click');
        seleccionar(this);
        selectionQuery(this);

    });
});



//funciones utiles
function seleccionar(item){
    items.forEach(function (item) {
       item.classList.remove('active');
    });
    item.classList.add('active');

}

function selectionQuery(menu){
    let url = menu.children[0].getAttribute('request');
    console.log(url);

    getRequest('http://localhost/infonete/' + url, function (response){
        console.log(response);
        cargarContenido(response)
            .then(res => {
                list_init();
            });

    });
}

function cargarContenido(html){

    return new Promise((resolve, reject) =>{
        container.innerHTML = html;
        console.log("cargo contenido");
        return resolve(true);
    });
}

function getRequest(url, callback){
    $.ajax({
       url: url,
       type: 'GET',
       success: callback
    });
}




function list_init(){
    const articles = document.querySelectorAll('a[article]');

    articles.forEach(function (art){
        art.addEventListener('click', function (){
            let url = this.getAttribute('request');
            getRequest('http://localhost/infonete/' + url, function (response){
                cargarContenido(response)
                    .then(resp =>{

                        init_Preview();
                    });
            });
        });
    });
}

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




