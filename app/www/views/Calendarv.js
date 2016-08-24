Appsalonbook.Calendarv = function (params) {

     if(params.id)
        Appsalonbook.cart.idService = params.id
    //console.log(Appsalonbook.cart);

     function onValueChange(Obj) {
         //var year_str = Obj.value.getFullYear();
         //var monthint = Obj.value.getMonth() + 1;
         //var dayint =   Obj.value.getDate() + 1;

         //var month_str = (monthint <= 9) ? '0' + monthint : monthint;
         //var day_str = (dayint <= 9) ? '0' + dayint : dayint;

         //Appsalonbook.cart.date = Obj.value.toDateString();
         //Appsalonbook.cart.date = year_str + "-" + month_str + "-" + day_str;
         var month = Obj.value.getMonth() + 1;
         Appsalonbook.cart.date = Obj.value.getFullYear() + "-" + month+ "-" + Obj.value.getDate();
         //console.log(Appsalonbook.cart.date);
         viewModel.nextVisible(false);
         viewModel.loadPanelVisible(true);
 
        $.getJSON(Appsalonbook.Utils.getUrl() + "?r=reservacionr/checkdate",
                {
                    date      : Appsalonbook.cart.date,
                    servid       : Appsalonbook.cart.idService

                },
                 function (data) {
                     viewModel.loadPanelVisible(false);
                     if(data.result== "SUCCESS")
                         viewModel.nextVisible(true);
                     else
                         DevExpress.ui.notify(Globalize.localize("NO_TIME_AVAILABLE"), 'error', 4500);
                 }).fail(function () {
                     viewModel.loadPanelVisible(false);
                     DevExpress.ui.notify(Globalize.localize("CNX_ERROR"), 'error', 4500);
                 });
        
        
    }

    var doReserv = function() {
        DevExpress.ui.notify('Notification message', 'info', 3000);
    }


    var timeForm = function () {
        Appsalonbook.app.navigate("timeSelect");
    }

    var viewModel = {
        onValueChange: onValueChange,
        loadPanelMessage : ko.observable("Comprobando disponibilidad ..."),
        loadPanelVisible:  ko.observable(false),
        doReserv: doReserv,
        horarioTitle: 'Horarios disponibles',
        timeForm: timeForm,
        nextVisible: ko.observable(false)
    };

    return viewModel;
};