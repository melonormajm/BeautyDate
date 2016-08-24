Appsalonbook.cartdetail = function (params) {

    var doReserv = function () {
        viewModel.loadPanelVisible(true);
        viewModel.loadPanelMessage("Reservando ...");
        $.getJSON(Appsalonbook.Utils.getUrlToken() + "&r=v1/reservacion/reserv", { reservid: Appsalonbook.cart.reservid }).done(function (data) {
            Appsalonbook.cart = {};
            Appsalonbook.states.clearcalendar = true;
            viewModel.enableReserv(false);
            viewModel.enableClear(false);
            viewModel.enableCancel(true);
            //viewModel.enableGoMyReserv(false);
            viewModel.loadPanelVisible(false);

            var toDate = new Date(viewModel.dateObj.getTime() + viewModel.servduracion()*60000);
            console.log(toDate);
            DevExpress.ui.notify("Reserva realizada", 'info', 4000);
            //Appsalonbook.app.navigate("reservList", { root: true });
            //Aqui hay que poner lo de calendario
            try {

                if ( window.plugins.calendar) {
                    var title = Globalize.localize("RESERVACION_CALENDAR_TITLE") + viewModel.salonname();
                    var location = viewModel.direccion();
                    var notes = Globalize.localize("RESERVACION_CALENDAR_SERVICIO") + viewModel.servname();
                    window.plugins.calendar.createEvent(title, location, notes, viewModel.dateObj, toDate, function (resp) { }, function (resp) {
						//Appsalonbook.app.navigate("search", { root: true });
                        Appsalonbook.app.navigate(Appsalonbook.backurl, { root: true });

					});

                }else{
                    Appsalonbook.app.navigate(Appsalonbook.backurl, { root: true });
                    //Appsalonbook.app.navigate("promotionlist", { root: true });
                }

            } catch (Exception) {
				//Appsalonbook.app.navigate("search", { root: true });
                Appsalonbook.app.navigate(Appsalonbook.backurl, { root: true });
            }


        }).fail(function () {
            viewModel.loadPanelVisible(false);
            DevExpress.ui.notify("Error de conexion", 'error', 4000);
        });
        
    }

    var doClearReserv = function () {
        viewModel.loadPanelVisible(true);
        $.getJSON(Appsalonbook.Utils.getUrlToken() + "&r=v1/reservacion/cancelar", { id: (Appsalonbook.cart.reservid) ? Appsalonbook.cart.reservid : viewModel.reservId() }).done(function (data) {
            viewModel.loadPanelVisible(false);
            Appsalonbook.cart = {};
            DevExpress.ui.notify("Reservacion olvidada", 'info', 2000);
            //Clearing calendar selected day
            $('td.fc-day > div.round').removeClass('round-bg-selected');

            //Appsalonbook.app.navigate("search", { root: true });
            Appsalonbook.app.navigate(Appsalonbook.backurl, { root: true });
        }).fail(function () {
            viewModel.loadPanelVisible(true);
            DevExpress.ui.notify("Error de conexion", 'error', 4000);
        });
        
    }

    var doCancelReserv = function () {
        viewModel.loadPanelVisible(true);
        $.getJSON(Appsalonbook.Utils.getUrlToken() + "&r=v1/reservacion/cancelar", { id: (Appsalonbook.cart.reservid) ? Appsalonbook.cart.reservid : viewModel.reservId() }).done(function (data) {
            viewModel.loadPanelVisible(false);
            Appsalonbook.cart = {};
            DevExpress.ui.notify("Reservacion cancelada", 'info', 2000);
            //Appsalonbook.app.navigate("search", { root: true });
            Appsalonbook.app.navigate(Appsalonbook.backurl, { root: true });
        }).fail(function () {
            viewModel.loadPanelVisible(true);
            DevExpress.ui.notify("Error de conexion", 'error', 4000);
        });

    }

    var goMyReserv = function () {

        Appsalonbook.app.navigate("reservList", { root: true });
    }

    var cleanDetails = function() {
        viewModel.salonname("");
        viewModel.servprice("");
        viewModel.servname("");
        viewModel.sillon("");
        viewModel.time("");
        viewModel.date("");
        viewModel.direccion("");
        viewModel.enableReserv(true);
        viewModel.enableCancel(false);
        viewModel.enableClear(true);
    }


    var viewModel = {
        isContentReady: ko.observable(false),
        loadPanelMessage: ko.observable("Cargando detalles ..."),
        loadPanelVisible: ko.observable(false),
        enableReserv: ko.observable(false),
        enableClear: ko.observable(false),
        enableCancel: ko.observable(false),
        enableGoMyReserv: ko.observable(false),
        salonsrc : ko.observable(''),
        salonname: ko.observable(''),
        servprice: ko.observable(''),
        nuevo_precio: ko.observable(false),
        npriceflag: ko.observable(false),
        servname: ko.observable(''),
        reservId: ko.observable(''),
        sillon: ko.observable(''),
        time: ko.observable(''),
        date: ko.observable(Appsalonbook.cart.date),
        direccion: ko.observable(''),
        servduracion: ko.observable(''),
        doReserv: doReserv,
        doClearReserv: doClearReserv,
        doCancelReserv: doCancelReserv,
        goMyReserv: goMyReserv,
        cleanDetails: cleanDetails,
        timeObj: '',
        dateObj: '',
        viewShown: function () {
            viewModel.loadPanelVisible(true);
            viewModel.isContentReady(false);
            viewModel.cleanDetails();
            var parametros  = {};
            parametros.date = Appsalonbook.cart.date + Appsalonbook.cart.time;
            parametros.servid=  Appsalonbook.cart.idService;
            if(Appsalonbook.cart.promocion != undefined){
                parametros.promocionid = Appsalonbook.cart.promocion.id
            }

            $.getJSON(Appsalonbook.Utils.getUrlToken() + "&r=v1/reservacion/prereserv",
                parametros
                ).done(function (data) {
                    if (data.success) {
                        //var datearr = data.content.date.split(" ");
                        viewModel.salonname(data.content.salonname);
                        viewModel.salonsrc(data.content.salonsrc ? data.content.salonsrc : "images/no_image.png");
                        viewModel.servname(data.content.servname);
                        //viewModel.servprice(Appsalonbook.Utils.formatCurrency(data.content.precio, data.content.moneda));
                        viewModel.servprice(data.content.precio);
                        viewModel.nuevo_precio(data.content.nuevo_precio);
                        viewModel.npriceflag(!data.content.nuevo_precio ? true : false);
                        viewModel.direccion(data.content.ubicacion);
                        viewModel.loadPanelVisible(false);
                        viewModel.servduracion(data.content.servduracion);

                        try {
                            var date1 = data.content.date.split("-");
                            //var d = new Date(date1[0], date1[1] - 1, date1[2]);

                            var hour = data.content.hora.substring(0, 2);
                            var minute = data.content.hora.substring(2);
                            var d = new Date(date1[0], date1[1] - 1, date1[2], parseInt(hour), parseInt(minute));
                            //var d = new Date(data.content.date);
                            viewModel.dateObj = d;
                            viewModel.date(d.format('l j / F / Y'));

                        } catch (Exception) {

                        }
                        


                        viewModel.time(Appsalonbook.Utils.formatTime(data.content.hora));
                        viewModel.reservId(data.content.reservid);
                        viewModel.sillon(data.content.sillon);
                        Appsalonbook.cart.reservid = data.content.reservid;
                        viewModel.enableReserv(true);
                        viewModel.enableClear(true);
                        viewModel.isContentReady(true);

                        /*var scrollElement = $(".dx-active-view .details");
                        scrollElement.dxScrollView({});
                        scrollElement.data("dxScrollView").scrollTo(0);*/
                    } else {
                        viewModel.loadPanelVisible(false);
                        Appsalonbook.cart = {};
                        DevExpress.ui.notify("Error de conexion", 'error', 4000);
                        Appsalonbook.app.navigate("search", {root:true});
                    }
               
               });
        },
        viewHidden: function (data) {
            viewModel.isContentReady(false);
        }
    };

    return viewModel;
};