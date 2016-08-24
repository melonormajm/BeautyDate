Appsalonbook.perfil = function (params) {



    var registeUser = function (params) {
        var result = { isValid: true, message: '' };
        if (viewModel.username() == '' || viewModel.email() == '' || viewModel.first_name() == '') {
            result.isValid = false;
            result.message = 'Los campos "Nombre", "Username", "Contraseña" y "Correo son obligatrios"';
        }
       

        if (result.isValid) {

            viewModel.loadPanelMessage(Globalize.localize("UPDATING_PROFILE"));
            viewModel.loadPanelVisible(true);
            $.post(Appsalonbook.Utils.getUrlToken() + "&r=v1/usuario/perfil-update",
                {
                    "SignupForm[username]": viewModel.username(),
                    "SignupForm[email]": viewModel.email(),
                    "SignupForm[first_name]": viewModel.first_name(),
                    "SignupForm[last_name]": viewModel.last_name()

                }
                ).fail(function () {
                    viewModel.loadPanelVisible(false);
                    DevExpress.ui.notify(Globalize.localize("CNX_ERROR"), 'error', 4500);
                    Appsalonbook.Utils.showErr();
                }).done(function (data) {
                    viewModel.loadPanelVisible(false);
                    if (data.success) {
                        DevExpress.ui.notify(Globalize.localize("PERFIL_ACTUALIZADO"), 'info', 3500);
                        //setTimeout(Appsalonbook.app.navigate("search", { root: true }));
                    } else {
                        DevExpress.ui.notify(data.content, 'error', 3500);
                    }
                });

        } else {
            DevExpress.ui.notify(result.message, 'error', 3000);
        }


    }

    var viewModel = {

        loadPanelMessage: ko.observable("Cargando perfil..."),
        loadPanelVisible: ko.observable(false),
        first_name: ko.observable(""),
        last_name: ko.observable(""),
        username: ko.observable(""),
        email: ko.observable(""),
        registeUser: registeUser,
        remenber: ko.observable(false),
        preRegister: function () {
            //setTimeout(viewModel.registeUser, 500);
			viewModel.registeUser();

        },
        viewShown: function () {
            viewModel.loadPanelMessage(Globalize.localize("LOADING_PROFILE"));
            viewModel.loadPanelVisible(true);
            var df = $.getJSON(Appsalonbook.Utils.getUrlToken() + "&r=v1/usuario/perfil-load");

            df.fail(function (response) {
                //console.log(response);
				try{
					viewModel.loadPanelVisible(false);
					DevExpress.ui.notify(Globalize.localize("CNX_ERROR"), 'error', 3000);
					Appsalonbook.Utils.showErr();
				}catch(Exception){
				
				}
            });

            df.done(function (data) {
                viewModel.loadPanelVisible(false);
                try{
					if (data.success) {
						viewModel.first_name(data.content.first_name);
						viewModel.last_name(data.content.last_name);
						viewModel.username(data.content.username);
						viewModel.email(data.content.email);
					}
				}catch(Exception){
					Appsalonbook.app.back();
				}
            });
            /*$.getJSON(Appsalonbook.Utils.getUrlToken() + "&r=v1/usuario/perfil-load").fail(function (response) {
                //console.log(response);
                viewModel.loadPanelVisible(false);
                DevExpress.ui.notify(Globalize.localize("CNX_ERROR"), 'error', 3000);
            },function (data) {
                viewModel.loadPanelVisible(false);
                if (data.success) {
                    viewModel.first_name(data.content.first_name);
                    viewModel.last_name(data.content.last_name);
                    viewModel.username(data.content.username);
                    viewModel.email(data.content.email);
                }
            });*/

            /*
          $.ajax({
                url: Appsalonbook.Utils.getUrlToken() + "&r=v1/usuario/perfil-load",
                success: function (data) {
                    viewModel.loadPanelVisible(false);
                    if (data.success) {
                        viewModel.first_name(data.content.first_name);
                        viewModel.last_name(data.content.last_name);
                        viewModel.username(data.content.username);
                        viewModel.email(data.content.email);
                    }
                },
                error: function () {
                    viewModel.loadPanelVisible(false);
                    DevExpress.ui.notify(Globalize.localize("CNX_ERROR"), 'error', 3000);
                }
            });*/

        }
    };

    return viewModel;
};