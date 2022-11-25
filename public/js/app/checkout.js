

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

function init_suscripcion(dias){
    console.log(dias);
    const fi = document.getElementById("fi");
    const fv = document.getElementById("fv");

  let fecha = new Date()
  let vence = new Date();

  vence.setDate(vence.getDate() + parseInt(dias));

  fi.innerHTML = fecha.toLocaleDateString('es-Ar', { weekday:"long", year:"numeric", month:"short", day:"numeric"});
  fv.innerHTML = vence.toLocaleDateString('es-Ar', { weekday:"long", year:"numeric", month:"short", day:"numeric"});
}