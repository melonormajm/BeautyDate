Appsalonbook.authform = function (params) {




	try{
		openFB.init({ appId: Appsalonbook.config.app.fb.id });
	}catch(Exception){
		//alert("Facebook exception init");
	}
    var authenticateWait = function () {
        console.log('Entro aqui');
        setTimeout(viewModel.authenticate, 500);
    }


    var authenticate =  function (vg) {
             
        var result = {isValid : true};
        if (!viewModel.username() || !viewModel.passwd()) {
            result.isValid = false
            result.message = Globalize.localize("USERNAME_PASSWORD_REQUIRED");
        }


        if (result.isValid) {
            viewModel.loadPanelVisible(true);
            Appsalonbook.Utils.authenticate(viewModel.username(), viewModel.passwd(), function () {
                viewModel.loadPanelVisible(false);
                Appsalonbook.Utils.setLastAuthUsed("LOCAL");
                //if()
                //setTimeout(Appsalonbook.app.navigate("search", { root: true }), 2000);
				//Appsalonbook.app.navigate("search", { root: true });
                if(Appsalonbook.app.canBack())
                    Appsalonbook.app.back();
                else
                    Appsalonbook.app.navigate("search", { root: true });
            }, function () {
                viewModel.loadPanelVisible(false);
                DevExpress.ui.notify(Globalize.localize("AUTH_ERROR_USERNAME_OR_PASS_INCORRECT"), 'error', 4500);
            },
                viewModel.remenber(),
               function(){
                   viewModel.loadPanelVisible(false);
                   DevExpress.ui.notify(Globalize.localize("CNX_ERROR"), 'error', 4500);
                   //Appsalonbook.Utils.showErr();
               }
            );

        } else {
            DevExpress.ui.notify(result.message, 'error', 3000);
        }
    }




    var settingGo = function () {
        Appsalonbook.app.navigate("settings");
    }

    var forgotPassword = function () {
        var email = viewModel.emailback();
        if (email == '') {
            DevExpress.ui.notify("Debe especificar una dirección de correo válida", 'error', 2000);
            return;
        }
        /*else if()
        {
            DevExpress.ui.notify("Debe especificar una dirección de correo válida", 'error', 2000);

        }*/


        $.post(Appsalonbook.Utils.getUrlToken() + "&r=v1/site/request-password-reset", {
            "PasswordResetRequestForm[email]" : email
        }).done(function (data) {
            if (data.success) {
                DevExpress.ui.notify("Se le ha  enviado un correo para recuperar su contraseña", 'info', 3000);

            } else {
                DevExpress.ui.notify("No pudo ser enviado el correo de recueracion de contraseña", 'error', 3000);
            }
            viewModel.popupVisible(false);

        }).fail(function (data) {
            DevExpress.ui.notify("No pudo ser enviado el correo de recueracion de contraseña", 'error', 3000);
            viewModel.popupVisible(false);

        });
    }

    var goRegister = function () {
        try {
            navigator.notification.confirm(Globalize.localize("CONFIRM_COOKIE"), function (pressed) {

                if (pressed == 2) {
                    Appsalonbook.app.navigate("register");
                }

            }, "Confirmar", "No,Si");
        } catch (Exception) {
            if (confirm(Globalize.localize("CONFIRM_COOKIE"))) {
                Appsalonbook.app.navigate("register");
            }
        }
    }

    var goResetPassword = function () {
        Appsalonbook.app.navigate("forgotpasswd");
    }

    

    var authFB = function () {

        openFB.getLoginStatus(function (loginStatus) {
            if (loginStatus.status == 'connected') {
                    //alert("Usuario conectado");
                    Appsalonbook.Utils.setLastAuthUsed("FACEBOOK");
                    viewModel.getFaceBookInfo();
                } else {
                    //alert("Usuario no conectado ir al login de face");
                    openFB.login(
                        function (response) {
                            if (response.status === 'connected') {
                                //alert('Facebook login succeeded, got access token: ' + response.authResponse.token);

                                viewModel.getFaceBookInfo();

                            } else {
                                //alert('Facebook login failed line: 117: ' + response.error);
                            }
                        }, { scope: 'email,read_stream' } /*{ scope: 'email,read_stream,publish_stream' }*/);

                }
        });

        

    }

    var getFaceBookInfo = function () {
        //alert("Obteniendo informacion de usuario");
        openFB.api({
            path: '/me',
            success: function (data) {
                viewModel.facebookUserInf(data);
                //alert("facebook info: " + JSON.stringify(data));
                Appsalonbook.Utils.setLastAuthUsed("FACEBOOK");
                //viewModel.registerUserInfo("FACEBOOK");
                //viewModel.facebookUserInf(fc);
                Appsalonbook.FBINFO = data;
                viewModel.loadPanelVisible(true);

                $.getJSON(Appsalonbook.Utils.getUrl() + "?r=v1/site/checksocial",
                            {
                                rs: 'FACEBOOK',
                                rsid: data.id
                            }
                            ).done(function (response) {
                                //alert("checksocial sucess:  " + JSON.stringify(response));
                                if (response.success) {
                                    viewModel.isRegisteredBefore(response.content.registered);
                                    viewModel.registerUserInfo("FACEBOOK");
                                }


                            }).fail(function (response) {

                                //alert("checksocial fail:  " + JSON.stringify(response));
                            });

            },
            error: function (error) {
                //alert("Error obteniendo la info de face: " + error.message);
                //Se producjo un error obteniendo la informacion del usuario
                //pero este esta conectado hay que seguir
                //Appsalonbook.config.site.credentials.token = localStorage.getItem("beautyDateToken");
                
                Appsalonbook.Utils.enableLoginState();
                Appsalonbook.app.navigate("search", { root: true });
            }

        });
    }


    var registerUserInfo = function (social_network) {

        var info = viewModel.facebookUserInf();
        var userid = Appsalonbook.Utils.getUserId();
        
        localStorage.setItem("beautyaDateFacebookId", info.id);

        info["beauty_social_type"] = social_network;

       // alert("registerUserInfo: antes ");

        if (!viewModel.isRegisteredBefore()) {
            if (!info.email || !info.name) {
                try {
                    Appsalonbook.FBINFO = info;
                    //alert("Antes del navegate");
                   // viewModel.loadPanelVisible(false);
                    viewModel.goProfile();
                    return;
                    //Appsalonbook.app.navigate('search');

                } catch (Exception) {
                    //alert(Exception.message);
                }
                return;

            }
        }

        $.post(Appsalonbook.Utils.getUrl() + "?r=v1/site/loginsocial",
                info
                ).fail(function (data) {
                    viewModel.loadPanelVisible(false);
                    //DevExpress.ui.notify(JSON.stringify(data), 'error', 4500);
                    //alert("loginsocial line 176: " + JSON.stringify(data));
                    //setTimeout(reloadList, 5000);
                    DevExpress.ui.notify("Error de conexión", 'error', 4500);
                }).done(function (data) {
                    viewModel.loadPanelVisible(false);
                    if (data.success) {
                        if (data.content.user_id)
                            Appsalonbook.Utils.setUserId(data.content.user_id);
                        if (data.content.token) {
                            Appsalonbook.Utils.setToken(data.content.token);
                            Appsalonbook.Utils.enableLoginState();
                        }

                        if(Appsalonbook.app.canBack())
                            Appsalonbook.app.back();
                        else
                            Appsalonbook.app.navigate("search", { root: true });
                        //Appsalonbook.app.navigate("search", { root: true });

                    } else {
                        //Por alguna razon fallo el registro de la informcion
                        //Aumiremos que fue un problema de conexio
                        //alert("Error de conexión line 195: " + JSON.stringify(data));
                        //DevExpress.ui.notify("Error de conexión line 195", 'error', 4500);
                    }

                });
    }


    var test = function () {

        var fc = {
            id: "10",
            first_name: "pruebajjjj",
            last_name: "pruebalast",
            link: "http://mu.link"
        };

        viewModel.facebookUserInf(fc);
        Appsalonbook.FBINFO = fc;
        viewModel.loadPanelVisible(true);

        $.getJSON(Appsalonbook.Utils.getUrl() + "?r=v1/site/checksocial",
                    {
                        rs: 'FACEBOOK',
                        rsid: fc.id
                    }
                    ).done(function (response) {
                        //alert("checksocial sucess:  " + JSON.stringify(response));
                        if (response.success) {
                            viewModel.isRegisteredBefore(response.content.registered);
                            viewModel.registerUserInfo("FACEBOOK");
                        }


                    }).fail(function (response) {

                        //alert("checksocial fail:  " + JSON.stringify(response));
                    });

       
    }




    var showPopup = function () {
    
        viewModel.emailback("");
        viewModel.popupVisible(true);

    }






    var showInfo = function () {
        viewModel.token(localStorage.getItem("beautyDateAuthType_FB_TOKEN"));
        viewModel.facbinf(localStorage.getItem("beautyDateAuthType_FB"));
        viewModel.popupVisible(true);
    }

    var viewModel = {
        vg: {},
        goProfile: function () {
            Appsalonbook.app.navigate("completeinfo");
        },
        isRegisteredBefore: ko.observable(false),
        loadPanelMessage: ko.observable("Autenticando ..."),
        loadPanelVisible: ko.observable(false),
        //username: ko.observable(""),
        //passwd: ko.observable(""),
        remenber: ko.observable(""),
        passwd : ko.observable(''),
        username : ko.observable(''),
        emailback: ko.observable(""),
        facebookUserInf : ko.observable(''),
        enableAuthBtn: ko.observable(false),
        authenticateWait: authenticateWait,
        authenticate: authenticate,
        settingGo: settingGo,
        forgotPassword: forgotPassword,
        goRegister: goRegister,
        goResetPassword: goResetPassword,
        authFB: authFB,
        //authFB: test,
        popupVisible: ko.observable(false),
        facbinf: ko.observable(''),
        token: ko.observable(''),
        showInfo: showInfo,
        changeUser: function (a) {

            console.log("changUser" + a);
        },
        changePass: function (a) {
            console.log("changePass" + a);
        },
        viewShown: function (args) {
            viewModel.loadPanelVisible(false);
            if(params.backurl){
                Appsalonbook.backurl = params.backurl;
            }
        },
        showPopup: showPopup,
        registerUserInfo: registerUserInfo,
        valueChange: function (e) {
            viewModel.passwd();
            viewModel.username();
        },
        getFaceBookInfo: getFaceBookInfo

    };

    return viewModel;
};