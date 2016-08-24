Appsalonbook.map = function (params) {



    var mapReady = function () {
        viewModel.loadPanelVisible(false);
    }


    var viewModel = {
        title: ko.observable(""),
        loadPanelMessage: ko.observable("Cargando mapa ..."),
        loadPanelVisible: ko.observable(false),
        markers: ko.observableArray(),
        mapReady: mapReady,
        viewShown: function () {
            if (params.id) {
                viewModel.loadPanelVisible(true);
                $.getJSON(Appsalonbook.Utils.getUrlToken() + "&r=v1/salon/viewmap&id=" + params.id).done(function (data) {
                    if (data.success) {
                        try {
                            var coor = { location: {} };
                            coor.location.lat = data.content.ubicacion_latitud;
                            coor.location.lng = data.content.ubicacion_longitud;
                            coor.location.tooltip = data.content.ubicacion;
                            viewModel.markers.push(coor);
                            viewModel.title(data.content.nombre);
                            viewModel.loadPanelVisible(false);
                        } catch (Exception) {
                            viewModel.loadPanelVisible(false);
                            //Appsalonbook.app.back();
                        }
                        
                    }
                

                }).fail(function (data) {
                    viewModel.loadPanelVisible(false);

                });
            }
        
        
        }
    };

    return viewModel;
};