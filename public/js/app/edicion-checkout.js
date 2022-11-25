const btn_compra = document.getElementById("btn-compra");

btn_compra.addEventListener("click", function (e){
    e.preventDefault();


    let fData = new FormData();

    fData.append("id", this.getAttribute("data-id"))

    console.log(this.getAttribute("data-id"));

    fetch('http://localhost/infonete/edicion/generarDatosPago',{
        method: "POST",
        body: fData
    }).then(preference => {
        document.getElementById("msg").innerHTML = preference.body;
        console.log(preference.body);
        return preference.json();
    }).then(res =>{
        console.log(res)
        showMP(res.publickey, res.id);
    })

        .catch(error=>{
        console.log(error);
    });





});


function showMP(Pk, PID){
    const mp = new MercadoPago(Pk, {
        locale: 'es-AR'
    });

    const checkout = mp.checkout({
        preference: {
            id: PID
        },
    });

    checkout.open();
}