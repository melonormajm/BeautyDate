Appsalonbook.completeinfo = function (params) {

    var loadPanelMessage = ko.observable("Registrando sesión...");
    var loadPanelVisible = ko.observable(false);


    var registeUser = function (arg) {
        //var result = params.validationGroup.validate();

        var result = { isValid: true, message: '' };

        if (viewModel.showname() && viewModel.name() == '') {
            result.isValid = false;
            result.message = 'El campo "Nombre" es obligatrio"';
        }

        if (viewModel.showusername() && viewModel.username() == '') {
            result.isValid = false;
            result.message = 'El campo "Username" es obligatrio"';
        }

        if (viewModel.email() && viewModel.email() == '') {
            result.isValid = false;
            result.message = 'El campo "Correo" es obligatrio"';
        }



       

        if (result.isValid) {

            var data = Appsalonbook.FBINFO;
            data["username"] = viewModel.username();
            data["first_name"] = viewModel.name();
            data["email"] = viewModel.email();

            viewModel.loadPanelVisible(true);
            $.post(Appsalonbook.Utils.getUrl() + "?r=v1/site/loginsocial&XDEBUG_SESSION_START=phpstorm",
                data
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
						Appsalonbook.app.navigate("search", { root: true })
                    } else {
                        DevExpress.ui.notify(data.content, 'error', 3500);
                    }


                });



        } else {
            DevExpress.ui.notify(result.message, 'error', 3000);
        }


    }



    var viewModel = {
        loadPanelMessage: loadPanelMessage,
        loadPanelVisible: loadPanelVisible,
        username: ko.observable(""),
        name: ko.observable(""),
        password: ko.observable(""),
        confirmpassword: ko.observable(""),
        email: ko.observable(""),
        showname: ko.observable(false),
        showusername: ko.observable(true),
        showemail : ko.observable(false),
        registeUser: registeUser,
        remenber: ko.observable(false),
        preRegister: function () {
            setTimeout(viewModel.registeUser, 500);

        },
        viewShown: function () {
            
           
            try {
                if (!Appsalonbook.FBINFO.email)
                    viewModel.showemail(true);
                if (!Appsalonbook.FBINFO.name)
                    viewModel.showname(true);
            } catch (Exception) {
               //alert("LLego aqui");

            }

           

        }
        
    };

    return viewModel;
};