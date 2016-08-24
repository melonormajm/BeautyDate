Appsalonbook.vcalendar = function (params) {

    if (params.id)
        Appsalonbook.cart.idService = params.id;
    //Appsalonbook.cart.idService = 7;

    var COLOR_OFF = "red";
    //var COLOR_RESERVED = "#ff9f89";
    var COLOR_RESERVED = "rgb(110, 92, 127)";
    var RESTRICTED_OFF = 1;
    var RESTRICTED_RESERVED = 2;
    var EVENT_ID_COUNTER = 0;

    var getEventId = function () {
        EVENT_ID_COUNTER++;
        return EVENT_ID_COUNTER;
    }



    var eliminarEvento = function(){
        var eventselected = viewModel.eventSelected();
        if (eventselected && eventselected.id) {
            $('#mycalendar').fullCalendar('removeEvents', eventselected.id);
            $("td[data-date='" + eventselected.start.format() + "'] > div").removeClass('round-bg-selected');

        }
        
        viewModel.eventSelected(null);
    }

    var eliminarEvento2 = function(ev){
        try{
            $('#mycalendar').fullCalendar('removeEvents', ev.id);
            $("td[data-date='" + ev.start + "'] > div").removeClass('round-bg-selected');

        }catch(Exception){
        
        }
        
       
    }


    var renderCalendar = function () {


        var createEvento =  function (ev, check) {
            if (check && !viewModel.checkEventRestriction(ev.start, ev.end)) {
                return;
            }

            console.log("Start: " + ev.start.format());
            console.log("End: " + ev.end.format());
            ev.id = getEventId();
            viewModel.eventSelected(ev);
            viewModel.nextVisible(true);
            $('#mycalendar').fullCalendar('renderEvent', ev, true); // stick? = true

            //$("td[data-date='" + ev.start.format() + "'] > div").addClass('round-bg-selected');
               
        }




        jQuery('#mycalendar').fullCalendar({
            height: "auto",
            editable: true,
            eventLimit: true,
            selectable: true,
            selectHelper: true,
            aspectRatio: 0.9,
            lang: "es",
            header: {
                left: 'prev',
                center: 'title',
                right: 'next',
            },
            
            dayRender: function (date, cell) {
				
                var d = date.date();
                var claseinner = d > 9 ? "left2_dig" : "left1_dig"; 
                cell.html("<div class='round'><div class='round-inner " + claseinner + "'>"+  d  + "</div></div>" );

            },


            select: function (start, end) {
                    console.log("Event select");
                    var eventselected = viewModel.eventSelected();
                   
                    if (viewModel.checkEventRestriction(start, end)) {                   
                      var eventData;
                      //Para seleccionar el dia en el momento antes de mostrar la ventana
                      $("td[data-date='" + start.format() + "'] > div").addClass('round-bg-selected');
                    
                        try {
                            navigator.notification.confirm(Globalize.localize("DESEA_RESERVAR_ESTE_DIA"), function (pressed) {
                                if (pressed == 2) {
                                    eventData = {
                                        start: start,
                                        end: end,
                                        rendering: "background",
                                        color: 'white'
                                    };
                                    createEvento(eventData, true);
                                    viewModel.showSelectTimePopUp();
                                } else if (pressed == 1) {
                                    $("td[data-date='" + start.format() + "'] > div").removeClass('round-bg-selected');
                                }

                            }, "Confirmar", "No,Si");

                        } catch (Exception) {
                            if (confirm("¿Desea reservar para este dia?")) {
                                eventData = {
                                    
                                    start: start,
                                    end: end,
                                    rendering: "background",                                  
                                    color: 'transparent'
                                };
                                createEvento(eventData, true);
                                viewModel.showSelectTimePopUp();
                            } else {
                                //Eliminando elcirculo de seleccion
                                $("td[data-date='" + start.format() + "'] > div").removeClass('round-bg-selected');
                            }
                        }                    
                    }
                    $('#mycalendar').fullCalendar('unselect');
            },

            events: function (start, end, timezone, cb) {
                //$('#mycalendar').fullCalendar('removeEvents');
                var startstr = ''
                    endstr = '';

                    if (start.date() == 1) {
                        endstr = start.add('months', 1).date(1).subtract('days', 1).format();
                        startstr = start.format();
                    } else {
                        startstr = start.add('months', 1).date(1).format()
                        endstr = start.add('months', 1).date(1).subtract('days', 1).format();

                    }
               

                $.getJSON(Appsalonbook.Utils.getUrlToken() + "&r=v1/reservacion/getbusydays", {
                    servid : Appsalonbook.cart.idService,
                    fecha_inicio: startstr,//start.format(),
                    fecha_fin: endstr//end.format()

                }).done(function (data) {
                    if (data.success)
                        cb(viewModel.processEvents(data.content));
                    else
                        cb([]);
                }).fail(function (data) {
                    cb();
                });
               
            },
            loading: function (bool) {
                viewModel.loadPanelVisible(bool);
            }

        });

        viewModel.calendarRendered = true;
        //jQuery('#mycalendar').fullCalendar('refetchEvents');


    }



    var processEvents = function (events) {
        var bEvent = [];       
        if (events.length > 0) {
            for (var i = 0; i < events.length; i++) {
                var be = {};
                var event = events[i];
                be.start = event;
                be.rendering = "background";
                //be.color = COLOR_OFF;
                be.color = 'transparent';
                //be.title = "Reserved";
                be.id = event.id ? event.id : getEventId();
                $("td[data-date='" + event + "'] > div").addClass('round-bg-busy');
                bEvent.push(be);
            }

        }

        viewModel.businessEvents(bEvent);
        return bEvent;
    }


    var removeEventos = function () {
        var events = viewModel.businessEvents();
        if (events.length > 0) {
            for (var i = 0; i < events.length; i++) {
                eliminarEvento2(events[i]);

            }

        }
    }

    var checkEventRestriction = function (start, end) {

        if ($("td[data-date='" + start.format() + "']").hasClass('fc-other-month'))
            return false;

        var daystart = start.format();
        var dayend = end.format();
        //if (start < new Date()) {
        var beginigDate = new Date();
        beginigDate.setHours(0);
        beginigDate.setMinutes(0);
        beginigDate.setSeconds(0);
        if (end < beginigDate) {
            DevExpress.ui.notify('La fecha debe ser mayor que la fecha actual', 'error', 2500);
            return false;
        }
        var be = viewModel.businessEvents();
        for (var i = 0; i < be.length; i++) {
            if (daystart == be[i].start)
            //if (daystart == be[i].start || dayend == be[i].start)
                return false;
        }
        return true;
    }

    var timeForm = function () {
        var eventselected = viewModel.eventSelected()
        Appsalonbook.cart.date = eventselected.start.format();
        Appsalonbook.app.navigate("timeSelect");
    }

    var getTimeAvailables = function () {
        var eventselected = viewModel.eventSelected()
        Appsalonbook.cart.date = eventselected.start.format();
        //Appsalonbook.cart.date = eventselected.end.format();
        viewModel.loadPanelMessage("Cargando horarios ...");

        //Clearing list of time
        viewModel.timeAvailable([]);

        viewModel.loadPanelVisible(true);
        $.getJSON(Appsalonbook.Utils.getUrlToken() + "&r=v1/reservacion/getavailablehours",
                   {

                       date: Appsalonbook.cart.date,
                       servid: Appsalonbook.cart.idService

                   },
                    function (data) {

                        viewModel.loadPanelVisible(false);
                        if (data.success) {
                            viewModel.timeAvailable(data.content);
                            //viewModel.isListReady(true);
                        }
                        else
                            DevExpress.ui.notify(Globalize.localize("NO_TIME_AVAILABLE"), 'error', 4500);
                    }).fail(function () {
                        viewModel.loadPanelVisible(false);
                        DevExpress.ui.notify(Globalize.localize("CNX_ERROR"), 'error', 4500);
                    });


    }

    var showSelectTimePopUp = function () {
        Appsalonbook.cart.time = undefined;
        viewModel.popupVisible(true);
        viewModel.getTimeAvailables();
        

    }

    var goDetail = function (data) {
        //console.log(data);
        Appsalonbook.cart.time = data;
        viewModel.popupVisible(false);
        Appsalonbook.app.navigate("cartdetail");
        //console.log(data);

    }


    var onHiddenPop = function () {
        if (!Appsalonbook.cart.time)
            eliminarEvento();
    }

    var viewModel = {
        popupVisible: ko.observable(false),
        isContentReady: ko.observable(false),
        eventSelected : ko.observable(),
        loadPanelMessage: ko.observable("Cargando disponibilidad ..."),
        loadPanelVisible: ko.observable(false),
        businessEvents: ko.observable([]),
        checkEventRestriction: checkEventRestriction,
        processEvents: processEvents,
        timeForm: timeForm,
        nextVisible: ko.observable(false),
        timeAvailable: ko.observable([]),
        getTimeAvailables: getTimeAvailables,
        showSelectTimePopUp: showSelectTimePopUp,
        goDetail: goDetail,
        calendarRendered: false,
        viewShown: function () {
            if (! Appsalonbook.cart.idService) Appsalonbook.app.navigate("search", { root: true });

            viewModel.loadPanelVisible(true);
            $('.round-bg-busy').removeClass('round-bg-busy'); 
            viewModel.isContentReady(true);
            viewModel.loadPanelVisible(false);
            //
            if (!viewModel.calendarRendered) {
                renderCalendar();
            }else
                jQuery('#mycalendar').fullCalendar('refetchEvents');
        },
        viewHidden: function (event) {
            viewModel.isContentReady(false);
            //console.log(event);

        },

        onHiddenPop: onHiddenPop
    };

    return viewModel;
};