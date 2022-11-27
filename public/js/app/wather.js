const temp = document.getElementById("temp");
const max = document.getElementById("tmax");
const min = document.getElementById("tmin");
const city = document.getElementById("tcity");




function init_wather(api_key){

    $.ajax({
        url: "https://weather.visualcrossing.com/VisualCrossingWebServices/rest/services/timeline/San Justo, La Matanza,AR?key=" + api_key,
        type: 'GET',
        success: function (respuesta){
            console.log(respuesta);
            temp.innerHTML = toC(respuesta.currentConditions.temp)+ "ºC";
            max.innerHTML="Max: " + toC(respuesta.days[0].tempmax) + "ºC";
            min.innerHTML="Min: " + toC(respuesta.days[0].tempmin) + "ºC";
            city.innerHTML=respuesta.resolvedAddress;
        },
        error: function (error){
            console.log(error);
        }
    });



}

function toC(f){
    return ((parseFloat(f)-32)/1.8).toFixed(1);
}