Appsalonbook.promociondetalle = function (params) {

    var viewModel = {
        imagen: ko.observable(''),
        isContentReady: ko.observable(false),
        salonname: ko.observable(''),
        servname: ko.observable(''),
        descripcion: ko.observable(''),
        servprice: ko.observable(''),
        nuevo_precio: ko.observable(''),
        npriceflag: ko.observable(false),
        startdate: ko.observable(''),
        enddate: ko.observable(''),
        viewShown: function () {

            if (params.promocionid) {
                $.getJSON(Appsalonbook.Utils.getUrlToken() + "&r=v1/promocion/detalle",
             {
                 id: params.promocionid
                 //id: Appsalonbook.cart.promocion.id
             }
           ).done(function (data) {
               if (data.success) {
                   //console.log(data);
				   try {
					   Appsalonbook.cart = {};

					   Appsalonbook.cart.idService = data.content.servicio.id;
					   Appsalonbook.cart.promocion = data.content;

					   viewModel.imagen(data.content.imagen);
					   viewModel.isContentReady(true);
					   viewModel.salonname(data.content.servicio.salon.nombre);
					   viewModel.servname(data.content.servicio.nombre);
					   viewModel.servprice(data.content.servicio.precio);
					   viewModel.nuevo_precio(data.content.servicio.nuevo_precio);
					   viewModel.npriceflag(!data.content.servicio.nuevo_precio ? true : false);
					   viewModel.descripcion(data.content.descripcion);

                   
                       var date1 = data.content.fecha_inicio.split(" ")[0].split("-");
                       var d = new Date(date1[0], date1[1] - 1, date1[2]);
                       //var d = new Date(data.content.date);
                       viewModel.startdate(d.format('l j / F / Y'));

                       date1 = data.content.fecha_fin.split(" ")[0].split("-");
                       d = new Date(date1[0], date1[1] - 1, date1[2]);
                       //var d = new Date(data.content.date);
                       viewModel.enddate(d.format('l j / F / Y'));

					   
					    var scrollElement = $(".dx-active-view #myscroll-promo");
						scrollElement.dxScrollView({});
						scrollElement.data("dxScrollView").scrollTo(0);
					   
				    }
					catch(Exception){
						console.log("Ocurio un error aqui");
					}
               }
           }).fail(function (data) {


           });



            }

         


        },
		goCalendar: function(){
			if (!Appsalonbook.Utils.getLoginState()) {
				//Appsalonbook.Utils.disableLoginState();
				//Appsalonbook.backurl
				Appsalonbook.app.navigate('authform//////promotionlist');
				return;
			} 
			Appsalonbook.app.navigate('vcalendar');
		
		}
	
	};

    return viewModel;
};