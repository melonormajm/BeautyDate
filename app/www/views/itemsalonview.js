Appsalonbook.itemsalonview = function (params) {

    var height = $(window).width() / 2;

    Appsalonbook.cart.idSalon = params.id;

    var isLoaded = ko.observable(false),
        houseItem = ko.observable(null),
        name = ko.observable(""),
        price = ko.observable(""),
        services = ko.observable(""),
        imgs = ko.observable(""),
        descripcion = ko.observable("");

    function loadData(params) {
        return $.getJSON(Appsalonbook.Utils.getUrlToken() + "&r=v1/salon/view&id=" + params.id );        
    }
   
    var addFavorite = function () {
        viewModel.loadPanelMessage("Agregando a favoritos...");
        viewModel.loadPanelVisible(true);
        //$.getJSON(Appsalonbook.Utils.getUrlToken() + "&r=v1/salon/addfavorito&salonid=" + params.id).done(function (data) {
		$.getJSON(Appsalonbook.Utils.getUrlToken() + "&r=v1/salonsec/addfavorito&salonid=" + params.id).done(function (data) {

            if (data.success) {
                viewModel.showRemoveFromFavorite(true);
                viewModel.showAddToFavorite(false);
            }

            viewModel.loadPanelVisible(false);

        }).fail(function (data) {
            viewModel.loadPanelVisible(false);
        });


    }

    var removeFavorite = function () {
        viewModel.loadPanelMessage("Elimando de favoritos...");
        viewModel.loadPanelVisible(true);
        //$.getJSON(Appsalonbook.Utils.getUrlToken() + "&r=v1/salon/removefavorito&salonid=" + params.id).done(function (data) {
		$.getJSON(Appsalonbook.Utils.getUrlToken() + "&r=v1/salonsec/removefavorito&salonid=" + params.id).done(function (data) {

            if (data.success) {
                viewModel.showRemoveFromFavorite(false);
                viewModel.showAddToFavorite(true);
            }

            viewModel.loadPanelVisible(false);

        }).fail(function(data){
            viewModel.loadPanelVisible(false);
        });
    }

    var checkImg = function (url) {
        return (url != "") ? url : "images/no_image.png";
    }

    var goToCalendar = function (data, ev) {
        
		console.log(data);
        jQuery.each(viewModel.popoverVisibleObj, function (obj, m) { m(false) });
        ev.stopPropagation();
        console.log(ev);
        
		if (!Appsalonbook.Utils.getLoginState()) {
			Appsalonbook.Utils.disableLoginState();
			Appsalonbook.app.navigate('authForm', { root: true });
			return;
        } 
		Appsalonbook.cart.idService = data.id;
        Appsalonbook.app.navigate('vcalendar');
     
    }

    var formatPrice = function (data) {
        if (viewModel.currency() && viewModel.currency().simbolo)
            return Appsalonbook.Utils.formatCurrency(data, viewModel.currency().simbolo);

        return data;
    }
    

    var goToMap = function(){
        Appsalonbook.app.navigate('map/' + viewModel.houseItem().id );
    }

    var popoverVisible = function (data) {
        viewModel.popoverVisibleObj['visibleValue_' + data.id] = ko.observable(false);
        return viewModel.popoverVisibleObj['visibleValue_' + data.id];
    }

    var togglePopover = function (data) {
        console.log(data);
        var flag = !data.descripcion || viewModel.popoverVisibleObj['visibleValue_' + data.id]() ? false : true;
        viewModel.popoverVisibleObj['visibleValue_' + data.id](flag);

        viewModel.lastItem && viewModel.lastItem(false);
        viewModel.lastItem = null;
        if (flag)
            viewModel.lastItem = viewModel.popoverVisibleObj['visibleValue_' + data.id];

    }

    var viewModel = {
        
        heightRatio: ko.observable(height + 'px'),
        loadPanelMessage: ko.observable("Cargando salon ..."),
        loadPanelVisible: ko.observable(false),
        thumbnail: ko.observable(''),
        thereIsImgs: ko.observable(true),
        title: ko.observable('Cargando salon ...'),
        currency: ko.observable(''),
        isLoaded: isLoaded,
        houseItem: houseItem,
        name: name,
        price: price,
        services: services,
        imgs: imgs,
        descripcion: descripcion,
        checkImg: checkImg,
        showAddToFavorite: ko.observable(false),
        showRemoveFromFavorite: ko.observable(false),
        salonid: ko.observable(),
        canShowMap: ko.observable(false),
        popoverVisibleObj: {},
        popoverVisible: popoverVisible,
        togglePopover: togglePopover,
        lastItem: null,
        viewShown: function () {
            viewModel.loadPanelVisible(true);
            loadData(params).done(function (response) {
                try {

                    if (response.success) {
                        var content = response.content;
                        viewModel.houseItem(content);
                        viewModel.name(content.nombre);
                        viewModel.price(content.price);
                        viewModel.services(content.servicios);
                        viewModel.descripcion(content.descripcion);
                        viewModel.title(content.nombre);
                        viewModel.currency(content.moneda);
                        if (content.clienteSalonFavoritos.length > 0) {
                            viewModel.showRemoveFromFavorite(true);
                        }
                        else {
                            viewModel.showAddToFavorite(true);
                        }


                        viewModel.salonid(content.salonid);

                        if (content.imagenes.length > 0)
                            viewModel.imgs(content.imagenes);
                        else
                            viewModel.imgs([{ url: "images/no_image.png" }]);

                        if (content.ubicacion_latitud && content.ubicacion_longitud)
                            viewModel.canShowMap(true);
                        viewModel.isLoaded(true);

                    }
                    //var scrollElement = $(".dx-active-view .details");
                    //scrollElement.dxScrollView({});
                    //scrollElement.data("dxScrollView").scrollTo(0);
                } catch (Exception) {
                    console.log("No se pudo cargar las caracteristicas del salon");
                    //DevExpress.ui.notify("Por errores en la configuracion del salon no se pudo mostrar sus detalles", 'error', 3000);
                    console.log(Exception);
                    Appsalonbook.app.back();
                }

                viewModel.loadPanelVisible(false);
            }).fail(function(){
                viewModel.loadPanelVisible(false);
                DevExpress.ui.notify(Globalize.localize("CNX_ERROR"), 'error', 3000);
                Appsalonbook.Utils.showErr();
            
            });
            
        },
        viewHidden: function (event) {
            
            viewModel.title('');
            viewModel.isLoaded(false);
            //console.log(event);

        },

        reserForm: function () {
            Appsalonbook.app.navigate("Calendarv/1");
        },
        addFavorite: addFavorite,
        removeFavorite: removeFavorite,
        goToCalendar: goToCalendar,
        formatPrice: formatPrice,
        goToMap: goToMap,
        replaceDesc: function(data){
            return data.replace(/(?:\r\n|\r|\n)/g, '<br />');
        }
        
    };


    return viewModel;
};