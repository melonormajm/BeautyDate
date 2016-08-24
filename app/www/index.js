window.Appsalonbook = window.Appsalonbook || {};

$(function() {
    // Uncomment the line below to disable platform-specific look and feel and to use the Generic theme for all devices
    // DevExpress.devices.current({ platform: "generic" });
    // To customize the Generic theme, use the DevExtreme Theme Builder (http://js.devexpress.com/ThemeBuilder)
    // For details on how to use themes and the Theme Builder, refer to the http://js.devexpress.com/Documentation/Howto/Themes article

    //$.ajaxSetup({ timeout: 3000 });
    
    Appsalonbook.Utils = {

        showErr: function (errd) {
            /*if (Appsalonbook.config.app.debug) 
                Appsalonbook.app.navigate("settings", { root: true });
            else*/
            Appsalonbook.app.navigate("error", {root: true});
        },
        getAppName: function () {
            return Appsalonbook.config.app.name;
        }
        ,
         formatTime : function (data) {

            if (typeof data == "number")
                data = data.toString();
            else if (data == null) return "";

            var hour = parseInt(data.substring(0, 2));
            var minute = data.substring(2);
            var civilhour = hour;
            var ampm = "am";
            if (hour > 12) {
                civilhour = hour - 12;
                ampm = "pm"
            } else if (hour == 12) {
                civilhour = 12;
                ampm = "pm";
            }
        
            return civilhour.toString() + ":" + minute + " " + ampm;
        
         },

         formatCurrency : function(price, simbolo){
             return simbolo + " " + price;
         
         },
         setLastAuthUsed: function (auth) {
             Appsalonbook.config.app.lastAuthUsed = auth;
         },
         getLastAuthUsed: function () {
             return Appsalonbook.config.app.lastAuthUsed;
         },
        checkCnx: function () {
        

        },
        getToken: function () {
            return Appsalonbook.config.site.credentials.token;

        },

        unsetToken: function () {
            Appsalonbook.config.site.credentials.token = undefined;
            localStorage.removeItem("beautyDateToken");
        },
        setToken: function (token, recordar) {
            Appsalonbook.config.site.credentials.token = token;
            if (recordar) {
                localStorage.setItem("beautyDateRecordar", true);
                localStorage.setItem("beautyDateToken", token);
            }else
                localStorage.removeItem("beautyDateRecordar");
            
        },
        enableLoginState: function () {
            try {
                                         
                /*var sce = $(".dx-slideout-menu");
                sce.dxScrollView({});
                sce.dxScrollView({ showScrollbar: 'always' });

                sce.data("dxScrollView").scrollTo(0); */
                Appsalonbook.app.createNavigation(Appsalonbook.config.app.nav_by_rol.registered);
                Appsalonbook.app.renderNavigation();

                /*var sce = $(".dx-slideout-menu");
                sce.dxScrollView({});
                sce.dxScrollView({ showScrollbar: 'always' });
                sce.data("dxScrollView").scrollTo(0);*/

            } catch (Exception) {
                alert(Exception.message);
            }

            Appsalonbook.config.site.credentials.loginState = true;
        },
        disableLoginState: function () {
            try {                
                Appsalonbook.app.createNavigation(Appsalonbook.config.app.nav_by_rol.publico);
                Appsalonbook.app.renderNavigation();
            } catch (Exception) {
                    alert(Exception.message);
            }

            Appsalonbook.config.site.credentials.loginState = false;
        },

        

        getLoginState: function () {
            if (localStorage.getItem("beautyDateRecordar") && localStorage.getItem("beautyDateToken")) {
                Appsalonbook.config.site.credentials.token = localStorage.getItem("beautyDateToken");
                Appsalonbook.Utils.enableLoginState();
                return true;
                //return Appsalonbook.config.site.credentials.loginState = true;
            }
            return Appsalonbook.config.site.credentials.loginState;
        },

        getUrl: function () {
            //return localStorage.getItem("beautyDateUrl") != null ? localStorage.getItem("beautyDateUrl") : Appsalonbook.config.site.url + "/api/web/index.php";
            return localStorage.getItem("beautyDateUrl") != null ? localStorage.getItem("beautyDateUrl") : Appsalonbook.config.site.url;
        },


        getUrlToken: function () {
            return Appsalonbook.Utils.getUrl() + "?token=" + Appsalonbook.config.site.credentials.token;
        },


        setUserId: function(userid){
            localStorage.setItem("beutyDateUserId", userid);
        },
        getUserId: function () {
            return localStorage.getItem("beutyDateUserId");
        },

        getAuthType: function () {
            return localStorage.getItem("beautyDateAuthType") != null ? localStorage.getItem("beautyDateAuthType") : Appsalonbook.config.site.authType;

        },
        checkLogin: function (doneFn, failFn) {
            if (Appsalonbook.config.site.credentials.loginState)
                doneFn();
            else if (localStorage.getItem("beautyDateUsername") && localStorage.getItem("beautyDatePassword")) {
                Appsalonbook.Utils.authenticate(localStorage.getItem("beautyDateUsername"), localStorage.getItem("beautyDatePassword"), doneFn, failFn);             

            } else
                Appsalonbook.app.navigate("authForm", {root: true});

        },
        authenticate : function (username, password, doneFn, failFn, recordar, failCnx) {
            $.getJSON(Appsalonbook.Utils.getUrl() + "?r=v1/site/login&username=" + username + "&pass=" + password).fail(function (data) {
                    //Appsalonbook.Utils.showErr();
                    failCnx();
                }).done(function (data) {
                     
                        //localStorage.setItem("beautyDateUsername", username);
                        //localStorage.setItem("beautyDatePassword", password);
					try{
                        if (/*data.result == "SUCCESS"*/data.success) {
                            if (data.content) {
                                Appsalonbook.Utils.setToken(data.content.token, recordar);
                                Appsalonbook.Utils.setUserId(data.content.user_id);
                                Appsalonbook.Utils.enableLoginState();    
                                localStorage.setItem("beautyDateRecordar", true);

                                if (doneFn) {
                                    doneFn(data);
                                }
                                else
                                    Appsalonbook.app.navigate("search", { root: true });
                            } else {
                                if (failFn)
                                    failFn(data);
                                else
                                    DevExpress.ui.notify(Globalize.localize("AUTH_ERROR_USERNAME_OR_PASS_INCORRECT"), 'error', 4500);
                            }
                            
                            
                        } else {

                            if (failFn)
                                failFn(data);
                            else
                                DevExpress.ui.notify(Globalize.localize("Auth_ERROR"), 'error', 4500);

                        }

                    }catch(Exception){
						alert("Error de autenticacion success");
					}    
                });          
            
        }

        ,
        registerUserRedSocial: function (red, name, firstname, lastname, email, nid, link, token) {
            $.post(Appsalonbook.Utils.getUrl() + "?r=v1/site/registersocial&XDEBUG_SESSION_START=phpstorm",
             {
                 "red": red,
                 "name": name,
                 "firstname": firstname,
                 "lastname": lastname,
                 "email": email,
                 "nid": nid,
                 "link": link,
                 "token": token

             }
             ).fail(function () {
                 DevExpress.ui.notify(Globalize.localize("CNX_ERROR"), 'error', 4500);
                 //setTimeout(reloadList, 5000);
             }).done(function (data) {
                 if (data.success) {
                     DevExpress.ui.notify(Globalize.localize("Usuario registrado"), 'info', 3500);
                     Appsalonbook.config.site.credentials.token = data.content;
                     setTimeout(Appsalonbook.app.navigate("account", { root: true }), 4000);
                 }

                });



        }

    };

   



    function onDeviceReady() {
        navigator["splashscreen"].hide();
        checkConnection();

        if (window.devextremeaddon != null) {
            window.devextremeaddon.setup();
        }
        document.addEventListener("backbutton", onBackButton, false);
      
        try {
            window.StatusBar.hide();
        } catch (Exception) {

        }

    }
    

    function onBackButton(param) {

        DevExpress.processHardwareBackButton();
    }

    function onNavigatingBack(e) {
        //console.log(e);
        if (e.isHardwareButton && !Appsalonbook.app.canBack()) {
            e.cancel = true;
            exitApp();
        }
    }

    function exitApp() {

        //if (confirm(Globalize.localize("ARE_YOU_WANT_EXIT"))) {

        navigator.notification.confirm(Globalize.localize("ARE_YOU_WANT_EXIT"), function (pressed) {

            if (pressed == 2) {
                switch (DevExpress.devices.real().platform) {
                    case "tizen":
                        window["tizen"].application.getCurrentApplication().exit();
                        break;
                    case "android":
                        navigator["app"].exitApp();
                        break;
                    case "win8":
                        window.external.Notify("DevExpress.ExitApp");
                        break;
                }
            }
            
        }, "Confirmar", "No,Si");
        
    }

    //Globalize.culture("es");

    Appsalonbook.app = new DevExpress.framework.html.HtmlApplication({
        namespace: Appsalonbook,
        layoutSet: DevExpress.framework.html.layoutSets[Appsalonbook.config.layoutSet],
        //navigation: Appsalonbook.config.navigation,
        navigation: Appsalonbook.config.app.nav_by_rol.publico,
        commandMapping: Appsalonbook.config.commandMapping,
        disableCache: true 
    });

    //document.addEventListener("deviceready", onDeviceReady, false);

    
    Appsalonbook.app.router.register(":view/:id/:categid/:query/:favorite/:promocionid/:backurl", { view: "", id: undefined, categid: undefined, query : undefined, favorite: undefined, promocionid: undefined, backurl : 'search'});
    Appsalonbook.app.on("navigatingBack", onNavigatingBack);

    Appsalonbook.cart = {};
    Appsalonbook.states = {};
    Appsalonbook.user = { username: "" };
    Appsalonbook.FBINFO = {};
	Appsalonbook.backurl = "search";



    var device = DevExpress.devices.current();

    function setScreenSize() {
        var el = $("<div>").addClass("screen-size").appendTo(".dx-viewport");
        var size = getComputedStyle(el[0], ":after").content.replace(/"/g, "");
        el.remove();
        device.screenSize = size;
    };
    $(window).bind("resize", setScreenSize);
    setScreenSize();

    String.prototype.capitalize = function () {
        return this.replace(/(^|\s)([a-z])/g, function (m, p1, p2) { return p1 + p2.toUpperCase(); });
    };

    Appsalonbook.app.navigate('search');
    $.ajaxSetup({
        //async: false,
        cache: false
	
    });
    
    document.addEventListener("deviceready", onDeviceReady, false);
});


document.addEventListener("deviceready", checkConnection, false);

function checkConnection() {
    
    var networkState = navigator.network.connection.type;
    var states = {};
    states[Connection.UNKNOWN] = 'Unknown connection';
    states[Connection.ETHERNET] = 'Ethernet connection';
    states[Connection.WIFI] = 'WiFi connection';
    states[Connection.CELL_2G] = 'Cell 2G connection';
    states[Connection.CELL_3G] = 'Cell 3G connection';
    states[Connection.CELL_4G] = 'Cell 4G connection';
    states[Connection.NONE] = 'No network connection';
    
    if (networkState == Connection.NONE) {
        navigator.notification.confirm(Globalize.localize("NOT_NETWORK_ARE_YOU_WANT_EXIT"), function (pressed) {

            if (pressed == 2) {
                switch (DevExpress.devices.real().platform) {
                    case "tizen":
                        window["tizen"].application.getCurrentApplication().exit();
                        break;
                    case "android":
                        navigator["app"].exitApp();
                        break;
                    case "win8":
                        window.external.Notify("DevExpress.ExitApp");
                        break;
                }
            }

        }, "Confirmar", "No,Si");

    }
    

}
