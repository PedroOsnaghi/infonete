
var btn_nuevasec = document.getElementById("nueva-seccion");
var sec_container = document.getElementById("seccion-container");


function iniciar_lista() {


    function request(url) {
        $.ajax({
            url: url,
            type: "GET",
            success: function (response) {
                if (response) {
                    ed_container.innerHTML = response;
                    iniciar_lista();
                }
            }
        });
    }

    function getRequest(url, callback) {
        $.ajax({
            url: url,
            type: "GET",
            success: callback
        });
    }

}



