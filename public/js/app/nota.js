var input_yt = document.getElementById("inYt");



input_yt.onpaste = function (e){

    e.preventDefault();
    var urlArr = e.clipboardData.getData('text/plain').split("/");
    var id_yt = urlArr.slice(-1);


    this.value = id_yt;

    document.getElementById("yt-preview").setAttribute("src", "https://www.youtube.com/embed/" + id_yt);
}