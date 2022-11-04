//Obtencion de elementos
function view_init(){

    var btn_roles = document.querySelectorAll("button[rol-name]");
    var rol_options = document.querySelectorAll("a[rol-option]");
    var btn_lock = document.querySelectorAll("a[user-lock]");
    var btn_blank = document.querySelectorAll("a[blank-pass]");
    var btn_edit = document.querySelectorAll("a[edit]");



    //peticion al Servidor
    function getRequest(url, callback) {
        $.ajax({
            url: url,
            type: 'GET',
            success: callback
        });
    }


//eventos

    btn_lock.forEach(function (btn){
        btn.addEventListener('click', function (e){
            console.log("click");
            bloquearDesbloquear(this);
        });
    });


    function bloquearDesbloquear(btn){
        var url_lock = "http://localhost/infonete/usuario/bloquear?id=" + btn.getAttribute("id-user");
        var url_unlock = "http://localhost/infonete/usuario/desbloquear?id=" + btn.getAttribute("id-user");
        var label_state = document.getElementById(btn.getAttribute("id-user"));

        if(btn.getElementById("state") == "lock"){

            getRequest(url_lock, function (response) {
               if(response){
                   btn.setAttribute("state", "unlock");
                   btn.children[0].classList.remove("mdi-account-off")
                   btn.children[0].classList.add("mdi mdi-account-check");
                   btn.setAttribute("title", "Desbloquear usuario");
                   label_state.innerHTML = "Inactivo";
                   label_state.classList.remove("badge-success");
                   label_state.classList.add("badge-danger");
               }

            });
        }else {
            getRequest(url_unlock, function (response) {
                if (response) {
                    btn.setAttribute("state", "lock");
                    btn.children[0].classList.remove("mdi mdi-account-check");
                    btn.children[0].classList.add("mdi-account-off")
                    btn.setAttribute("title", "Bloquear usuario");
                    label_state.innerHTML = "Activo";
                    label_state.classList.remove("badge-danger");
                    label_state.classList.add("badge-success");
                }

            });
        }




    }


    btn_roles.forEach(function (btn){
        btn.addEventListener('rolchange' ,function (e){
            console.log("evento rolchange");
            this.innerHTML = e.detail.name;
            this.setAttribute("rol-sel", e.detail.id);

            var url = "http://localhost/infonete/usuario/setRol?id=" + this.getAttribute("id") + "&rol=" + this.getAttribute("rol-sel");

            getRequest(url, function (response){
                if (response) setearEstadoRol(btn, e.detail.name);
            });




        });
    });

    function setearEstadoRol(btn, rol){
        btn.classList.remove("lector","editor","redactor","administrador");

        btn.classList.add(rol.toLowerCase());
    }

    rol_options.forEach(function (opt){
        var id_selected = document.getElementById(opt.getAttribute("for")).getAttribute("rol-sel");
        var rol_iterado = opt.getAttribute("value");

        opt.removeAttribute("selected");

        if(id_selected == rol_iterado)
        {
            document.getElementById(opt.getAttribute("for")).innerHTML = opt.innerHTML;
            opt.setAttribute("selected", "true");
            setearEstadoRol(document.getElementById(opt.getAttribute("for")),opt.innerHTML)
        }


        opt.addEventListener('click',
            function (e) {
                    rolChangeEvent(document.getElementById(this.getAttribute("for")),this);
            });



    });



    function rolChangeEvent(element, dispatcher){

        element.dispatchEvent(new CustomEvent('rolchange', {
            detail:{name: dispatcher.innerHTML,
                    id: dispatcher.getAttribute("value")}
        }));
    }


    //busqueda de usuarios
   const searchUser = document.getElementById("search-user");
    const searchUbox = document.getElementById("search-user-box");




//eventos

    searchUser.addEventListener('submit', function (e){
        e.preventDefault();
        e.stopPropagation();

        show_form_ajax("http://localhost/infonete/usuario/search?value=" + searchUbox.value);

    });




}
