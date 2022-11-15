
    var file = document.getElementById('file');

    var form = document.getElementById("form-noticia");

    var scroller = document.getElementById("img-scroller");

    var text_empty = document.getElementById("text-empty");

    var btn_guardar = document.getElementById("btn-save");

    var formDataFile = new FormData();











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
            e.target.parentNode.remove();
            formDataFile.delete(e.target.parentNode.dataset.id);
        }
    });

    form.addEventListener("submit", function (e) {
        e.preventDefault();


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

        var obj = Object.fromEntries(formDataMain);

        console.log(JSON.stringify(obj));


        sendRequest("http://localhost/infonete/articulo/guardar", formDataMain,  response => {
            console.log(response);
            if(response && response.success){
                console.log(response.success);
                formDataMain = null;
                formDataFile = null;
                success(response.success);
                deshabilitarBoton();
            } else{
                console.log(response.error);
                error(response.error);
            }

        });
    });

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