const formulario = document.getElementById("form-checkout");

formulario.addEventListener("submit", function (e){
    e.preventDefault();
    e.stopPropagation();

    let fData = new FormData(this);

    fetch('http://localhost/infonete/suscripcion/generarDatosPago',{
        method: "POST",
        body: fData
    }).then(preference => {
        console.log(preference);
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