Appsalonbook.salir = function (params) {

    try{
		openFB.init({ appId: Appsalonbook.config.app.fb.id });
	}catch(Exception){
		alert("Fecabook config error");
	}


    var logOut = function () {
	
			localStorage.removeItem("beautyDateUsername");
			localStorage.removeItem("beautyDatePassword");
			Appsalonbook.Utils.unsetToken();
			Appsalonbook.Utils.disableLoginState();
			localStorage.removeItem("beautyDatePassword");
			localStorage.removeItem("beautyDateRecordar");
			
			switch(Appsalonbook.Utils.getLastAuthUsed()){
			
				case 'FACEBOOK':
					openFB.getLoginStatus(function (loginStatus) {
						if (loginStatus.status == 'connected') {
							//alert("Usuario conectado, vamos a desconectarlo");
							openFB.logout(function () {
								viewModel.goToAuthForm();
							});
						}else{
							viewModel.goToAuthForm();
						
						}
					});
					break;
				default:
					viewModel.goToAuthForm();
			
			
			
			}  
		

    }




    var viewModel = {
        loadPanelMessage: ko.observable(Globalize.localize("LOGOUT_MSG")),
        loadPanelVisible: ko.observable(true),
		logOut:logOut,
        viewShown: function (args) {
            viewModel.logOut();
			//setTimeout(logOut, 2000);
        },
        goToAuthForm: function () {
            try{
				viewModel.loadPanelVisible(false);
				Appsalonbook.app.navigate("authform", { root: true });
				//Appsalonbook.app.navigate("search", { root: true });
			}catch(Exception){
				alert("Error cerrando session");
			}
			
        }

    };
    return viewModel;
};