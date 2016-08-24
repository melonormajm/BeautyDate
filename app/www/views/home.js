Appsalonbook.home = function (params) {

    //console.log(params);

    var dataSourceSalon = new DevExpress.data.DataSource({
        load: function (loadOptions) {
				console.log(Appsalonbook.Utils.getUrlToken() + "&r=v1/salon/index1");
				console.log(JSON.stringify(loadOptions));
                return $.post(Appsalonbook.Utils.getUrlToken() + "&r=v1/salon/index1",
                loadOptions
                ).fail(function (data) {
                    console.log(data);
					DevExpress.ui.notify(Globalize.localize("CNX_ERROR"), 'error', 4500);
                    viewModel.loadPanelVisible(false);
                    Appsalonbook.Utils.showErr();
					//return [];
                }).pipe(function (data) {
                    if (data.success)
                        return data.content;
                    else {
                        return [];
                    }

                });

            
        }

    });


    /*var reloadList = function () {
        viewModel.loadDatasource();
    }*/

    /*var checkLoginInfo = function () {
        if(localStorage.getItem("beautyDateUsername") && localStorage.getItem("beautyDatePassword")){
            $.getJSON(Appsalonbook.Utils.getUrl() + "?auth&username=" + localStorage.getItem("beautyDateUsername") + "&passwd=" +  localStorage.getItem("beautyDatePassword")).fail(function () {
                //DevExpress.ui.notify(Globalize.localize("CNX_ERROR"), 'error', 4500);
                Appsalonbook.app.navigate("settings");
            }).done(function (data) {
                if(!data.result)
                    Appsalonbook.app.navigate("authForm");
            });
        } else{
            Appsalonbook.app.navigate("authForm");
        }

        
        
    }*/

    var search = function (obj) {
        if (obj.categid) {
            viewModel.dataSourceSalon.filter('categid', '=', obj.categid);
        }

        if(obj.favorite)
            viewModel.dataSourceSalon.filter('favorite', '=', 1);

        if (obj.query)
            viewModel.dataSourceSalon.filter('query', '=', obj.query);

        /*viewModel.dataSourceSalon.filter('name', 'contains', viewModel.name());*/
        viewModel.loadPanelVisible(true);
        viewModel.dataSourceSalon.load().done(function (data) {
            viewModel.loadPanelVisible(false);
            viewModel.listVisibleFlag(true);
          
        });
        //Appsalonbook.app.navigate("search");

       
    };

    var searchByCateg = function (categid) {
        viewModel.dataSourceSalon.filter('categid', '=', categid);
        viewModel.loadPanelVisible(true);
        viewModel.dataSourceSalon.load().done(function (data) {
            viewModel.loadPanelVisible(false);
            viewModel.listVisibleFlag(true);
            viewModel.searchVisibleFlag(false);
        });
        
    };

    var IsFavorite = function (data) {
        if(typeof data == "object" ){
            return data.length > 0;
        }        
        return false;
    }

    var renderRating = function (evaluacion) {
        
        evaluacion = parseInt(evaluacion);
        if (evaluacion < 0 && evaluacion > 5)
            evaluacion = 2;
        var html = "";
        for (var i = 0 ; i < evaluacion; i++)
            html += '<img src="images/start_y32.png"/>';
        return html;
    }

    var findQuery = function () {
        var sobj = {};    
        sobj.query = viewModel.searchQuery();
        viewModel.search(sobj);
        viewModel.showSearch(false);
    }

    var preFindQuery = function () {

        //setTimeout(viewModel.findQuery, 250);
		viewModel.findQuery();
    }


    /*Buscar como reiniciar el filtro*/
    var resetSearch = function () {
        viewModel.dataSourceSalon.filter(null);
        viewModel.dataSourceSalon.load();
        viewModel.searchQuery('');
        viewModel.showSearch(false);
        viewModel.title('Todos');
    }

    var viewModel = {
        title: ko.observable(''),
        loadPanelMessage: ko.observable("Cargando salones"),
        loadPanelVisible: ko.observable(false),
        //loadPanelVisible: ko.observable(true),
        listVisibleFlag: ko.observable(false),
        searchVisibleFlag: ko.observable(false),
        preFindQuery: preFindQuery,
        IsFavorite: IsFavorite,
        
        loadDatasource: function () {
            this.dataSourceSalon.load();
        },
        dataSourceSalon: dataSourceSalon,
        searchBtnHandler: function () {
           /* if (this.searchVisibleFlag()) {
                this.search();
            } else {
                this.searchVisibleFlag(true);
                this.listVisibleFlag(false);
            }*/
            viewModel.showSearch(true);
            
        },
        //viewShowing: checkLoginInfo,
        viewShowing: function () {
            //Appsalonbook.Utils.checkLogin();
           
        },
        viewShown: function () {
            var sobj = {};
            viewModel.title("");
            if (params.categid) {
                sobj.categid = params.categid;
                viewModel.title("");
                $.getJSON(Appsalonbook.Utils.getUrlToken() + "&r=v1/categoria/view&id=" + params.categid).fail(function () {
                    viewModel.title("Salones");
                    Appsalonbook.Utils.showErr();
                }).done(function (data) {
                    if (data.success) {
                        if (data.content.nombre)
                            viewModel.title(data.content.nombre.capitalize());
                    }                        
                });

            }
            if (params.favorite == "1") {
                sobj.favorite = 1;
                viewModel.title(Globalize.localize("FAVORITES"));
            }
                

            if (params.query) {
                sobj.query = params.query;
                viewModel.title(params.query + "...");
                viewModel.searchQuery(params.query);
            }
            

            search(sobj);
        },
        displayImg: function (imgs) {
            for (index = 0; index < imgs.length; ++index) {
                if (imgs[index].principal == "1") {
                   return imgs[index].url;
                }

            }
            return imgs.length > 0 ? imgs[0].url : "/images/no_image.png";

        },
        displayServ: function (servicios) {
            var result = "";
            var max = 4;
            for (index = 0; index < servicios.length; ++index) {
                ele = servicios[index];
                if (index != 0) {
                    result += "<span class='left'>," + ele.nombre + "</span>";
                }else
                    result += "<span class='left'>" + ele.nombre + "</span>";
                
                if (index > max) {
                    result += "<span class='left'>...</span>";
                    break;
                }


            }
            return result;
        },
        displayCateg: function (servicios) {
            var img = "";
            /*if (categorias.length > 0) {
                
                for (var i = 0; i < categorias.length ; i++) {
                    img += "<img class='img-categ-in-salon-list' src='" + categorias[i].thumbnail + "' />";
                }

              
                return img;
            }*/
            var categorias = [];
            for (var i = 0; i < servicios.length ; i++) {
                if (servicios[i].categoria != null && categorias.indexOf(servicios[i].categoria.id) == -1) {
                    img += "<img class='img-categ-in-salon-list' src='" + servicios[i].categoria.thumbnail + "' />";
                    categorias.push(servicios[i].categoria.id);
                }   
            }
            return img;
        },
        //Search
        search  : search,
        name: ko.observable(""),
        categories: ko.observable(Array()),
        searchByCateg: searchByCateg,
        renderRating: renderRating,
        searchQuery: ko.observable(''),
        findQuery: findQuery,
        resetSearch: resetSearch,
        showSearch : ko.observable(false) 

    }
    return viewModel;
};