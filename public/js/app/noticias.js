var btn_nuevanota = document.getElementById("nueva-nota");
var nota_container = document.getElementById("notas-container");
var select_edition = document.getElementById("select-edition");


select_edition.addEventListener("change", function (){
  establecerSeleccion(this.value)
});

function establecerSeleccion(id){
    btn_nuevanota.classList.remove("disabled");
    btn_nuevanota.href = "/infonete/articulo/crear?ide=" + id;
    request("http://localhost/infonete/articulo/list?ide=" + id);
}

function request(url){
    $.ajax({
        url: url,
        type: "GET",
        success: function (response){
            if(response){
                nota_container.innerHTML = response;
            }
        }
    });
}

function verificar(id){
   for (var i = 0; i < select_edition.children.length; i++){
       if (select_edition.children[i].value == id){
           select_edition.children[i].setAttribute("selected", true);
           establecerSeleccion(id);
       }

   }
}





