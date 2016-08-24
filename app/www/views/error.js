Appsalonbook.error = function (params) {

    var viewModel = {
        errdsc: ko.observable(Globalize.localize("error_generico")),
        goSettings: function(){
            Appsalonbook.app.navigate("settings");
        },
        viewShown: function () {
            if (params.err) {
                viewModel.errdsc(params.err);
            }
        }
    };

    return viewModel;
};