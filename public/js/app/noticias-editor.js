
var nota_container = document.getElementById("notas-container");
var select_edition = document.getElementById("select-edition");






select_edition.addEventListener("change", function (){
  establecerSeleccion(this.value)
});

function establecerSeleccion(id){

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



function verificarSeleccion(){
  if(select_edition.value != "0") establecerSeleccion(select_edition.value);
}







