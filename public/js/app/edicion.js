var btn_state = document.querySelectorAll("a[ed-state]");
var btn_nuevaed = document.getElementById("nueva-edicion");
var ed_container = document.getElementById("edicion-container");
var select_product = document.getElementById("select-product");

btn_state.forEach(function (btn){
    btn.addEventListener("click",function (){

    });
});

function publicarDespublicar(btn){
    var url_public = "http://localhost/infonete/edicion/publicar?id=" + btn.getAttribute("id-edicion");
}

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



