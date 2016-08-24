Appsalonbook.account = function (params) {
    openFB.init({ appId: Appsalonbook.config.app.fb.id });



    var logOut = function () {
        localStorage.removeItem("beautyDateUsername");
        localStorage.removeItem("beautyDatePassword");
        Appsalonbook.Utils.unsetToken();
        Appsalonbook.Utils.disableLoginState();

        //if (Appsalonbook.Utils.getLastSocialUsed() == "FACEBOOK") {

            //alert("Vamos a ver si esta logueado en FB");

            openFB.getLoginStatus(function (loginStatus) {
                if (loginStatus.status == 'connected') {
                    //alert("Usuario conectado, vamos a desconectarlo");
                    openFB.logout(function () {
                        //alert("Usuario deslogeado de face");
                    });
                } 
            });
       // }

        Appsalonbook.app.navigate("authForm", {root: true});

    }



    var viewModel = {
        token: ko.observable(''),
        clientid: ko.observable(''),
        facebinfo: ko.observable(false),
        redifo: ko.observable(''),
        username: ko.observable(""),
        email: ko.observable(""),
        isContentReady: ko.observable(false),
        loadPanelMessage: ko.observable("Cargando mi perfil ..."),
        loadPanelVisible: ko.observable(false),
        viewShown: function () {
            if (!Appsalonbook.Utils.getLoginState()) {
                Appsalonbook.app.navigate('authForm', { root: true });
                return;
            }

            if (Appsalonbook.Utils.getUrlToken()) {
                viewModel.loadPanelVisible(true);
                $.getJSON(Appsalonbook.Utils.getUrlToken() + "&r=v1/site/userinfo"
               ).fail(function () {
                   DevExpress.ui.notify(Globalize.localize("CNX_ERROR"), 'error', 4500);
                   setTimeout(Appsalonbook.app.navigate("settings", { root: true }), 5000);
               }).done(function (data) {
                   viewModel.loadPanelVisible(false);
                   if (data.success) {
                       viewModel.username(data.content.username);
                       viewModel.email(data.content.email);
                       if (data.content.userRedsocials.length > 0) {
                           viewModel.facebinfo(true);
                           viewModel.redifo(data.content.userRedsocials[0].user_red_social_id);
                       }
                       viewModel.token(Appsalonbook.Utils.getToken());
                       viewModel.clientid(Appsalonbook.Utils.getUserId());
                       viewModel.isContentReady(true);

                   } else {
                       DevExpress.ui.notify(Globalize.localize("CNX_ERROR"), 'error', 4500);
                       setTimeout(Appsalonbook.app.navigate("settings", { root: true }), 5000);
                   }
               });
            }
            


        },
        logOut: logOut

    };

    return viewModel;
};