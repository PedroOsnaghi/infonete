let tag = document.getElementById("tag");
let paleta = document.getElementById("paleta");

tag.addEventListener("change", function (e){
    paleta.classList.remove("bg-gray-dark");
    for(let i = 0 ; i < tag.options.length; i++){
        paleta.classList.remove("bg-gradient-"+ tag.options[i].value);
    }

    paleta.classList.add("bg-gradient-" + tag.value );
});