
var nota_container = document.getElementById("notas-container");
var select_edition = document.getElementById("select-edition");


function iniciar_lista() {
    var btn_state = document.querySelectorAll("a[nota-delete]");

    btn_state.forEach(function (btn) {
        btn.addEventListener("click", function () {
            if (btn.getAttribute('state') == 'baja') {
                darDeAlta(btn);
            } else {
                darDeBaja(btn);
            }
        });
    });

    function darDeBaja(btn) {
        const label = document.getElementById('label-' + btn.getAttribute('id-nota'))
        getRequest('http://localhost/infonete/articulo/baja?id=' + btn.getAttribute('id-nota'), function (response) {
            if (response && response.state) {
                label.innerHTML = response.state;
                limpiarClase(label);
                label.classList.add('badge-state--1');
                btn.setAttribute('state', 'baja');
                btn.children[0].classList.remove('mdi-delete');
                btn.children[0].classList.add('mdi-replay');
            }
        });
    }

    function darDeAlta(btn) {
        const label = document.getElementById('label-' + btn.getAttribute('id-nota'));
        getRequest('http://localhost/infonete/articulo/restablecer?id=' + btn.getAttribute('id-nota'), function (response) {
            if (response && response.state) {
                label.innerHTML = response.state;
                limpiarClase(label);
                label.classList.add('badge-state-1');
                btn.setAttribute('state', 'alta');
                btn.children[0].classList.remove('mdi-replay');
                btn.children[0].classList.add('mdi-delete');
            }
        });
    }
}

select_edition.addEventListener("change", function () {
    establecerSeleccion(this.value)
});

function establecerSeleccion(id) {
    request("http://localhost/infonete/articulo/list?ide=" + id);
}

function request(url) {
    $.ajax({
        url: url,
        type: "GET",
        success: function (response) {
            if (response) {
                nota_container.innerHTML = response;
                crearBotones();
                iniciar_lista();
            }
        }
    });
}

function getRequest(url, callback) {
    $.ajax({
        url: url,
        type: "GET",
        success: callback
    });
}

function verificarSeleccion() {
    if (select_edition.value != "0") establecerSeleccion(select_edition.value);
}

function crearBotones() {
    const tools = document.querySelectorAll('td[tools]');
    tools.forEach(function (tool) {
        verificarEstado(tool, tool.getAttribute('id-estado'), tool.getAttribute('id-nota'));
    });
}

function verificarEstado(tool, estado, id) {
    if (estado < 0) {
        tool.innerHTML = '<a  class="btn btn-secondary" title="Restablecer nota" id-nota="' + id + '" state="baja" nota-delete><i class="mdi mdi-replay mx-auto"></i></a> ' +
            '<a class="btn btn-secondary" title="Previsualizar" href="/infonete/articulo/preview?id=' + id + '"><i class="mdi mdi-eye mx-auto"></i></a>';
    } else {
        tool.innerHTML = '<a  class="btn btn-secondary" title="Dar de baja" id-nota="' + id + '" state="alta" nota-delete><i class="mdi mdi-delete mx-auto"></i></a> ' +
            '<a class="btn btn-secondary" title="Previsualizar" href="/infonete/articulo/preview?id=' + id + '"><i class="mdi mdi-eye mx-auto"></i></a>';
    }
}

function limpiarClase(e) {
    for (let i = -1; i < 4; i++) {
        e.classList.remove('badge-state-' + i);
    }
}