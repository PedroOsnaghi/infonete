(function () {
    var email = document.getElementById("email");
    var msg = document.getElementById('mail-validate-msg');
    var passmsg = document.getElementById('pass-validate-msg');

    var pass = document.getElementById("pass");
    var passrpt = document.getElementById("pass-rpt");
    var form = document.getElementById("form");
    var lat = document.getElementById("lat");
    var lng = document.getElementById("lng");


    var passview = document.querySelectorAll("button[pass-view]");

    //listener pass-view
    passview.forEach(function (btn){
        btn.addEventListener('click', function (){
            var i_pass = document.getElementById(this.getAttribute('for'));
            if(this.classList.contains("view")){
                this.innerHTML = '<i class="fa-solid fa-eye-slash"></i>';
                this.classList.remove('view');
                this.classList.add('non-view');
                this.setAttribute('title', 'ocultar contraseña');
                i_pass.setAttribute('type', 'text');

            }else{
                this.innerHTML = '<i class="fa-solid fa-eye"></i>';
                this.classList.remove('non-view');
                this.classList.add('view');
                i_pass.setAttribute('type', 'password');
                this.setAttribute('title', 'mostrar contraseña');
            }
        });
    });

    //validacion pass
    pass.addEventListener('change', function (){
        if( passrpt.value != ''){
            if(pass.value != passrpt.value){
                pass_invalid(this,passmsg);
            }else{
                pass_valid(this, passmsg);
            }
        }
    });

    passrpt.addEventListener('change', function (){
        if(pass.value != ''){
            if(pass.value != passrpt.value){
                invalid(this,passmsg);
            }else{
                valid(this, passmsg);
            }
        }
    });

    function invalid(input, msg){
        input.classList.add('invalid');
        msg.classList.remove('valid-feedback');
        msg.classList.add('invalid-feedback');
        msg.innerHTML=' <i class="fa-solid fa-circle-exclamation me-2"></i> Las contraseñas no coinciden.';
        msg.style.display = 'block';
    }

    function valid(input, msg){
        input.classList.remove('invalid');
        msg.classList.remove('invalid-feedback');
        msg.classList.add('valid-feedback');
        msg.innerHTML=' <i class="fa-solid fa-check me-2"></i></i> Ambas contraseñas coinciden.';
        msg.style.display = 'block';
    }

    form.addEventListener('submit', function (e){
        e.preventDefault();
        e.stopPropagation();

        if (document.querySelectorAll(".invalid-feedback").length == 0){
            this.submit();
        }


    });





    lat.addEventListener("change", function (){
        console.log("cambio desde blur");
        if(lat.value == '' && lng.value == ''){
            invalid(this, dirmsg);
        }else{
            valid(this, dirmsg);
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

                if(response && response.email ){
                   email.classList.add('invalid');
                   msg.classList.remove('valid-feedback');
                   msg.classList.add('invalid-feedback');
                   msg.innerHTML=' <i class="fa-solid fa-circle-exclamation me-2"></i> El email ya se encuentra registrado.';
                   msg.style.display = 'block';
                }else{
                    email.classList.remove('invalid');
                    msg.classList.remove('invalid-feedback');
                    msg.classList.add('valid-feedback');
                    msg.innerHTML=' <i class="fa-solid fa-check me-2"></i></i> El email se encuentra disponible.';
                    msg.style.display = 'block';
                }
            }
        });

    }
})()