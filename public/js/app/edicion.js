var btn_nuevaed = document.getElementById("nueva-edicion");
var ed_container = document.getElementById("edicion-container");
var select_product = document.getElementById("select-product");

select_product.addEventListener("change", function (){
  establecerSeleccion(this.value)
});

function establecerSeleccion(id){
    btn_nuevaed.classList.remove("disabled");
    btn_nuevaed.href = "/infonete/edicion/crear?idp=" + id;
    request("http://localhost/infonete/edicion/list?idp=" + id);
}

function request(url){
    $.ajax({
        url: url,
        type: "GET",
        success: function (response){
            if(response){
                ed_container.innerHTML = response;
            }
        }
    });
}

function verificar(id){
   for (var i = 0; i < select_product.children.length; i++){
       if (select_product.children[i].value == id){
           select_product.children[i].setAttribute("selected", true);
           establecerSeleccion(id);
       }

   }
}



