Appsalonbook.fb = function (params) {
    /*

    $("#editor1").dxTextBox({
        placeholder: 'Type username',
        maxLenght: 10
    });


    $("#editor2").dxTextBox({
        placeholder: 'Type password',
        maxLenght: 10,
        mode: 'password'
    });






    






    var auth = function () {

        
    }*/

    var valueTx = ko.observable("");



    var testFn = function () {
        console.log("aaaa");
        navigator.notification.confirm("El mensaje", function (pressed) {

            if (pressed == 1) {
                alert("Aceptar");
            } else if (pressed == 2) {
                alert("Salir");

            }
            
        }, "Mi titulo", "Aceptar,Salir");

            
        

    }

    



    var viewModel = {
        //  Put the binding properties here'
        //viewShown: fbFn,
        //auth: auth,
        /*textSettings: {
            value: ko.observable(Appsalonbook.user.username)
        
        }*/

        auth: testFn,
        // logout: logout
        fixva : ko.observable(''),
        saveValueFix: function(data){
            //console.log(data);
            //alert(data.value);
            viewModel.fixva(data.value);

        },
        valueTx: valueTx,
        //valueTx2: ko.observable(''),
        //valueTx3: ko.observable(''),

        viewShown: function () {
            /*viewModel.valueTx(1);
            viewModel.valueTx2(2);
            viewModel.valueTx3(3);
            viewModel.valueTx('');
            viewModel.valueTx2('');
            viewModel.valueTx3('');*/
            //testFn();
            // viewModel.valueTx('a');

            //viewModel.valueTx2('');
            //testFn();

        }
        ,
        viewShowing: function(){
            //viewModel.valueTx(1);
            //viewModel.valueTx2(2);
        
        }
        ,
        getValue: function (data) {
            Appsalonbook.user.username;
        },
        pop: ko.observable(false)
    };

    return viewModel;
};