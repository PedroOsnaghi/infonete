let btn_inciar = document.getElementById("stream");
let sel_audio = document.getElementById("d-audio");
let sel_video = document.getElementById("d-video");
let preview = document.getElementById("video");
let btn_grabar = document.getElementById("grabar");
let btn_stop = document.getElementById("detener");
let duracion = document.getElementById("duracion");
let record_label = document.getElementById("record-label");
let duracionPlay = document.getElementById("duracion-play");
let play_label = document.getElementById("play-label");
let btn_ver = document.getElementById("ver-video");

// Variables "globales"
let tiempoInicio, mediaRecorder, idIntervalo, blobVideo;
// En el arreglo pondremos los datos que traiga el evento dataavailable del mediaRecorder
let tracks = [];

let stream;





// Consulta la lista de dispositivos de entrada de audio y llena el select
const cargarDispositivos = function () {
    navigator.mediaDevices.enumerateDevices()
        .then(dispositivos => {
            limpiarSelect(sel_audio);
            limpiarSelect(sel_video);
            dispositivos.forEach(function (dispositivo, indice){
                if (dispositivo.kind === "audioinput") {
                    const opcion = document.createElement("option");

                    opcion.text = dispositivo.label || "Micrófono " + (indice + 1);
                    opcion.value = dispositivo.deviceId;
                    sel_audio.appendChild(opcion);

                } else if (dispositivo.kind === "videoinput") {
                    const opcion = document.createElement("option");

                    opcion.text = dispositivo.label || "Cámara " + (indice + 1);
                    opcion.value = dispositivo.deviceId;
                    sel_video.appendChild(opcion);
                }
            })
        })
};

btn_inciar.addEventListener("click", function (){
    if(this.hasAttribute("iniciado")) {
        stream.getTracks().forEach(track => track.stop())
        this.textContent = "Iniciar Stream";
        this.removeAttribute("iniciado");
        btn_grabar.setAttribute("disabled", "true");
        btn_stop.setAttribute("disabled", "true");
        btn_ver.removeAttribute("disabled");
        detenerConteo(record_label);
        detenerConteo(play_label);
        preview.srcObject=null;

        return ;
    }
    if (!sel_audio.options.length) return errorMessage("No hay micrófono");
    //if (!sel_video.options.length) return errorMessage("No hay cámara");
    navigator.mediaDevices.getUserMedia({
        audio: {
            deviceId: sel_audio.value, // Indicar dispositivo de audio
        },
        video: {
            deviceId: sel_video.value, // Indicar dispositivo de vídeo
        }
    })
        .then(iniciarStream)
        .catch(error => {
            if (error){
                return errorMessage("No se pudo iniciar el stream " + error.message);
            }
        });
});

btn_ver.addEventListener("click", function (){
    if(!blobVideo) return errorMessage("No se puede previsualizar el video");
    btn_grabar.setAttribute("disabled", "true");
    preview.src = URL.createObjectURL(blobVideo);
    preview.load();
    comenzarAContar(play_label);
    preview.onended = function (){
        detenerConteo(play_label);
        preview.pause();
    }
    preview.onloadeddata = function() {
        preview.play();

    }


});

btn_grabar.addEventListener("click", function (){
    grabar(stream);
});

btn_stop.addEventListener("click", function(){
   detenerGrabacion();
});

function iniciarStream(strm){
    btn_inciar.textContent = "Terminar Stream";
    btn_inciar.setAttribute("iniciado", "true");

    btn_grabar.removeAttribute("disabled");
    preview.srcObject = strm;
    preview.play();
    stream = strm;
}

const grabar = function (strm){
    //si hay video den dataForm lo elimina
    if(formDataFile.has("video")) formDataFile.delete("video");

    //deshabilita boton ver video por si se esta sobreescribiendo
    btn_ver.setAttribute("hidden", "true");

    let options = {
        mimeType : "video/webm;codecs=h264"
    }

    if(!MediaRecorder.isTypeSupported("video/webm;codecs=h264")){
        options = {
            mimeType : "video/webm;codecs=vp8"
        }
    }



    mediaRecorder = new MediaRecorder(strm, options);

    mediaRecorder.start();

    //bloquea boton grabar y habilita el boton stop
    btn_stop.removeAttribute("disabled");
    btn_grabar.setAttribute("disabled", "true");

    comenzarAContar(record_label);
    // Escuchar cuando haya datos disponibles
    mediaRecorder.ondataavailable = function (e){
        tracks.push(e.data);
    };

    mediaRecorder.onstop = function (){


        successMessage("La grabación finalizo y el archivo esta listo para enviarse!. " +
            "           Para ver el video finalice el Stream y presione el botón \"Ver video\"");

        // Detener la cuenta regresiva
        detenerConteo(record_label);
        let videoBlob = new Blob(tracks);
        tracks = [];


        //asiga el blob generado al blob global para poder prevsualizar
        blobVideo = videoBlob
        //habilita boton ver video
        btn_ver.removeAttribute("hidden");
        btn_ver.setAttribute("disabled", "true");
        publicar(videoBlob);
    };
}

function detenerConteo (label){
    clearInterval(idIntervalo);
    tiempoInicio = null;
    duracionPlay.textContent = "00:00:00";
    duracion.textContent = "00:00:00";
    ocultarContador(label);
}

function detenerGrabacion(){
    // Detener el stream
    mediaRecorder.stop();
    //bloquea boton stop y habilita el boton grabar
    btn_grabar.removeAttribute("disabled");
    btn_stop.setAttribute("disabled", "true");
}

function publicar(blob){
    //Agrega el BinaryLargeObject FormData Principal que se enviara con el formulario
    formDataFile.append("video", blobVideo);
}

//algunas funciones utiles
const limpiarSelect = function (elemento) {
    for (let x = elemento.options.length - 1; x >= 0; x--) {
        elemento.options.remove(x);
    }
}

function segundosATiempo(numeroDeSegundos){
    let horas = Math.floor(numeroDeSegundos / 60 / 60);
    numeroDeSegundos -= horas * 60 * 60;
    let minutos = Math.floor(numeroDeSegundos / 60);
    numeroDeSegundos -= minutos * 60;
    numeroDeSegundos = parseInt(numeroDeSegundos);
    if (horas < 10) horas = "0" + horas;
    if (minutos < 10) minutos = "0" + minutos;
    if (numeroDeSegundos < 10) numeroDeSegundos = "0" + numeroDeSegundos;

    return `${horas}:${minutos}:${numeroDeSegundos}`;
};

function refrescar(){
    console.log("contando");
    duracionPlay.textContent = segundosATiempo((Date.now() - tiempoInicio) / 1000);
    duracion.textContent = segundosATiempo((Date.now() - tiempoInicio) / 1000);
}

function mostrarContador  (label){
    label.classList.add("d-flex");
    label.classList.remove("d-none");

}

function ocultarContador  (label){
    label.classList.add("d-none");
    label.classList.remove("d-flex");

}


// Ayudante para la duración; no ayuda en nada pero muestra algo informativo
function comenzarAContar (label){
    mostrarContador(label);
    tiempoInicio = Date.now();
    idIntervalo = setInterval(refrescar, 500);
}

//Borrado de stream solo para edicion
function activarBorrado() {
    const borrar = document.getElementById("borrar-strm");


    btn_inciar.setAttribute('disabled','true');
    btn_inciar.classList.add('disabled');

    borrar.addEventListener('click', function (e){


            let opt = confirm("Se eliminara el archivo Stream del servidor. confirma?");
            if(opt) {
                //AJAX
                sendRequestGET("http://localhost/infonete/articulo/eliminarStream?id=" + this.getAttribute("data-id") + "&name=" + this.getAttribute("data-name"),response => {
                    console.log(response);
                    if(response && response.success){
                        alert(response.success);
                        this.setAttribute('disabled', 'true');
                        this.classList.add('disabled');
                        preview.removeAttribute('controls');
                        preview.src = null;
                        btn_inciar.removeAttribute('disabled');
                        btn_inciar.classList.remove('disabled');
                    }else if(response.error){
                        alert(response.error);
                        return;
                    }



                });
            }



    });

}
//mensajes
function errorMessage(msg){
    let alert = document.getElementById("message-stream");
    alert.classList.remove("alert-success");
    alert.classList.add("alert-danger");
    alert.textContent = msg;
    alert.classList.remove("hidden");
    console.log(msg);
}
function successMessage(msg){
    let alert = document.getElementById("message-stream");
    alert.classList.remove("alert-danger");
    alert.classList.add("alert-success");
    alert.textContent = msg;
    alert.classList.remove("hidden");
    console.log(msg);
}




cargarDispositivos();
