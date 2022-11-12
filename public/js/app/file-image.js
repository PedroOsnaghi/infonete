
    var file = document.getElementById('file');

    var form = document.getElementById("form-noticia");

    var scroller = document.getElementById("img-scroller");

    var text_empty = document.getElementById("text-empty");

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

    form.addEventListener("submit", function (e){
        e.preventDefault();


        //obtener contenido de tiny
        tinymce.activeEditor.save();

        var myContent = tinymce.activeEditor.getContent();

        console.log(myContent);

        tinymce.get("text-content").setContent(myContent);


        var formDataMain = new FormData(e.target);


        if (formDataMain.has("file[]")){
            formDataMain.delete("file[]");
        }



        var files = [];

        for (var pair of formDataFile.entries()) {

            files.push(pair[1]);
        }

        console.log(files);

        files.forEach(function (file){
            formDataMain.append("file[]", file);
        });
        var data = Object.fromEntries(formDataMain);
        //console.log(JSON.stringify(data));

        for (var pair of formDataMain.entries()) {
            console.log(pair);


        }

        fetch("http://localhost/infonete/articulo/guardar", {
            method: "POST",
            body: formDataMain
        })
            .then(function(resp){
                console.log(resp);
            })
            .catch(function (err){
                console.log(err);
            })




    });

