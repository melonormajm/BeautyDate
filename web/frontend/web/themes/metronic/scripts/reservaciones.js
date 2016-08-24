/**
 * Created by juanma on 4/14/2015.
 */
jQuery(document).ready(function() {
    $("#reservacion_new_client").click(function(){
        $("#nuevo_usuario").toggle();
        $("#usuario_registrado").toggle();
    });

    $('#reservacion-correo_cliente').change(function(){
        var email = $('#reservacion-correo_cliente').val();
        var emailReg = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
        if(!emailReg.test(email)){
            //alert('no es un correo');
            $('#email-error').show();
        }else{
            $('#email-error').hide();
        }
    });
})


var RESERVACIONES = {

    currentEvent: null,
    counterId: 0,

    onEventSelect : function(start, end) {

        //this.currentEvent = event;
        if(new Date(start).getTime() > new Date(new Date().getTime() - 24 * 60 * 60 * 1000).getTime()){
            $("#wait-spinner").hide();
            $("#load-failed").hide();
            $("#load-success").hide();
            $("#reservacion-nombre_cliente").val('');
            $("#reservacion-correo_cliente").val('');
            $("#users").find('option').prop("selected", false);
            $("#hora_inicio").find('option').prop("selected",false);
            $("#time_cont_col").hide();
            $('#mail-help-block').hide();
            $('#name-help-block').hide();
            $('#time-help-block').hide();
            $('#services-help-block').hide();
            $('#register-name-help-block').hide();
            $("#servicios_list").find('option').prop("selected",false);
            $('#modal_create_evento').modal('show');

            var title = 'En proceso';
            //var title = prompt('Nombre del Evento:');
            var eventData;
            if (title) {
                eventData = {
                    title: title,
                    start: start,
                    end: end,
                    id: RESERVACIONES.getNextId()
                };
                $('#fecha').val(start.format());
                RESERVACIONES.currentEvent = eventData;
                //$('#w0').fullCalendar('renderEvent', eventData, true);
            }
            $('#w0').fullCalendar('unselect');
        }
        else
            alert('No se pueden reservar días pasados.');
    },

    onEventClick : function(event) {
       // console.log(event);
        RESERVACIONES.currentEvent = event;
        $('#event_id').val(event.id);
        $('#cliente_info').text(event.cliente);
        $('#cliente_servicio').text(event.servicio);
        $('#cliente_sillon').text(event.sillon);
        $('#time_info').text(event.start.format('h:mm a'));
        $('#modal_evento').modal('show');
    },

    onEventRender:function(event, element, view ){
        element.find('.fc-event-title').append("<br />" + event.description);
    },

    onEventReceive: function(event){
        event.title = 'jorge';
        //alert(event.start.format('YYYY-MM-DD[T]HH:MM:SS'));
        event.id = RESERVACIONES.getNextId();
        this.currentEvent = event;
        $('#modal_create_evento').modal('show');
        $('#w0').fullCalendar('removeEvents', this.currentEvent.id);
        //element.find('.fc-event-title').append("<br />" + event.description);
    },

    getNextId: function(){
        this.counterId ++;
        return this.counterId;
    },


    removeCurrentEvent: function(){
        //$('#w0').fullCalendar('removeEvents', RESERVACIONES.currentEvent.id);
        RESERVACIONES.currentEvent = null;
        $('#modal_create_evento').modal('hide');
    },

    formatTime : function (data) {

        if (typeof data == "number")
            data = data.toString();
        else if (data == null) return "";

        var hour = parseInt(data.substring(0, 2));
        var minute = data.substring(2);
        var civilhour = hour;
        var ampm = "AM";

        if(hour == 12){
            ampm = "PM"
        }
        if(hour == 00){
            civilhour = 12;
        }
        if (hour > 12) {
            civilhour = hour - 12;
            ampm = "PM"
        }

        return civilhour.toString() + ":" + minute + " " + ampm;

    },
    onEventReserved: function(response){
        var newEvento = {
            id : response.id,
            title: response.user + " #" + response.id,
            start: response.start,
            end: response.end
        };
        //$('#w0').fullCalendar('removeEvents', this.currentEvent.id);
        //$('#w0').fullCalendar('renderEvent', newEvento, true);
    },

    getCurrentDay: function(){
        return RESERVACIONES.currentEvent.start.format();
    }

};




