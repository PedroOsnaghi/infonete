var btn_inciar = document.getElementById("stream");
var preview = document.getElementById("video");
var btn_grabar = document.getElementById("grabar");
var btn_stop = document.getElementById("detener");
var partes = [];
var mediaRecorder;
var stream;

btn_inciar.addEventListener("click", function (){
    navigator.mediaDevices.getUserMedia({audio : true, video : true})
        .then(iniciarStream)
        .catch(error => {
            if (error.name == "NotFoundError"){
                console.log("No se encontro ningun dispositivo de video");
            }
        });
});

btn_grabar.addEventListener("click", function (){
    grabar(stream);
});

btn_stop.addEventListener("click", function(){
   detenerGrabacion();
});

function iniciarStream(strm){
    preview.srcObject = strm;
    stream = strm;
}

function grabar(stream){
    mediaRecorder = new MediaRecorder(stream, {
        mimeType : "video/webm;codecs=h264"
    });

    mediaRecorder.start();

    mediaRecorder.ondataavailable = function (e){
        partes.push(e.data);
    };

    mediaRecorder.onstop = function (){
        alert("Finalizó la grabación");
        var blob = new Blob(partes);
        partes = [];
        publicar(blob);
    };
}

function detenerGrabacion(){
    mediaRecorder.stop();
}

function publicar(blob){
   var link = document.createElement("a");
   link.href = window.URL.createObjectURL(blob);
   link.setAttribute("download", "video.webm");
   link.style.display = "none";
   document.body.appendChild(link);
   link.click();
   link.remove();
}