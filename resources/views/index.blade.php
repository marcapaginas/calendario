<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calendario</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.27.2/axios.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/locales-all.min.js"></script>
</head>

<body>
    <div id='calendar'></div>

    <script>
        var SITEURL = "http://calendario.test";

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            error: function(xhr) {
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

        document.addEventListener("DOMContentLoaded", function() {
            var calendarEl = document.getElementById("calendar");
            var calendar = new FullCalendar.Calendar(calendarEl, {
                themeSystem: "bootstrap5",
                initialView: "dayGridMonth",
                locale: 'es',
                headerToolbar: {
                    left: "prev,next,today",
                    center: "title",
                    right: "dayGridMonth,timeGridWeek,timeGridDay,listMonth",
                },
                firstDay: 1,
                selectable: true,
                editable: true,
                dayMaxEvents: true,
                timeZone: "local",
                events: SITEURL + "/eventos",
                select: function(event) {
                    var title = prompt("nombre:");
                    // var tag = prompt("etiqueta:");
                    // var select = document.getElementById("color");
                    // var color = select.options[select.selectedIndex].value;
                    // event["color"] = color;
                    var comienzo = event["startStr"];
                    var final = event["endStr"];
                    var todoElDia = event["allDay"] ? 1 : 0;
                    var tipo = 'vacaciones';
                    var color = 'red';

                    $.ajax({
                        url: SITEURL + "/fullcalenderAjax",
                        data: {
                            title: title,
                            persona: 1,
                            allDay: todoElDia,
                            start: comienzo,
                            end: final,
                            tipo: tipo,
                            type: "add",
                        },
                        type: "POST",
                        success: function(data) {
                            displayMessage("Evento Creado");
                            calendar.refetchEvents();
                        },
                    });
                },

                eventRender: function(event, element, view) {
                    if (event.allDay === "true") {
                        event.allDay = true;
                    } else {
                        event.allDay = false;
                    }
                },
                selectHelper: true,
                eventResize: function(info) {
                    if (!confirm("is this okay?")) {
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
                            color: info.event.color,
                            id: info.event.id,
                            type: "update",
                        },
                        type: "POST",
                        success: function(response) {
                            displayMessage("Event Updated Successfully");
                        },
                    });
                },
                eventDrop: function(info) {
                    // alert(info.event.title + " was dropped on " + info.event.start.toISOString());

                    if (!confirm("Are you sure about this change?")) {
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
                        success: function(response) {
                            displayMessage("Event Updated Successfully");
                        },
                    });
                },
                eventClick: function(info) {
                    var deleteMsg = confirm("Do you really want to delete?");
                    if (deleteMsg) {
                        $.ajax({
                            type: "POST",
                            url: SITEURL + "/fullcalenderAjax",
                            data: {
                                id: info.event.id,
                                type: "delete",
                            },
                            success: function(response) {
                                calendar.refetchEvents();
                                displayMessage("Event Deleted Successfully");
                            },
                        });
                    }
                },
            });
            calendar.render();
        });

        function displayMessage(message) {
            toastr.success(message, "Event");
        }
    </script>
</body>

</html>
