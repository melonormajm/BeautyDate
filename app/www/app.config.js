//"url": "http://www.cubatoprentals.com"
// NOTE object below must be a valid JSON
window.Appsalonbook = $.extend(true, window.Appsalonbook, {
  "config": {
    "layoutSet": "slideout",
    /*"navigation": [
     {
         "title": "Inicio",
         "action": "#search",
         "icon": "myhome",
         "visible": ko.observable(true)
     },
      {
          "title": "Mis Reservaciones",
          "action": "#reservList",
          "icon": "myreservaciones",
          //"visible": ko.observable(false)
          "visible": ko.observable(true)

      },
      {
          "title": "Mis favoritos",
          "action": "#home////1",
          "icon": "myfav",
          //"visible": ko.observable(false)
          "visible": ko.observable(true)

      },
      {
          "title": "Promociones",
          "action": "#promotionList",
          "icon": "mypromociones",
          //"visible": ko.observable(false),
          "visible": ko.observable(true)

      },
      {
          "title": "Mis votaciones",
          "action": "#votacion",
          "icon": "votacion",
          //"visible": ko.observable(false)
          "visible": ko.observable(true)

      },
      {
          "title": Globalize.localize("SETTINGS"),
          "action": "#settings",
          "icon": "conf",
          //"visible": ko.observable(false)
          "visible": ko.observable(true)
      },
      {
          "title": Globalize.localize("PERFIL"),
          "action": "#perfil",
          "icon": "perfil",
          //"visible": ko.observable(false)
          "visible": ko.observable(true)

      },
      {
          "title": Globalize.localize("MENU_ADD_SALON"),
          "action": "#saloncrear",
          "icon": "add"
      },
      {
          "title": "Quienes somos",
          "action": "#about",
          "icon": "mysomos"
      },
       {
           "title": "Salir",
           "action": "#salir",
           "icon": "salir",
          // "visible": ko.observable(false)
           "visible": ko.observable(true)
       }
     
    ]*/
    "commandMapping": {
      "ios-header-toolbar": {
        "commands": [
          {
            "id": "add",
            "location": "right",
            "showIcon": true,
            "showText": false
          }
        ]
      },
      "android-header-toolbar": {
      "commands": [
          {
            "id": "add",
            "location": "right"
            /*"showIcon": true,
            "showText": true*/
          }
        ]
      }
    },
    "site": {
      "url": "http://api.beautydate.mx",
      "credentials": {
        "username": "juanma",
        "password": "juanma1",
        "loginState": false,
        "token": null
      },
      "testFlag": true,
      "authType": "site"
    },
    "app": {
      "name": "Beauty Date",
      "version": "1.0.1.1",
      "lastAuthUsed": "",
      "fb": {
        "id": "615242208611974"
      },
      "debug": false,
      "nav_by_rol": {

          "publico": [
                  {
                      "title": "Inicio",
                      "action": "#search",
                      //"icon": "myhome",
                      "icon": "home",
                      "id": "inicio"
                  },
				  {
  
					  "title": "Promociones",
					  "action": "#promotionlist",
					  //"icon": "mypromociones",
					  "icon": "percent",
					  "id": "mypromociones"
					},
                  {
                      "title": Globalize.localize("MENU_ADD_SALON"),
                      "action": "#saloncrear",
                      //"icon": "add",
                      "icon": "plus",
                      "id": "addsalon"
                  },
                  {
                      "title": "Quienes somos",
                      "action": "#about",
                      //"icon": "mysomos",
                      "icon": "help",
                      "id":"quienesomos"
                  },
				  {
						"title": Globalize.localize("INICIAR_SESION"),
						"action": "#authform",
						"icon": "user"
				},
				{
				  "title": Globalize.localize("PERFIL"),
				  "action": "#settings",
				  "icon": "perfil",
				  //"visible": ko.observable(false)
				  "visible": true

			  }
              ],
          "registered": [
                            {
                                "title": "Inicio",
                                "action": "#search",
                                "icon": "home"
                                //"id": "inicio"
                            },
                            {
                                "title": "Mis Reservaciones",
                                "action": "#reservList",
                                "icon": "edit"
                                //"id": "myreservaciones",
                             },
                            {   
                                "title": "Mis favoritos",
                                "action": "#home////1",
                                //"icon": "myfav",
                                "icon": "favorites"
                                //"id": "myfav"
                            },
							{  
							  "title": "Promociones",
							  "action": "#promotionlist",
							  "icon": "percent"
							  //"id": "mypromociones"
							},
                            {
                              "title": "Mis votaciones",
                              "action": "#votacion",
                              "icon": "like"
                              //"id": "votacion"
                            },
                            {
                                "title": Globalize.localize("PERFIL"),
                                "action": "#perfil",
                                "icon": "user"
                                //"id":"perfilid"

                            },
                            {
                                "title": Globalize.localize("MENU_ADD_SALON"),
                                "action": "#saloncrear",
                                "icon": "plus"
                               //"id" : "addid"

                            },
                            {
                                "title": "Quienes somos",
                                "action": "#about",
                                "icon": "help"
                                //"id": "quienesomos"
                            },
                            {
                                "title": "Salir",
                                "action": "#salir",
                                "icon": "close"
                                //"id": "salinid"
                            }
                        ]  
                }

      }
    }
  
});