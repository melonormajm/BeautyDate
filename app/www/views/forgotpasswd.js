Appsalonbook.forgotpasswd = function (params) {



    var sendPassResetRequest = function () {
        var email = viewModel.email();
        if (email == '') {
            DevExpress.ui.notify("Debe especificar una dirección de correo válida", 'error', 2000);
            return;
        }
        /*else if()
        {
            DevExpress.ui.notify("Debe especificar una dirección de correo válida", 'error', 2000);

        }*/
        viewModel.loadPanelVisible(true);
        $.post(Appsalonbook.Utils.getUrlToken() + "&r=v1/site/request-password-reset", {
            "PasswordResetRequestForm[email]": email
        }).done(function (data) {
            viewModel.loadPanelVisible(false);
            if (data.success) {
                
                DevExpress.ui.notify("Se le ha  enviado un correo para recuperar su contraseña", 'info', 3000);

            } else {
                DevExpress.ui.notify(data.content, 'error', 3000);
                //DevExpress.ui.notify("No pudo ser enviado el correo de recueracion de contraseña", 'error', 3000);
            }
        }).fail(function (data) {
            viewModel.loadPanelVisible(false);
            DevExpress.ui.notify("No pudo ser enviado el correo de recueracion de contraseña", 'error', 3000);

        });

    }



    var viewModel = {
        loadPanelMessage: ko.observable("Recuperando contraseña ..."),
        loadPanelVisible: ko.observable(false),
        email: ko.observable(""),
        sendPassResetRequest: sendPassResetRequest,
        preSendPassResetRequest: function () {
            //setTimeout(viewModel.sendPassResetRequest, 500);
			viewModel.sendPassResetRequest();
            
        }
    };

    return viewModel;
};