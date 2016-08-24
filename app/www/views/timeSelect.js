Appsalonbook.timeSelect = function (params) {

    var goDetail = function (data) {
        //console.log(data);
        Appsalonbook.cart.time = data;
        Appsalonbook.app.navigate("cartDetail");

    }

    var formatTime = function (data) {

        if (typeof data == "number")
            data = data.toString();

        var hour = parseInt(data.substring(0, 2));
        var minute = data.substring(2);
        var civilhour = hour;
        var ampm = "AM";
        if (hour > 12) {
            civilhour = hour - 12;
            ampm = "PM"
        }
        
        return civilhour.toString() + ":" + minute + " " + ampm;
        
    }


    var viewModel = {
        isListReady: ko.observable(false),
        loadPanelMessage: ko.observable("Cargando turnos ..."),
        loadPanelVisible: ko.observable(false),
        timeAvailable: ko.observable(Array()),
        goDetail: goDetail,
        formatTime: formatTime,
        viewShown: function () {
            viewModel.isListReady(false);
            //if (Appsalonbook.cart.date && Appsalonbook.cart.idService) {
                viewModel.loadPanelVisible(true);
                $.getJSON(Appsalonbook.Utils.getUrlToken() + "&r=v1/reservacion/gettimeavailable",
                   {

                       date: Appsalonbook.cart.date,
                       servid: Appsalonbook.cart.idService

                   },
                    function (data) {
                        viewModel.loadPanelVisible(false);
                        if (data.success){
                            viewModel.timeAvailable(data.content);
                            viewModel.isListReady(true);
                        }
                        else
                            DevExpress.ui.notify(Globalize.localize("NO_TIME_AVAILABLE"), 'error', 4500);
                    }).fail(function () {
                        viewModel.loadPanelVisible(false);
                        DevExpress.ui.notify(Globalize.localize("CNX_ERROR"), 'error', 4500);
                    });


           // } else {
           //     Appsalonbook.app.navigate('search')
           // }
            

        },
        viewShowing: function () {
            if (!Appsalonbook.cart.date || !Appsalonbook.cart.idService)
                Appsalonbook.app.navigate('search');
        },
        viewHidden: function (event) {
            viewModel.isListReady(false);
            //console.log(event);

        }

    };


    return viewModel;
};