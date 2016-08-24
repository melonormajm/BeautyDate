Appsalonbook.promotionlist = function (params) {


    var dataSourcePromo = new DevExpress.data.DataSource({
        load: function (loadOptions) {
            console.log(loadOptions);
            viewModel.listVisibleFlag(false);
            viewModel.loadPanelVisible(true);
            return $.getJSON(Appsalonbook.Utils.getUrlToken() + "&r=v1/promocion/index1",
                loadOptions
                ).fail(function () {
                    DevExpress.ui.notify(Globalize.localize("CNX_ERROR"), 'error', 4500);
                    viewModel.listVisibleFlag(true);
                    viewModel.loadPanelVisible(false);
                    Appsalonbook.Utils.showErr();
                }).pipe(function (data) {
                    viewModel.listVisibleFlag(true);
                    viewModel.loadPanelVisible(false);
                    if (data.success)
                        return data.content;
                    else
                        return [];
                }, function (data) {
                    return null;

                });

        }

    });


   /* var search = function (obj) {
        if (obj.categid) {
            viewModel.dataSourcePromo.filter('categid', '=', obj.categid);
        }

        if (obj.favorite)
            viewModel.dataSourcePromo.filter('favorite', '=', 1);

      
        viewModel.loadPanelVisible(true);
        viewModel.dataSourcePromo.load().done(function (data) {
            viewModel.loadPanelVisible(false);
            

        });
        //Appsalonbook.app.navigate("search");


    };*/

    var formatDate = function ($date) {
        $datearr = $date.split(" ");
        return $datearr[0];


    }

   
    var reservFn = function () {
        DevExpress.ui.notify('Reservacion', 'info', 3000);
    }

    var goCalendar = function (data) {
        console.log(data);
        Appsalonbook.cart = {};

        Appsalonbook.cart.idService = data.servicio.id;
        Appsalonbook.cart.promocion = data;
        //Appsalonbook.app.navigate("vcalendar");
        Appsalonbook.app.navigate("promocionDetalle");
    }

    var viewModel = {
        loadPanelMessage: ko.observable("Cargando promociones ..."),
        loadPanelVisible: ko.observable(false),
        listVisibleFlag: ko.observable(false),
        reservFn: reservFn,
        dataSourcePromo: dataSourcePromo,
        formatDate: formatDate,
        viewShown: function () {
            
            try {
                viewModel.dataSourcePromo.load();
            } catch (Exception) {
            }
        },
        goCalendar: goCalendar,
        forceScroll: function () {
            console.log("Force scrolll")
            var l = $("#promocionLisId").dxList("instance");
            if (l != undefined)
                l.scrollTo(0);
            console.log(l);
        }
    };

    return viewModel;
};