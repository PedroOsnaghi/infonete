var tabs_items = document.querySelectorAll("a[tab-menu]");


tabs_items.forEach(function (item){
    item.addEventListener('click', function (e){
        e.preventDefault();
        limpiar_items();
        this.classList.add("active");
        console.log(this.getAttribute("ref"));
        document.querySelector(this.getAttribute("ref")).classList.remove("tab-pane");
        document.querySelector(this.getAttribute("ref")).classList.add("active");

    });
});

var limpiar_items = function (){
    tabs_items.forEach(function (item){
        if (item.classList.contains("active")){
            item.classList.remove("active");
            console.log(item.getAttribute("ref"));
            document.querySelector(item.getAttribute("ref").toString()).classList.remove("active");
            document.querySelector(item.getAttribute("ref").toString()).classList.add("tab-pane");
        }

    });
}