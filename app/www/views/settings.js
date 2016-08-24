Appsalonbook.settings = function (params) {

    var saveCfg = function () {
        if (viewModel.especificaUrl() != "" || viewModel.urlsitio() != "") {
            viewModel.loadPanelVisible(true);
            localStorage.setItem("beautyDateUrl", viewModel.especificaUrl() ? viewModel.especificaUrl() : viewModel.urlsitio().text);

            //localStorage.setItem("beautyDateAuthType", viewModel.authType());
            //$.getJSON(Appsalonbook.Utils.getUrlToken() + "&r=siter/test").fail(function () {
            $.getJSON(Appsalonbook.Utils.getUrlToken() + "&r=v1/site/test").fail(function () {
                viewModel.loadPanelVisible(false);
                DevExpress.ui.notify(Globalize.localize("WRONGCFG"), 'error', 4500);
            }).done(function (data) {
                viewModel.loadPanelVisible(false);
                DevExpress.ui.notify(Globalize.localize("RIGHTCFG"), 'info', 2000);
                setTimeout(function(){
                    Appsalonbook.app.navigate("search", { root: true });
                    }, 2000
                );
               
            });
           
        }else
            DevExpress.ui.notify(Globalize.localize("ERROR_MSG_SITE_URL"), 'error', 4500);
    }

    var clearCfg = function () {
        localStorage.removeItem("beautyDateUsername");
        localStorage.removeItem("beautyDatePassword");
    }

  
    var viewModel = {
        title: "Configuracion v " + Appsalonbook.config.app.version,
        loadPanelMessage: ko.observable("Comprobando ..."),
        loadPanelVisible: ko.observable(false),
        urlsitio: ko.observable(Appsalonbook.Utils.getUrl()),
        especificaUrl: ko.observable(''),
        authType: ko.observable(),
        saveCfg: saveCfg,
        clearCfg: clearCfg
    };

    return viewModel;
};