var btn_nuevanota = document.getElementById("nueva-nota");
var nota_container = document.getElementById("notas-container");
var select_edition = document.getElementById("select-edition");


function iniciar_lista(){
    var btn_state = document.querySelectorAll("a[nota-state]");

    btn_state.forEach(function (btn){
        btn.addEventListener("click",function (){
            enviarRevision(btn);
            console.log("click");
        });
    });

    function enviarRevision(btn){
        var id_nota = btn.getAttribute("id-nota");
        var url_review = "http://localhost/infonete/articulo/revision?id=" + id_nota;

        var label_state = document.getElementById("label-" + id_nota);


        if(!btn.classList.contains("disabled")){
            getRequest(url_review, function (response){
                console.log(response);
                if(response && response.state){
                    btn.children[0].classList.remove("mdi-send");
                    btn.children[0].classList.add("mdi-read");
                    label_state.innerHTML = response.state;
                    label_state.classList.remove("badge-edit");
                    label_state.classList.add("badge-revision");
                    btn.href = "";
                    btn.classList.add("disabled");

                }
            });

        }




    }
}



select_edition.addEventListener("change", function (){
  establecerSeleccion(this.value)
});

function establecerSeleccion(id){
    btn_nuevanota.classList.remove("disabled");
    request("http://localhost/infonete/articulo/list?ide=" + id);
}

function request(url){
    $.ajax({
        url: url,
        type: "GET",
        success: function (response){
            if(response){
                nota_container.innerHTML = response;
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
  if(select_edition.value != "0") establecerSeleccion(select_edition.value);
}







