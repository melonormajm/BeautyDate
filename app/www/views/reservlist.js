Appsalonbook.reservList = function (params) {


    var dataSourceReserv = new DevExpress.data.DataSource({
        load: function (loadOptions) {
            //console.log(loadOptions);
            return $.getJSON(Appsalonbook.Utils.getUrlToken() + "&r=v1/reservacion/getallreserv",
                loadOptions
                ).fail(function () {
                    DevExpress.ui.notify(Globalize.localize("CNX_ERROR"), 'error', 4500);
                    //setTimeout(reloadList, 5000);
                }).pipe(function (data) {
                    if(data.success)
                        return data.content;
                    else {
                        return [];
                    }
                });
        }

    });

    /*var dataSourceReserv = [

        {

            date: "Date",
            salonname: "Salon 1",
            servname: "Servicio",
            precio: "25",
            estado: "PENDIENTE",
            ubicacion: "Marianao",
            evaluacion: 0

        }

    ]*/




    var timeForm = function () {
        DevExpress.ui.notify('Ahora se cancelara la reservacion', 'info', 3000);

    }

    var goToMap = function (obj) {
        console.log(obj);
        Appsalonbook.app.navigate('map/' + obj.model.salonid);
        //if(.)
    }

    var canShowMap = function (obj) {
        //console.log(obj);
        return obj.salon_lng && obj.salon_ltd;

    }

    var detallesBook = function (data) {
        viewModel.reservFecha(data.date);
        viewModel.reservSalon(data.salonname);
        viewModel.reservServ(data.servname);
        viewModel.reservPrecio(data.precio);
        viewModel.reservEstado(data.estado);
        viewModel.reservDir(data.ubicacion);
        viewModel.detailedBook(data);
        viewModel.isCurrentReadyToCancel(viewModel.isVisibleToCancel(data));
        viewModel.isCurrentReadyToConfirm(viewModel.isVisibleToConfirm(data));

        viewModel.popupVisible(true);
    }

    var isVisibleToCancel = function (data) {
        if (data.estado == "EJECUTADA" || data.estado == "CANCELADA") {
            return false;
        } else if (data.estado == "PRERESERVADO") {
            return true;
        } else if (data.estado == "PENDIENTE") {
            return true;
        }

    }

    var isVisibleToConfirm = function (data) {
        if (data.estado == "EJECUTADA" || data.estado == "CANCELADA") {
            return false;
        } else if (data.estado == "PRERESERVADO") {
            return true;
        } else if (data.estado == "PENDIENTE") {
            return false;
        }
    }


    var cancelarBook = function (data) {
        var reservid = null;
        if (data.reservid == undefined) {
            reservid = viewModel.detailedBook().reservid;
        } else
            reservid = data.reservid;


        try {
            navigator.notification.confirm(Globalize.localize("SURE_CAN_CANCEL_BOOK"), function (pressed) {

                if (pressed == 2) {
                    viewModel.loadPanelMessage("Cancelando ...");
                    viewModel.loadPanelVisible(true);
                    $.getJSON(Appsalonbook.Utils.getUrlToken() + "&r=v1/reservacion/cancelar", { id: reservid }).done(function (data) {
                        viewModel.loadPanelVisible(false);
                        viewModel.listVisibleFlag(false);
                        viewModel.dataSourceReserv.load().done(function () {
                            viewModel.listVisibleFlag(true);
                            viewModel.popupVisible(false);

                        }).fail(function () {
                            DevExpress.ui.notify('Error de conexion', 'error', 3000);
                            viewModel.popupVisible(false);
                        });

                    }).fail(function () {
                        DevExpress.ui.notify("Error de conexion", 'error', 4000);
                    });


                }

            }, "Confirmar", "No,Si");

        } catch (Exception) {
            if (confirm(Globalize.localize("SURE_CAN_CANCEL_BOOK"))) {
                viewModel.loadPanelMessage("Cancelando ...");
                viewModel.loadPanelVisible(true);
                $.getJSON(Appsalonbook.Utils.getUrlToken() + "&r=v1/reservacion/cancelar", { id: reservid }).done(function (data) {
                    viewModel.loadPanelVisible(false);
                    viewModel.dataSourceReserv.load().done(function () {
                        viewModel.popupVisible(false);
                    }).fail(function () {
                        DevExpress.ui.notify('Error de conexion', 'error', 3000);
                        viewModel.popupVisible(false);
                    });

                }).fail(function () {
                    DevExpress.ui.notify("Error de conexion", 'error', 4000);
                });
            }
        }      
    }

    var confirmBook = function (data) {
        var reservid = null;
        if (data.reservid == undefined) {
            reservid = viewModel.detailedBook().reservid;
        } else
            reservid = data.reservid;

        viewModel.loadPanelMessage("Confirmando ...");
        viewModel.loadPanelVisible(true);
        $.getJSON(Appsalonbook.Utils.getUrlToken() + "&r=v1/reservacion/reserv", { reservid: reservid }).done(function (data) {
            viewModel.loadPanelVisible(false);
            viewModel.listVisibleFlag(false);
            viewModel.dataSourceReserv.load().done(function () {
                viewModel.popupVisible(false);
                viewModel.listVisibleFlag(false);
            }).fail(function () {
                DevExpress.ui.notify('Error de conexion', 'error', 3000);
                viewModel.popupVisible(false);
                viewModel.listVisibleFlag(false);
            });

        }).fail(function () {
            viewModel.loadPanelVisible(false);
            DevExpress.ui.notify("Error de conexion", 'error', 4000);
        });

    }

    var showEval = function (data) {
        viewModel.detailedBook(data);
        viewModel.popupEvalVisible(true);

    }

    var isVisibleToEval = function (data) {
        
        return data.estado == "EJECUTADA" && (data.evaluacion == null || data.evaluacion == 0) ? true : false;
    }

    var evaluate = function () {
        //DevExpress.ui.notify("Evaluacion: " + viewModel.evalValue(), 'info', 4000);
        console.log(viewModel.evalValue());
        var valor = viewModel.evalValue();
        if (valor == undefined) {
            DevExpress.ui.notify("Bebe seleccionar un valor", 'error', 4000);
        }
        else {
            var reservid = viewModel.detailedBook().reservid;
            $.getJSON(Appsalonbook.Utils.getUrlToken() + "&r=v1/salon/evaluar", { reservid: reservid, eval : valor.value }).done(function (data) {
                viewModel.loadPanelMessage("Evaluando ...");
                viewModel.loadPanelVisible(false);
                viewModel.dataSourceReserv.load().done(function () {
                    viewModel.popupEvalVisible(false);
                    viewModel.listVisibleFlag(false);
                }).fail(function () {
                    DevExpress.ui.notify('Error de conexion', 'error', 3000);
                    viewModel.popupEvalVisible(false);
                    viewModel.listVisibleFlag(true);
                });

            }).fail(function () {
                viewModel.loadPanelVisible(false);
                DevExpress.ui.notify("Error de conexion", 'error', 4000);
            });


        }
        //

    }

    var makeDate = function (data) {
        return data.date + " " + Appsalonbook.Utils.formatTime(data.hora);
    }

    var showDetails = function (data, ev) {
        $('#reserv-cnt-' + data.reservid).slideDown(200);
        //$('#reserv-cnt-' + data.reservid).show();
        $('#img-mas-' + data.reservid).hide();
        $('#img-flecharriba-' + data.reservid).show();
        //console.log(ev);
        ev && ev.stopPropagation();
        
    }

    var hideDetails = function (data, ev) {
        //$('#reserv-cnt-' + data.reservid).hide();
        $('#reserv-cnt-' + data.reservid).slideUp(200);
        $('#img-mas-' + data.reservid).show();
        $('#img-flecharriba-' + data.reservid).hide();
        ev && ev.stopPropagation();
    }

    var formatDate = function (date) {
        try{
            date = date.split("-");
            var d = new Date(date[0], date[1] - 1, date[2]);
            //var d = new Date(date);
            var fecha = d.format('l j \\d\\e F Y');
            //console.log(fecha);
            return fecha;
        }catch(Exception){
            return "";
        }
        
        //return d.format('l j /F/Y');

    }


    var toogleDetails = function (data) {
        console.log(data)
        if ($('#img-flecharriba-' + data.reservid).is(":visible"))
            viewModel.hideDetails(data);
        else {
            viewModel.showDetails(data);
            //alert($('#reservation_list_id').height());
            //var rls = $('#reservation_list_id');
            //console.log(rls);
            //rls.dxScrollView({ showScrollbar: 'always' });
            //rls.data("dxScrollView").scrollTo(0);

        }
        
    }

    var viewModel = {
        btnArriba: ko.observable(false),
        btnMas: ko.observable(true),
        btnCrus: ko.observable(true),
        evalValue: ko.observable(null),
        visibilityEval: ko.observable(true),
        popupEvalVisible: ko.observable(false),
        loadPanelMessage: ko.observable("Cargando reservaciones ..."),
        loadPanelVisible: ko.observable(false),
        listVisibleFlag: ko.observable(false),
        dataSourceReserv: dataSourceReserv,
        timeForm: timeForm,
        cancelarBook: cancelarBook,
        detallesBook: detallesBook,
        confirmBook : confirmBook,
        popupVisible: ko.observable(false),
        reservSalon: ko.observable(''),
        reservFecha: ko.observable(''),
        reservFecha: ko.observable(''),
        reservServ: ko.observable(''),
        reservPrecio: ko.observable(''),
        reservEstado: ko.observable(''),
        reservDir : ko.observable(''),
        isCurrentReadyToConfirm: ko.observable(false),
        isCurrentReadyToCancel: ko.observable(false),
        isVisibleToCancel: isVisibleToCancel,
        isVisibleToConfirm: isVisibleToConfirm,
        detailedBook : ko.observable(),
        showEval: showEval,
        isVisibleToEval: isVisibleToEval,
        evaluate: evaluate,
        makeDate: makeDate,
        showDetails: showDetails,
        hideDetails: hideDetails,
        formatDate: formatDate,
        goToMap: goToMap,
        canShowMap: canShowMap,
        toogleDetails: toogleDetails,
        onContentReadyHandler: function(data){
            //console.log($('.reservacion-details-cnt-hide'));
            $('.reservacion-details-cnt-hide').hide();

        },

        viewShown: function () {
            //viewModel.evalValue({ text: 'Excelente', value: 5 });
            //viewModel.loadPanelMessage("Cargando ...");
            viewModel.loadPanelVisible(true);
            viewModel.loadPanelMessage("Cargando reservaciones ...");
            if (!Appsalonbook.Utils.getLoginState()) {
                Appsalonbook.app.navigate('authForm', { root: true });
                return;
            }
            dataSourceReserv.load().done(function (data) {
                viewModel.loadPanelVisible(false);
                //if (data.success) {
                //    viewModel.listVisibleFlag(true);
                //    return data.content;
                //}
                viewModel.listVisibleFlag(true);
            }).fail(function () {
                viewModel.loadPanelVisible(false);
                viewModel.listVisibleFlag(true);
                DevExpress.ui.notify('Error de conexion', 'error', 3000);

            });
        }
    };

    return viewModel;
};