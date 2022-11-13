
var btn_nuevaed = document.getElementById("nueva-edicion");
var ed_container = document.getElementById("edicion-container");
var select_product = document.getElementById("select-product");

function iniciar_lista(){
    var btn_state = document.querySelectorAll("a[ed-state]");

    btn_state.forEach(function (btn){
        btn.addEventListener("click",function (){
            publicarDespublicar(btn);
            console.log("click");
        });
    });

    function publicarDespublicar(btn){
        var id_edicion = btn.getAttribute("id-edicion");
        var url_public = "http://localhost/infonete/edicion/publicar?id=" + id_edicion;
        var url_despublic = "http://localhost/infonete/edicion/despublicar?id=" + id_edicion;
        var label_state = document.getElementById("label-" + id_edicion);
        var label_date = document.getElementById("date-" + id_edicion);

        if(btn.getAttribute("state") == "0"){
            console.log("entro en publicar");
            getRequest(url_public, function (response){
                console.log(response);
                if(response && response.publicado){
                    btn.setAttribute("state",response.publicado);
                    btn.children[0].classList.remove("mdi-earth");
                    btn.children[0].classList.add("mdi-earth-off");
                    label_state.innerHTML = "Publicada";
                    label_state.classList.remove("badge-edit");
                    label_state.classList.add("badge-success");
                    label_date.innerHTML = response.date;

                }
            });
        }else{
            console.log("entro en despublicar");
            getRequest(url_despublic, function (response){
                console.log(response);
                if(response && !response.publicado){
                    btn.setAttribute("state",response.publicado);
                    btn.children[0].classList.remove("mdi-earth-off");
                    btn.children[0].classList.add("mdi-earth");
                    label_state.innerHTML = "Edición";
                    label_state.classList.remove("badge-success");
                    label_state.classList.add("badge-edit");
                    label_date.innerHTML = "no-publicado";

                }
            });
        }



    }
}





select_product.addEventListener("change", function (){
  establecerSeleccion(this.value)
});

function establecerSeleccion(id){
    btn_nuevaed.classList.remove("disabled");
    request("http://localhost/infonete/edicion/list?idp=" + id);
}

function request(url){
    $.ajax({
        url: url,
        type: "GET",
        success: function (response){
            if(response){
                ed_container.innerHTML = response;
                iniciar_lista();
            }
        }
    });
}

function getRequest(url, callback){
    $.ajax({
        url: url,
        type: "GET",
        success: callback
    });
}

function verificarSeleccion(){
  if(select_product.value != "0") establecerSeleccion(select_product.value);
}



