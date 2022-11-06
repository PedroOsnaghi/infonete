var pass = document.getElementById("pass"); //input password
var passrpt = document.getElementById("pass-rpt"); //input repeat password


//boton toggle que muestra y oculta password
var passview = document.querySelectorAll("button[pass-view]");

//listener pass-view
passview.forEach(function (btn){
    btn.addEventListener('click', function (){
        //vincula el boton a un input
        var i_pass = document.getElementById(this.getAttribute('for'));

        //toggle
        if(this.classList.contains("view")){
            this.innerHTML = '<i class="mdi mdi-eye"></i>';
            this.classList.remove('view');
            this.classList.add('non-view');
            this.setAttribute('title', 'ocultar contraseña');
            i_pass.setAttribute('type', 'text');

        }else{
            this.innerHTML = '<i class="mdi mdi-eye-off"></i>';
            this.classList.remove('non-view');
            this.classList.add('view');
            i_pass.setAttribute('type', 'password');
            this.setAttribute('title', 'mostrar contraseña');
        }
    });
});