(function () {
    const email = document.getElementById("email");
    const pass = document.getElementById("pass");
    const passrpt = document.getElementById("pass-rpt");
    const form = document.getElementById("form");

    const email_msg = document.getElementById('mail-validate-msg');
    const pass_msg = document.getElementById('pass-validate-msg');



    //validacion password y repetir iguales
    pass.addEventListener('change', function (){
       if (passrpt.value !== '')
            (pass.value != passrpt.value) ? invalid(this, pass_msg, 'Las contraseñas no coinciden') :
                valid(this, pass_msg, 'Las contraseñas coinciden');
    });

    passrpt.addEventListener('change', function (){
       if (pass.value !== '')
            (pass.value != passrpt.value) ? invalid(this, pass_msg, 'Las contraseñas no coinciden') :
                valid(this, pass_msg, 'Las contraseñas coinciden');
    });


    //cancelqar submit si hay error
    form.addEventListener('submit', function (e){
        e.preventDefault();
        e.stopPropagation();

        if (document.querySelectorAll(".invalid-feedback").length == 0){
            
            this.submit()
        }
    });





    email.addEventListener('change', function (){
        verificarMail(this.value);
    });


    function verificarMail(value){
        $.ajax({
            url: 'http://localhost/infonete/usuario/existeEmail?email=' + value,
            type: 'GET',
            success: function (response){
                (response && response.email ) ?
                    invalid(email, email_msg, 'El email ya se encuentra registrado') :
                    valid(email, email_msg, 'El email se encuentra disponible');
            }
        });

    }

    function invalid(input, msg_container, msg_text){
        input.classList.add('invalid');
        msg_container.classList.remove('valid-feedback');
        msg_container.classList.add('invalid-feedback');
        msg_container.innerHTML=' <i class="fa-solid fa-circle-exclamation me-2"></i>';
        msg_container.innerHTML += msg_text;
        msg_container.style.display = 'block';
    }

    function valid(input, msg_container, msg_text){
        input.classList.remove('invalid');
        msg_container.classList.remove('invalid-feedback');
        msg_container.classList.add('valid-feedback');
        msg_container.innerHTML=' <i class="fa-solid fa-check me-2"></i></i>';
        msg_container.innerHTML += msg_text;
        msg_container.style.display = 'block';
    }
})()