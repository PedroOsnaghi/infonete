const btn_aprobar = document.getElementById("aprobar");
const btn_draft = document.getElementById("draft");
const message = document.getElementById("message");
const estado = document.getElementById("estado");



btn_aprobar.addEventListener('click', function (){
    aprobarNota(this);
});

btn_draft.addEventListener('click', function (){
    enviarDraft(this);
});

function getRequest(url, callback){
    $.ajax({
        url: url,
        type: "GET",
        success: callback
    });
}


function aprobarNota(btn){

    getRequest("http://localhost/infonete/articulo/aprobar?id=" + btn.getAttribute("id-nota"), function (resp){

            if(resp && resp.state){
                estado.innerHTML = resp.state;
                message.classList.remove("hidden");
            }else{
                message.classList.remove("hidden");
                message.innerHTML = "No se realizaron cambios en el estado";
            }


    });

}


function enviarDraft(btn){

    getRequest("http://localhost/infonete/articulo/draft?id=" + btn.getAttribute("id-nota"), function (resp){

        if(resp && resp.state){
            estado.innerHTML = resp.state;
            message.classList.remove("hidden");
        }else{
            message.classList.remove("hidden");
            message.innerHTML = "No se realizaron cambios en el estado";
        }


    });

}





