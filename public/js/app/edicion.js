var btn_nuevaed = document.getElementById("nueva-edicion");
var ed_container = document.getElementById("edicion-container");
var select_product = document.getElementById("select-product");

select_product.addEventListener("change", function (){
    btn_nuevaed.classList.remove("disabled");
    btn_nuevaed.href = "/infonete/edicion/crear?idp=" + this.value;
    request("http://localhost/infonete/edicion/list?idp=" + this.value);
});

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