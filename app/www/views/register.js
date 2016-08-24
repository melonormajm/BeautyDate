Appsalonbook.register = function (params) {


    var registeUser = function (params) {
        //var result = params.validationGroup.validate();
        var result = { isValid: true, message: '' };
        if (viewModel.username() == '' || viewModel.password() == '' || viewModel.email() == '' || viewModel.first_name() == '') {
            result.isValid = false;
            result.message = 'Los campos "Nombre", "Username", "Contraseña" y "Correo son obligatrios"';
        }
        else if (viewModel.password() != viewModel.confirmpassword()) {
            result.isValid = false;
            result.message = 'La comfirmación de contraseña no coincide';
        }

        if (result.isValid) {


            viewModel.loadPanelVisible(true);
            $.post(Appsalonbook.Utils.getUrl() + "?r=v1/site/signup",
                {
                    "SignupForm[username]": viewModel.username(),
                    "SignupForm[password]": viewModel.password(),
                    "SignupForm[email]": viewModel.email(),
                    "SignupForm[first_name]": viewModel.first_name(),
                    "SignupForm[last_name]": viewModel.last_name()

                }
                ).fail(function () {
                    viewModel.loadPanelVisible(false);
                    DevExpress.ui.notify(Globalize.localize("CNX_ERROR"), 'error', 4500);
                    //setTimeout(reloadList, 5000);
                }).done(function (data) {
                    viewModel.loadPanelVisible(false);
                    if (data.success) {
                        DevExpress.ui.notify(Globalize.localize("Usuario registrado"), 'info', 3500);

                        Appsalonbook.Utils.setToken(data.content.token, viewModel.remenber());
                        Appsalonbook.Utils.setUserId(data.content.user_id);
                        Appsalonbook.Utils.enableLoginState();
                        //setTimeout(Appsalonbook.app.navigate("search", { root: true }), 4000);
						//Appsalonbook.app.navigate("search", { root: true });
                        if(Appsalonbook.app.canBack())
                            Appsalonbook.app.back();
                        else
                            Appsalonbook.app.navigate("search", { root: true });

                    } else {
                        DevExpress.ui.notify(data.content, 'error', 3500);
                    }
                });

        } else {
            DevExpress.ui.notify(result.message, 'error', 3000);
        }


    }



    var viewModel = {
        loadPanelMessage: ko.observable("Registrando sesión..."),
        loadPanelVisible: ko.observable(false),
        first_name: ko.observable(""),
        last_name: ko.observable(""),
        username: ko.observable(""),
        password: ko.observable(""),
        confirmpassword: ko.observable(""),
        email: ko.observable(""),
        registeUser: registeUser,
        remenber: ko.observable(false),
        preRegister: function(){
            //setTimeout(viewModel.registeUser, 500);
			viewModel.registeUser();

        }

    };

    return viewModel;
};