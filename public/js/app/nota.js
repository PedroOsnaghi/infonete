var input_yt = document.getElementById("inYt");



input_yt.addEventListener("change", function (e){
    var urlArr = this.value.split("/");
    var id_yt = urlArr.slice(-1);

    console.log(id_yt);
    this.value = id_yt;

    document.getElementById("yt-preview").setAttribute("src", "https://www.youtube.com/embed/" + id_yt);
});