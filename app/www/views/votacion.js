Appsalonbook.votacion = function (params) {


    var dataSourceVotacion = new DevExpress.data.DataSource({
        load: function (loadOptions) {
            console.log(loadOptions);
            viewModel.listVisibleFlag(false);
           
                return $.post(Appsalonbook.Utils.getUrlToken() + "&r=v1/reservacion/por-eval",
                    loadOptions
                    ).fail(function () {
                        DevExpress.ui.notify(Globalize.localize("CNX_ERROR"), 'error', 4500);
                        viewModel.listVisibleFlag(true);
                        viewModel.loadPanelVisible(false)
                    }).pipe(function (data) {
                        viewModel.listVisibleFlag(true);
                        viewModel.loadPanelVisible(false)
                        if (data.success)
                            return data.content;
                        else {
                            return [];
                        }

                    });
            }
               
                
        

    });


    var showEval = function (data) {
        viewModel.detailedBook(data);
        viewModel.popupEvalVisible(true);
    }

    var evaluate = function () {
       

    }

    var getClass = function (data) {
        if(!data.evaluacion)
            return "back-redcolor";
    }

    var testRadio = function (data) {
        console.log(data);
        console.log(viewModel.objRadio);
        console.log(viewModel.objRadio['value_' + data.reservid]());
    }

    var getRadioValue = function (data) {
        //console.log(data);
        var value = null;
        for (var i = 0; i < dataSourceRadio.length; i++) {
            if (data.evaluacion == dataSourceRadio[i].value) {
                value = dataSourceRadio[i];
                break;
            }
        }
        viewModel.objRadio['value_' + data.reservid] = ko.observable(value);
        return viewModel.objRadio['value_' + data.reservid];
    }

    var showDetails = function (data) {
        $('#voto-eva-cnt-' + data.reservid).slideDown(200);
        //$('#reserv-cnt-' + data.reservid).show();
        $('#img-voto-mas-' + data.reservid).hide();
        $('#img-voto-flecharriba-' + data.reservid).show();
        //console.log(data);
    }

    var hideDetails = function (data) {
        //$('#reserv-cnt-' + data.reservid).hide();
        $('#voto-eva-cnt-' + data.reservid).slideUp(200);
        $('#img-voto-mas-' + data.reservid).show();
        $('#img-voto-flecharriba-' + data.reservid).hide();
    }

    var showPopUp = function (data) {
        viewModel.detailedBook(data);
        viewModel.popupEvalVisible(true);
    }


    var evaluar = function (data) {
        var valueFn =  viewModel.objRadio['value_' + data.model.reservid];
        console.log(valueFn());
        
        var valor = valueFn();
        if (valor == undefined) {
            DevExpress.ui.notify("Bebe seleccionar un valor", 'error', 4000);
        }
        else {
            viewModel.loadPanelVisible(true);
            var reservid = data.model.reservid;
            viewModel.loadPanelMessage("Evaluando ...");
            $.getJSON(Appsalonbook.Utils.getUrlToken() + "&r=v1/salon/evaluar", { reservid: reservid, eval: valor.value }).done(function (data) {
                viewModel.loadPanelVisible(false);
                viewModel.dataSourceVotacion.load().done(function () {
                    viewModel.popupEvalVisible(false);
                }).fail(function () {
                    DevExpress.ui.notify('Error de conexion', 'error', 3000);
                    viewModel.popupEvalVisible(false);
                });

            }).fail(function () {
                viewModel.loadPanelVisible(false);
                DevExpress.ui.notify("Error de conexion", 'error', 4000);
            });


        }
        //
    }

    var later = function (data) {
        //data.model.reservid;
    }

    var evalPopUp = function () {
        var detailed = viewModel.detailedBook();
        var valor = viewModel.evalValue();

        if(valor != undefined){
            $.getJSON(Appsalonbook.Utils.getUrlToken() + "&r=v1/salon/evaluar", { reservid: detailed.reservid, eval: valor.value }).done(function (data) {
                viewModel.loadPanelMessage("Evaluando ...");
                viewModel.loadPanelVisible(false);
                viewModel.dataSourceVotacion.load().done(function () {
                    viewModel.popupEvalVisible(false);
                }).fail(function () {
                    DevExpress.ui.notify('Error de conexion', 'error', 3000);
                    viewModel.popupEvalVisible(false);
                });

            }).fail(function () {
                viewModel.loadPanelVisible(false);
                DevExpress.ui.notify("Error de conexion", 'error', 4000);
            });

        }else{
            DevExpress.ui.notify("Debe seleccionar una opción", 'error', 4000);
        }
    }


    var closePopUp = function () {
        viewModel.popupEvalVisible(false);
    }


    var formatDate = function(date){
        var d = new Date(date);
        return d.format('l j / F / Y');
    }

    var dataSourceRadio = [ {text: 'Excelente', value: 5}, {text: 'Bueno', value: 4}, {text: 'Regular', value: 3}, {text: 'Malo', value: 2}]

    var viewModel = {
        dataSourceRadio: dataSourceRadio,
        objRadio: {},
        popupEvalVisible: ko.observable(false),
        showEval: showEval,
        evaluate: evaluate,
        dataSourceVotacion: dataSourceVotacion,
        detailedBook: ko.observable(),
        evalValue: ko.observable(dataSourceRadio[1]),
        loadPanelMessage: ko.observable('Cargando ...'),
        loadPanelVisible: ko.observable(false),
        listVisibleFlag: ko.observable(false),
        flag: false,
        viewHidden: function () {
        //    viewModel.flag = true;
        },
        viewShown: function (obj) {
            console.log(obj);
          //  if (viewModel.flag) {
            viewModel.loadPanelVisible(true);
            viewModel.dataSourceVotacion.load();
          //  }
        },
        getClass: getClass,
        testRadio: testRadio,
        getRadioValue: getRadioValue,
        showDetails: showDetails,
        hideDetails: hideDetails,
        evaluar: evaluar,
        showPopUp: showPopUp,
        evalPopUp: evalPopUp,
        closePopUp: closePopUp,
        changeColorBg: function () {
            $('#cnt-vot').parent().addClass('bgviolet');
            console.log($('#cnt-vot').parent());
        },
        formatDate: formatDate,
        later: later

    };

    return viewModel;
};