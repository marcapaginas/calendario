var SITEURL = "http://calendario.test";
var calendar;
var personaSeleccionada;
var acceso = false;
var admin = false;

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
    error: function (xhr) {
        alert(
            "Request Status: " +
                xhr.status +
                " Status Text: " +
                xhr.statusText +
                " " +
                xhr.responseText
        );
    },
});

document.addEventListener("DOMContentLoaded", function () {
    var calendarEl = document.getElementById("calendar");
    calendar = new FullCalendar.Calendar(calendarEl, {
        // themeSystem: "bootstrap5",
        initialView: "dayGridMonth",
        locale: "es",
        headerToolbar: {
            left: "prev,next,today",
            center: "title",
            right: "dayGridMonth,timeGridWeek,timeGridDay,listMonth",
        },
        firstDay: 1,
        weekNumbers: true,
        weekText: "",
        selectable: true,
        editable: false,
        navLinks: true,
        navLinkDayClick: true,
        // eventMaxStack: 2,
        // dayMaxEvents: true,
        timeZone: "Europe/Madrid",
        // eventContent: function (arg) {
        //     console.log(arg.event);
        //     return {
        //         html: arg.event.extendedProps.nombre + " - " + arg.event.title,
        //     };
        // },

        //eventRender: function (info, element) {
        // console.log(info);
        // console.log(element);
        // element.find(".fc-event").prepend("<span class='closeon'>X</span>");
        // if (view.name == "listDay") {
        //     element
        //         .find(".fc-list-item-time")
        //         .append("<span class='closeon'>X</span>");
        // } else {
        //     element
        //         .find(".fc-event")
        //         .prepend("<span class='closeon'>X</span>");
        // }
        // element.find(".closeon").on("click", function () {
        //     calendar.fullCalendar("removeEvents", event._id);
        //     console.log("delete");
        // });
        // },
        // eventDidMount: function (info) {
        //     var tooltip = new Tooltip(info.el, {
        //         title: info.event.title,
        //         placement: "top",
        //         trigger: "hover",
        //         container: "body",
        //     });
        // },
        businessHours: [
            {
                // days of week. an array of zero-based day of week integers (0=Sunday)
                daysOfWeek: [1, 2, 3, 4, 5],

                startTime: "09:30",
                endTime: "14:00",
            },
            {
                // days of week. an array of zero-based day of week integers (0=Sunday)
                daysOfWeek: [1, 2, 3, 4, 5],

                startTime: "15:30",
                endTime: "19:30",
            },
        ],

        // events: [{
        //     start: '2022-07-18',
        //     end: '2022-07-18',
        //     display: 'background',
        //     backgroundColor: 'teal'
        // }],

        select: function (event) {
            if (admin) {
                if (checkPersonaSeleccionada() !== false) {
                    $("#modal").html(
                        `
                        <form id="formModal">
                        <input class="border border-rounded px-3 py-1" id="titulo" name="titulo">
                        <button class="px-3 py-2 rounded bg-blue-500 text-white botonCrear">CREAR EVENTO</button>
                        </form>
                        `
                    );
                    const form = document.getElementById("formModal");
                    $("form").submit(function (e) {
                        e.preventDefault();
                        var titulo = document.getElementById("titulo").value;
                        var comienzo = event["startStr"];
                        var final = event["endStr"];
                        var todoElDia = event["allDay"] ? 1 : 0;
                        var tipo = "vacaciones";
                        var select = document.getElementById("persona");
                        var persona =
                            select.options[select.selectedIndex].value;
                        $.ajax({
                            url: SITEURL + "/fullcalenderAjax",
                            data: {
                                title: titulo,
                                persona: persona,
                                allDay: todoElDia,
                                start: comienzo,
                                end: final,
                                tipo: tipo,
                                type: "add",
                            },
                            type: "POST",
                            success: function (data) {
                                displayMessage("Evento Creado");
                                calendar.refetchEvents();
                                window.livewire.emit("recalcular");
                            },
                        });
                        cerrarModal();
                    });
                    // const btn = document.querySelector(".botonCrear");
                    // btn.addEventListener("click", function () {
                    //     var titulo = document.getElementById("titulo").value;
                    //     var comienzo = event["startStr"];
                    //     var final = event["endStr"];
                    //     var todoElDia = event["allDay"] ? 1 : 0;
                    //     var tipo = "vacaciones";
                    //     var select = document.getElementById("persona");
                    //     var persona =
                    //         select.options[select.selectedIndex].value;
                    //     $.ajax({
                    //         url: SITEURL + "/fullcalenderAjax",
                    //         data: {
                    //             title: titulo,
                    //             persona: persona,
                    //             allDay: todoElDia,
                    //             start: comienzo,
                    //             end: final,
                    //             tipo: tipo,
                    //             type: "add",
                    //         },
                    //         type: "POST",
                    //         success: function (data) {
                    //             displayMessage("Evento Creado");
                    //             calendar.refetchEvents();
                    //             window.livewire.emit("recalcular");
                    //         },
                    //     });
                    //     cerrarModal();
                    // });
                    $("#modal").modal();

                    //$inicio, $fin, $todoElDia, $tipo

                    // var title = prompt("nombre:");
                    // var comienzo = event["startStr"];
                    // var final = event["endStr"];
                    // var todoElDia = event["allDay"] ? 1 : 0;
                    // var tipo = "vacaciones";
                    // var select = document.getElementById("persona");
                    // var persona = select.options[select.selectedIndex].value;

                    // $.ajax({
                    //     url: SITEURL + "/fullcalenderAjax",
                    //     data: {
                    //         title: title,
                    //         persona: persona,
                    //         allDay: todoElDia,
                    //         start: comienzo,
                    //         end: final,
                    //         tipo: tipo,
                    //         type: "add",
                    //     },
                    //     type: "POST",
                    //     success: function (data) {
                    //         displayMessage("Evento Creado");
                    //         calendar.refetchEvents();
                    //         window.livewire.emit("recalcular");
                    //     },
                    // });
                }
            }
        },
        eventContent: function (arg) {
            return {
                html: arg.event.extendedProps.nombre + " - " + arg.event.title,
            };
        },
        eventRender: function (event, element, view) {
            if (event.allDay === "true") {
                event.allDay = true;
            } else {
                event.allDay = false;
            }
            if (acceso) {
                event.editable = true;
            } else {
                event.editable = false;
            }
        },

        selectHelper: true,
        eventResize: function (info) {
            if (admin) {
                if (!confirm("Confirmar cambio?")) {
                    info.revert();
                }
                var start = info.event["startStr"];
                var end = info.event["endStr"];

                $.ajax({
                    url: SITEURL + "/fullcalenderAjax",
                    data: {
                        title: info.event.title,
                        start: start,
                        end: end,
                        id: info.event.id,
                        type: "update",
                    },
                    type: "POST",
                    success: function (response) {
                        displayMessage("Actualizado");
                        window.livewire.emit("recalcular");
                    },
                });
            }
        },
        eventDrop: function (info) {
            // alert(info.event.title + " was dropped on " + info.event.start.toISOString());
            if (admin) {
                if (!confirm("¿Estás seguro que quieres cambiar el evento?")) {
                    info.revert();
                }

                var start = info.event["startStr"];
                var end = info.event["endStr"];

                $.ajax({
                    url: SITEURL + "/fullcalenderAjax",
                    data: {
                        title: info.event.title,
                        start: start,
                        end: end,
                        id: info.event.id,
                        type: "update",
                    },
                    type: "POST",
                    success: function () {
                        displayMessage("Actualizado");
                        window.livewire.emit("recalcular");
                    },
                });
            }
        },
        eventClick: function (info) {
            $("#modal").html(`<div class="p-3">${info.event.title}</div>`);
            if (admin) {
                $("#modal").append(
                    `<button class="px-3 py-2 rounded bg-blue-500 text-white" onClick="borrarEvento(${info.event.id})">BORRAR EVENTO</button>
                    <button class="px-3 py-2 rounded bg-gray-500 text-white" onClick="cerrarModal()">
                    CANCELAR</button>`
                );
            }
            $("#modal").modal();

            // if (admin) {
            //     var deleteMsg = confirm("¿Seguro que quieres borrarlo?");
            //     if (deleteMsg) {
            //         $.ajax({
            //             type: "POST",
            //             url: SITEURL + "/fullcalenderAjax",
            //             data: {
            //                 id: info.event.id,
            //                 type: "delete",
            //             },
            //             success: function (response) {
            //                 calendar.refetchEvents();
            //                 window.livewire.emit("recalcular");
            //                 displayMessage("Borrado");
            //             },
            //         });
            //     }
            // }
        },
    });
    calendar.render();
    //console.log(document.getElementsByClassName("closeon"));
});

function displayMessage(message) {
    toastr.success(message, "Éxito");
}

function checkPersonaSeleccionada() {
    if (!personaSeleccionada > 0) {
        toastr.error("No se ha seleccionado ninguna persona", "Alerta");
        return false;
    }
    return true;
}

// function checkAcceso() {
//     if (!acceso) {
//         toastr.error("No tiene acceso a modificar", "Alerta");
//         return false;
//     }
//     return true;
// }

window.addEventListener("contentChanged", (e) => {
    calendar.refetchEvents();
});

window.addEventListener("cambiadaPersona", (e) => {
    if (e.detail.persona > 0) {
        personaSeleccionada = e.detail.persona;
    } else {
        personaSeleccionada = null;
    }
});

window.addEventListener("creadaPersona", (e) => {
    toastr.success("Persona creada", "Éxito");
});

window.addEventListener("borradaPersona", (e) => {
    toastr.error("Persona borrada", "Éxito");
});

window.addEventListener("concedidoAcceso", (e) => {
    acceso = true;
    calendar.setOption("events", SITEURL + "/eventos");
    calendar.refetchEvents();
});

window.addEventListener("concedidoAccesoAdmin", (e) => {
    acceso = true;
    admin = true;
    calendar.setOption("selectable", true);
    calendar.setOption("editable", true);
    calendar.setOption("events", SITEURL + "/eventos");
    calendar.refetchEvents();
});

window.addEventListener("alerta", (e) => {
    toastr.error(e.detail.mensaje, "Error");
});

function crearEvento($input) {
    console.log($input);
    // $.ajax({
    //     url: SITEURL + "/fullcalenderAjax",
    //     data: {
    //         title: $titulo,
    //         start: $inicio,
    //         allDay: $todoElDia,
    //         end: $fin,
    //         tipo: $tipo,
    //         type: "add",
    //     },
    //     type: "POST",
    //     success: function () {
    //         displayMessage("Evento creado");
    //         calendar.refetchEvents();
    //         window.livewire.emit("recalcular");
    //     },
    // });
    // cerrarModal();
}

function actualizarEvento($id, $titulo, $inicio, $fin, $todoElDia) {
    $.ajax({
        url: SITEURL + "/fullcalenderAjax",
        data: {
            id: $id,
            title: $titulo,
            start: $inicio,
            allDay: $todoElDia,
            end: $fin,
            type: "update",
        },
        type: "POST",
        success: function () {
            displayMessage("Actualizado");
            window.livewire.emit("recalcular");
        },
    });
    cerrarModal();
}

function borrarEvento($id) {
    if (admin) {
        var deleteMsg = confirm("¿Seguro que quieres borrarlo?");
        if (deleteMsg) {
            $.ajax({
                type: "POST",
                url: SITEURL + "/fullcalenderAjax",
                data: {
                    id: $id,
                    type: "delete",
                },
                success: function (response) {
                    calendar.refetchEvents();
                    window.livewire.emit("recalcular");
                    displayMessage("Borrado");
                },
            });
        }
    }
    cerrarModal();
}

function borrarPersona() {
    var deleteMsg = confirm("¿Seguro que quieres borrar esta persona?");
    if (deleteMsg) {
        window.livewire.emit("borrarPersona");
        calendar.refetchEvents();
    }
}

function cerrarModal() {
    $.modal.close();
}
