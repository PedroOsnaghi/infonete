(function () {
    var email = document.getElementById("email");

    email.addEventListener('change', function (){
        ajax();
    });


    function ajax(){
        $.ajax({
            url: 'http://localhost/infonete/usuario/existeEmail?email=' + email.value,
            type: 'GET',
            success: function (response){
                console.log(response);
            }
        });

    }
})()