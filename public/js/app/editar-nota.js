const input_yt = document.getElementById("inYt");
const file = document.getElementById('file');
const form = document.getElementById("form-noticia");
const scroller = document.getElementById("img-scroller");
const text_empty = document.getElementById("text-empty");
const btn_guardar = document.getElementById("btn-save");

const seccion = document.getElementById("seccion");
const titulo = document.getElementById("titulo");
const subtitulo = document.getElementById("subtitulo");
const ubicacion = document.getElementById("pac-input");

let formDataFile = new FormData();
let deletedFiles = [];

//***********************ARCHIVOS***************************************//
file.addEventListener('change', function (e) {

    for ( var i = 0; i < file.files.length; i++ ) {
        var thumbnail_id = Math.floor( Math.random() * 30000 ) + '_' + Date.now();
        createThumbnail(file, i, thumbnail_id);
        formDataFile.append(thumbnail_id, file.files[i]);
    }

    e.target.value = '';

});


var createThumbnail = function (file, iterator, thumbnail_id) {

    text_empty.setAttribute("hidden", true);

    var thumbnail = document.createElement('div');

    thumbnail.classList.add('thumbnail', thumbnail_id);
    thumbnail.dataset.id = thumbnail_id;

    thumbnail.setAttribute('style', `background-image: url(${ URL.createObjectURL( file.files[iterator] ) })`);
    document.getElementById('img-scroller').appendChild(thumbnail);
    createCloseButton(thumbnail_id);
}

var createCloseButton = function (thumbnail_id) {
    var closeButton = document.createElement('div');
    closeButton.classList.add('close-button');
    closeButton.innerText = 'x';
    document.getElementsByClassName(thumbnail_id)[0].appendChild(closeButton);
}


scroller.addEventListener('click', function (e) {
    if ( e.target.classList.contains('close-button') ) {
        //archivo que se elimina ya existia en el servidor

        if(e.target.parentNode.hasAttribute("file-id")){
            let opt = confirm("Se eliminara el archivo del servidor. confirma?");
            if(opt) {
                //AJAX
                sendRequestGET("http://localhost/infonete/articulo/eliminarImagen?id=" + e.target.parentNode.getAttribute("art-id") + "&name=" + e.target.parentNode.getAttribute("file-id"),response => {
                    console.log(response);
                    if(response && response.success){
                        alert(response.success);
                        e.target.parentNode.remove();
                    }else if(response.error){
                        alert(response.error);
                        return;
                    }



                });
            }
        }



        //archivo agregado y eliminado
        if (formDataFile.has(e.target.parentNode.dataset.id)){
            e.target.parentNode.remove();
            formDataFile.delete(e.target.parentNode.dataset.id);
        }
    }
});







//************************SUBMIT***************************************//
form.addEventListener("submit", function (e) {
    e.preventDefault();
    e.stopPropagation();

    if (validarForm()){

        //obtener contenido de tiny y colocarlo en textarea
        tinymce.activeEditor.save();

        var myContent = tinymce.activeEditor.getContent();

        console.log(myContent);

        tinymce.get("text-content").setContent(myContent);

        //obtenemos los datos del formulario
        let formDataMain = new FormData(e.target);


        if (formDataMain.has("file[]")) {
            formDataMain.delete("file[]");
        }


        for (var pair of formDataFile.entries()) {
            if (pair[0] == "video")
                formDataMain.append(pair[0], pair[1]);
            else
                formDataMain.append("file[]", pair[1]);
        }



        //VER POR CONSOLA LOS DATOS ENVIADOS
        var obj = Object.fromEntries(formDataMain);

        console.log(JSON.stringify(obj));


        //AJAX
        sendRequest("http://localhost/infonete/articulo/actualizar?id=" + this.name, formDataMain,  response => {
            console.log(response);
            if(response && response.success){
                console.log(response.success);
                formDataMain = null;
                formDataFile = null;
                success(response.success);
                deshabilitarBoton();
            } else{
                console.log(response.error);
                error("No se realizaron cambios en el Articulo");
            }

        });



    }


});


function validarForm(){
    if(seccion.value === "0") {
        seccion.focus();
        return error("Seleccione la Seccion de la Noticia");
    }

    if(titulo.value == ""){
        titulo.focus();
        return error("Debe especificar un titulo");
    }

    if(subtitulo.value == ""){
        subtitulo.focus();
        return error("Debe especificar un Subtitulo");
    }

    if (ubicacion.value == ""){
        ubicacion.focus();
        return error("Debe especificar una ubicacion en el mapa");
    }

    return true;
}



function sendRequest(url, data, callback){
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        success: callback
    });
}

function sendRequestGET(url, callback){
    $.ajax({
        url: url,
        type: "GET",
        success: callback
    });
}


function success(msg){
    var msg_container = document.getElementById("message");

    msg_container.classList.remove("alert-danger");
    msg_container.classList.add("alert-success");
    msg_container.classList.remove("hidden");
    msg_container.innerHTML = msg;
    msg_container.scrollIntoView();
}

function error(msg){
    var msg_container = document.getElementById("message");

    msg_container.classList.remove("alert-success");
    msg_container.classList.add("alert-danger");
    msg_container.classList.remove("hidden");
    msg_container.innerHTML = msg;
    msg_container.scrollIntoView();
}

function deshabilitarBoton(){
    btn_guardar.classList.add("disabled");
    btn_guardar.setAttribute("disabled", "true");
}


input_yt.onpaste = function (e){

    e.preventDefault();
    var urlArr = e.clipboardData.getData('text/plain').split("/");
    var id_yt = urlArr.slice(-1);


    this.value = id_yt;

    document.getElementById("yt-preview").setAttribute("src", "https://www.youtube.com/embed/" + id_yt);
}


//validaciones

