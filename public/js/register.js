(function () {
    var email = document.getElementById("email");
    var msg = document.getElementById('mail-validate-msg');

    email.addEventListener('change', function (){

        verificarMail(this.value);
    });


    function verificarMail(value){
        $.ajax({
            url: 'http://localhost/infonete/usuario/existeEmail?email=' + value,
            type: 'GET',
            success: function (response){

                if(response.email){
                   email.classList.add('invalid');
                   msg.classList.remove('valid-feedback');
                   msg.classList.add('invalid-feedback');
                   msg.innerHTML='El email ya se encuentra registrado.';
                   msg.style.display = 'block';
                }else{
                    console.log("disponible");
                }
            }
        });

    }
})()