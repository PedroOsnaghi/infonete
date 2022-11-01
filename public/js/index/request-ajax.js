//Obtencion de elementos

const container = document.getElementById("main-content");

const admin_users = document.getElementById("admin-users"); //menu Gestion de usuarios


//eventos

admin_users.addEventListener('click', function (){
    show_form_ajax("http://localhost/infonete/usuario/admin");
});






function show_form_ajax(url){
    $.ajax({
        url: url,
        type: 'GET',
        success: function (response){
           if (response) {

               container.innerHTML = response;

           }
        }
    });
}