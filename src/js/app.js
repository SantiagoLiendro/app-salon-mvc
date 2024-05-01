let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

document.addEventListener('DOMContentLoaded', () => {
    iniciarApp()
})


function iniciarApp() {
    mostrarSession()
    tabs()//Cambia la session cuando se precine un tab
    botonesPaginador() //agrega o quita los botones del paginador
    paginaSiguiente()
    paginaAnterior()
    consultarApi() // COnsulta la Api  en el Backend de PHP
    idCliente()
    nombreCliente()
    fechaCliente()
    horaCliente()
    mostrarResumen()
}

function mostrarSession() {
    //Ocultar 
    const seccionAnterior = document.querySelector('.mostrar')
    if (seccionAnterior) {
        seccionAnterior.classList.remove('mostrar')
    }
    //Seleccionar la secion con el paso
    const seccion = document.querySelector(`#paso-${paso}`)
    seccion.classList.add('mostrar');

    //Quita la clase actual 
    const tabAnterior = document.querySelector('.actual');
    if (tabAnterior) {
        tabAnterior.classList.remove('actual')
    }

    //Resalta el tab actual 
    const tab = document.querySelector(`[data-paso="${paso}"]`)
    tab.classList.add('actual')
}

function tabs() {
    const botones = document.querySelectorAll('.tabs button');
    botones.forEach(boton => {
        boton.addEventListener('click', e => {
            paso = parseInt(e.target.dataset.paso);
            mostrarSession();
            botonesPaginador();
            if (paso === 3) {
                mostrarResumen()
            }
        })
    })
}

function botonesPaginador() {
    const paginaSiguiente = document.querySelector('#siguiente')
    const paginaAnterior = document.querySelector('#anterior')

    switch (paso) {
        case 1: {
            paginaAnterior.classList.add('ocultar')
            paginaSiguiente.classList.remove('ocultar')
            break;
        }

        case 3: {
            paginaAnterior.classList.remove('ocultar')
            paginaSiguiente.classList.add('ocultar')
            break;
        }

        default: {
            paginaAnterior.classList.remove('ocultar')
            paginaSiguiente.classList.remove('ocultar')
            break
        }

    }
    mostrarSession()
}

function paginaSiguiente() {
    const paginaSiguiente = document.querySelector('#siguiente')
    paginaSiguiente.addEventListener('click', () => {
        if (paso >= pasoFinal) return
        paso++;
        botonesPaginador();
        mostrarResumen()
    })
}

function paginaAnterior() {
    const paginaAnterior = document.querySelector('#anterior')
    paginaAnterior.addEventListener('click', () => {
        if (paso <= pasoInicial) return
        paso--;
        botonesPaginador();
        mostrarResumen()
    })
}

async function consultarApi() {
    try {
        const url = '/api/servicios';
        const resultado = await fetch(url)
            .then(res => res.json())
        mostrarServicios(resultado)
    } catch (error) {
        console.log(error)
    }
}

function mostrarServicios(servicios) {
    servicios.forEach(servicio => {
        const { id, nombre, precio } = servicio

        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$ ${precio}`;

        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('servicio');
        contenedorServicio.dataset.idServicio = id;
        contenedorServicio.onclick = () => seleccionarServicio(servicio);

        contenedorServicio.appendChild(nombreServicio);
        contenedorServicio.appendChild(precioServicio);

        document.querySelector('#servicios').appendChild(contenedorServicio)
    })
}

function seleccionarServicio(servicio) {
    const { id } = servicio
    const { servicios } = cita;

    const divServico = document.querySelector(`[data-id-servicio="${id}"]`)

    //Comprobar si un servicio ya fue agregado
    if (servicios.some(item => item.id === id)) {
        cita.servicios = servicios.filter(items => items.id !== id)
        divServico.classList.remove('seleccionado')
    } else {
        divServico.classList.add('seleccionado');
        cita.servicios = [...servicios, servicio]
    }
}

function idCliente() {
    const id = document.querySelector('#id').value;
    cita.id = id
}

function nombreCliente() {
    const nombre = document.querySelector('#nombre').value;
    cita.nombre = nombre
}

function fechaCliente() {
    const fecha = document.querySelector('#fecha')
    fecha.addEventListener('input', (e) => {
        const dia = new Date(e.target.value).getUTCDay();
        if ([6, 0].includes(dia)) {
            //Mostrar alerta
            e.target.value = '';
            mostrarAlerta("Fines de semana no permitidos.", 'error', '#paso-2 p')
        } else {
            cita.fecha = e.target.value;
        }
    })
}

function horaCliente() {
    const horaInput = document.querySelector('#hora');
    horaInput.addEventListener('input', (e) => {
        const horaCita = e.target.value;
        const hora = horaCita.split(':')[0]
        if (hora < 10 || hora > 18) {
            e.target.value = '';
            mostrarAlerta('Horario de atencion de 10:00 a 18:00', 'error', '#paso-2 p')
            return;
        }
        cita.hora = horaCita;
    })

}

function mostrarResumen() {
    const resumen = document.querySelector('.contenido-resumen')

    //Limpiar contenido de resumen
    while (resumen.firstChild) {
        resumen.removeChild(resumen.firstChild)
    }

    if (Object.values(cita).includes('') || cita.servicios.length < 1) {
        mostrarAlerta('Faltan datos de Servicios, Fecha u Hora.', 'error', '.contenido-resumen', false);
        return;
    }

    //Formater el div de resumen
    const { nombre, fecha, hora, servicios } = cita

    //Headin para servicios y resumen
    const headinServicios = document.createElement('H3');
    headinServicios.textContent = 'Resumen de Servicios'
    resumen.appendChild(headinServicios)

    //Iterando y mostrando servicios
    servicios.forEach(servicio => {
        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio = document.createElement('P')
        textoServicio.textContent = servicio.nombre;

        const precioServicio = document.createElement('P')
        precioServicio.innerHTML = `<span>Precio: </span> $${servicio.precio}`;

        contenedorServicio.appendChild(textoServicio)
        contenedorServicio.appendChild(precioServicio)

        resumen.appendChild(contenedorServicio)
    })

    const headingCita = document.createElement('H3');
    headingCita.classList.add('my-2')
    headingCita.textContent = 'Resumen de la Cita'
    resumen.appendChild(headingCita)

    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre: </span> ${nombre}`;

    const fechaCita = document.createElement('P');
    fechaCita.classList.add('capitalize')

    //Formatear fecha
    const fechaObj = new Date(fecha)
    const mes = fechaObj.getMonth()
    const dia = fechaObj.getDay() + 2
    const year = fechaObj.getFullYear()

    const fechaUTC = new Date(Date.UTC(year, mes, dia))

    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }
    const fechaFormateada = fechaUTC.toLocaleDateString('es-AR', opciones)

    fechaCita.innerHTML = `<span>Fecha: </span> ${fechaFormateada}`;

    const horaCita = document.createElement('P');
    horaCita.innerHTML = `<span>Hora: </span> ${hora}`;

    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton')
    botonReservar.textContent = 'Resarvar Cita';
    botonReservar.onclick = reservarCita

    const contenedorBoton = document.createElement('DIV');
    contenedorBoton.classList.add('alinear-derecha')
    contenedorBoton.appendChild(botonReservar)

    resumen.appendChild(nombreCliente)
    resumen.appendChild(fechaCita)
    resumen.appendChild(horaCita)

    resumen.appendChild(contenedorBoton);
}

async function reservarCita() {
    const { id, fecha, hora, servicios } = cita

    const idServicios = servicios.map(servicio => servicio.id)

    const datos = new FormData()
    datos.append('usuario_id', id)
    datos.append('fecha', fecha)
    datos.append('hora', hora)
    datos.append('servicios', idServicios)

    try {
        const url = '/api/citas'
        const respuesta = await fetch(url, {
            method: 'post',
            body: datos
        })
            .then(res => res.json())

        const { resultado } = respuesta;
        if (resultado) {
            Swal.fire({
                icon: "success",
                title: "Cita Creada",
                text: "Tu cita fue creada correctamente",
                button: 'OK'
            })
                .then(() => {
                    window.location.reload()
                })
        }
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Hubo un error al guardar la cita",
        });
    }

}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {

    const alertaPrevia = document.querySelector('.alerta')
    if (alertaPrevia) {
        alertaPrevia.remove()
    }

    const alerta = document.createElement('DIV')
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);
    alerta.classList.add('my-2');

    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta)

    if (desaparece) {
        setTimeout(() => {
            alerta.remove()
        }, 4000);
    }
}