Appsalonbook.search = function (params) {
    
    console.log(params);


    var validationGroup = "sampleGroup";

    var search = function (params) {
        //Appsalonbook.app.navigate("home///" + viewModel.name());

    }

    var resetSearch = function(){
        viewModel.searchQuery('');
        viewModel.showSearch(false);
    }

    var searchByCateg = function (categ) {
        console.log(categ);
        if (params.view == "search" && categ && categ.id)
            Appsalonbook.app.navigate("home//"+ categ.id );
    }

    var searchBtnHandler = function () {

        viewModel.showSearch(true);
    }

    var findQuery = function () {

        /*setTimeout(function () {
            if (viewModel.searchQuery() != '') {
                Appsalonbook.app.navigate("home///" + viewModel.searchQuery());
                viewModel.showSearch(false);
            }
            else {
                DevExpress.ui.notify('Debe ingresar algun texto para buscar', 'error', 4500);
            }
        }, 250);*/
		 if (viewModel.searchQuery() != '') {
                Appsalonbook.app.navigate("home///" + viewModel.searchQuery());
                viewModel.showSearch(false);
		}
		else {
			DevExpress.ui.notify('Debe ingresar algun texto para buscar', 'error', 4500);
		}
		

    }

    

    var viewModel = {
        search: search,
        resetSearch: resetSearch,
        name: ko.observable(""),
        categories: ko.observable(Array()),
        searchByCateg: searchByCateg,
        contentReady: ko.observable(true),
        loadPanelVisibleSearch: ko.observable(false),
        loadPanelMessageSearch: ko.observable("Cargando ..."),
        showSearch: ko.observable(false),
        searchQuery: ko.observable(''),
        findQuery: findQuery,
        showFooter: ko.observable(true),
        //categCntHeight: ko.observable(height + 'px'),
        loadCateg: function (data) {
            viewModel.loadPanelVisibleSearch(true);

            $.getJSON(Appsalonbook.Utils.getUrlToken() + "&r=v1/categoria/index").fail(function (data) {
                viewModel.loadPanelVisibleSearch(false);

				Appsalonbook.Utils.showErr();

            }).done(function (data) {

                viewModel.loadPanelVisibleSearch(false);
                if (data.success) {
                    viewModel.categories(data.content);
                    
                    //viewModel.contentReady(true);
                    var wh = $(window).height();
                    var hlogo = $('#search-img-cnt').height() + 70;
                    var fh = $('#my-footer-cat').height();
                    var margin = 100;
                    var h = $('.imgcat').height() + 15;

                    if ((wh - hlogo - fh - margin) > (h * 4)) {
                        $('#categ-cnt').height(h * 4);
                    } else
                        $('#categ-cnt').height(h * 3);
                    
                    //alert("H imgcat: " + h + ", H categ-cnt" + $('#categ-cnt').height());

                    try{
						var sce= $("#categ-cnt");
						sce.dxScrollView({});
						sce.dxScrollView({ showScrollbar: 'always' });						
						sce.data("dxScrollView").scrollTo(0);
                    }catch(Exception){
						console.log("Esto no esta soportado en esta version");
					}
					setTimeout(function () {
                        try{
							$('#categ-cnt .dx-scrollbar-vertical .dx-scrollable-scroll').removeClass('dx-state-invisible');
						}
						catch(Execption){
							console.log("Esto no esta soportado en esta version");
						}
                    }, 500);
 
                } else {
                    DevExpress.ui.notify(Globalize.localize("CNX_ERROR"), 'error', 4500);
                    //Appsalonbook.app.navigate("settings", { root: true });
                    Appsalonbook.Utils.showErr();
                }
                                
               
            });
        },
        failFn: function (data) {
            viewModel.loadPanelVisibleSearch(false);
            DevExpress.ui.notify(Globalize.localize("Auth_ERROR"), 'error', 4500);
            Appsalonbook.app.navigate("authForm", { root: true });
        },
        viewShown: function () {
           
			
			//Esto se comentaria para poder mostrar los casas sin estar registados
			
			/*if (!Appsalonbook.Utils.getLoginState()) {
                Appsalonbook.Utils.disableLoginState();
                Appsalonbook.app.navigate('authForm', { root: true });
                return;
            } else {
                //Appsalonbook.Utils.enableLoginState();
                viewModel.loadCateg();
            }*/
			
			Appsalonbook.Utils.getLoginState();
            viewModel.loadCateg();    


            
        },
        searchBtnHandler: searchBtnHandler
    };

    
   

    return viewModel;
};