//Obtencion de elementos
$(function users_init(){
    const btn_roles = document.getElementById("drop-roles");
    const rol_options = document.querySelectorAll("a[rol-option]");

    console.log("cargado users.js");



//eventos
    btn_roles.addEventListener('rolchange' ,function (e){
        console.log("evento rolchange");
        this.innerHTML = e.name;
    });


    rol_options.forEach(function (opt){

        opt.addEventListener('click', function (e){
            e.preventDefault();
            console.log("hola");
            if (opt.hasAttribute("selected"))
                rolChangeEvent(opt);
        });



    });

    function rolChangeEvent(element){
        element.dispatchEvent(new customEvent('rolchange', {
            name: element.innerHTML
        }));
    }
users_init();
});
