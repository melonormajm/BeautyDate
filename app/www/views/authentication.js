Appsalonbook.authentication = function (params) {


    var goCfg = function () {
        Appsalonbook.app.navigate("settings");
    }

    var goSalones = function () {
        Appsalonbook.app.navigate("home", { root: true });
    }

    var siteLogForm = function () {

    }

    var initFn = function () {
        //viewModel.progressValue(10);
        viewModel.loadPanelVisible(true);
        $.getJSON(Appsalonbook.Utils.getUrl() + "?r=salonr/test").fail(function () {
            viewModel.loadPanelVisible(false);
            DevExpress.ui.notify(Globalize.localize("CNX_ERROR"), 'error', 10000);
            viewModel.enableCfgLink(true);
        }).done(function (data) {
            //viewModel.progressValue(20);
            var result = Appsalonbook.Utils.checkLogin();
            if (result == true) {
                //viewModel.progressValue(100);
                viewModel.loadPanelVisible(false);
                Appsalonbook.app.navigate("home", {root: true});
            } else if (result == false) {
                viewModel.loadPanelVisible(false);
                Appsalonbook.app.navigate("authForm");
            } else
                result.done(function (data) {
                    //viewModel.progressValue(100);
                    viewModel.loadPanelVisible(false);
                    if (data.result) {
                        Appsalonbook.config.site.credentials.loginState = true
                        viewModel.enableSalones(true);
                    } else {
                        Appsalonbook.app.navigate("authForm");
                    }

                });

        });

    };

    var viewModel = {
        registrarse: "Registrarse",
        goCfg: goCfg,
        goSalones: goSalones,
        siteLogForm: siteLogForm,
        visibleFlag: ko.observable(false),
        loadPanelMessage: ko.observable("Inicializando ..."),
        loadPanelVisible: ko.observable(false),
        progressValue: ko.observable(0),
        viewShown: function () {
            initFn();
        },
        enableCfgLink: ko.observable(false),
        enableSalones: ko.observable(false)
    };

    return viewModel;
};