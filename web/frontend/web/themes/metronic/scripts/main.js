/**
 * Created by Administrador on 21/03/2015.
 */

jQuery(document).ready(function() {
    load_edit();
    delete_servicio();
    load_sillon_edit();
    delete_sillon();
})

cleanObj = {

    clean_form_servicio: function (){
        $('[name="Servicio[nombre]"]').val('');
        $('[name="Servicio[duracion]"]').val('');
        $('[name="Servicio[descripcion]"]').val('');
        $('[name="Servicio[precio]"]').val('');
        //$('[name="Servicio[estado]"]').val('');
    },
    clean_form_categoria: function (){
        $('[name="CategoriaSalon[categoriaid]"]').val('');
    },
    clean_form_imagenes : function(){
        $('[name="Imagenes[nombre]"]').val('');
        $('[name="Imagenes[principal]"]').val(0);
        $('[name="Imagenes[url]"]').val('');
        $('[name="Imagenes[descripcion]"]').val('');
    },
    clean_form_sillon : function(){
        $('[name="Sillon[nombre]"]').val('');
        $('#my_multi_select1').multiSelect('deselect_all');
    }
}

// listen click, open modal and .load content
$('#crear_servicio').click(function (){
    cleanObj.clean_form_servicio();
    var action = $(this).attr('action');
    $('form#form_servicio').attr('action',action);
    $('#modal_servicio').modal('show');
});
$('#crear_categoria').click(function (){
    $('#modal_categoria').modal('show');
});
$('#crear_imagen').click(function (){
    $('#modal_imagen').modal('show');
});
$('#crear_sillon').click(function (){
    cleanObj.clean_form_sillon();
    var action = $(this).attr('action');
    $('form#form_sillon').attr('action',action);
    $('#modal_sillon').modal('show');
});
$('#agregar_imagen').click(function (){
    $('#modal_imagen').modal('show');
});



var load_edit = function(){$('.edit_servicio').click(function(){
    var id = $(this).attr('value');
    var action = $(this).attr('action');
    $('form#form_servicio').attr('action',action);
    var url = $(this).attr('load');
    var config = {};
    config.id = id;
    $.ajax({
        dataType: 'json',
        type: 'GET',
        url: url,
        data: config ,
        beforeSend: function(){
        },
        success: function(data){
            //data = JSON.parse(data);
            /*for(var field in data){
                $('[id=servicio-' + field.replace(/\"/g, "") + ']').val(data[field]);
            }*/
            $('[id=servicio-estado]').val(data['estado']);
            $('[id=servicio-precio]').val(data['precio']);
            $('[id=servicio-nombre]').val(data['nombre']);
            $('[id=servicio-descripcion]').val(data['descripcion']);
            $('[id=servicio-horario_inicio]').val(data['horario_inicio']);
            $('[id=servicio-horario_fin]').val(data['horario_fin']);
            $('[id=servicio-duracion]').val(data['duracion']);
            $('[id=servicio-categoriaid]').val(data['categoriaid']);

            $('#modal_servicio').modal('show');

        }
    });
});
}

var load_sillon_edit = function(){$('.edit_sillon').click(function(){
    var id = $(this).attr('value');
    var action = $(this).attr('action');
    $('form#form_sillon').attr('action',action);
    var url = $(this).attr('load');
    var config = {};
    config.id = id;
    $.ajax({
        dataType: 'json',
        type: 'GET',
        url: url,
        data: config ,
        beforeSend: function(){
        },
        success: function(data){
            //data = JSON.parse(data);
            //alert(data['servicios']);
            for(var field in data){
                $('#my_multi_select1').multiSelect('deselect_all');
                $('[id=sillon-' + field.replace(/\"/g, "") + ']').val(data[field]);
                if(data['servicios'] != null){
                    $('#my_multi_select1').multiSelect('select',data['servicios']);
                }
            }
            $('#modal_sillon').modal('show');

        }
    });
});
}

$('.edit_imagen').click(function(){
    var id = $(this).attr('value');
    var url = '<?php echo Url::toRoute("servicio/update"); ?>';
    var config = {};
    config.id = id;
    //alert(url);
    $.ajax({
        dataType: 'json',
        type: 'GET',
        url: 'http://localhost/beautyDate/frontend/web/index.php?r=imagenes%2Fedit',
        data: config ,
        beforeSend: function(){
        },
        success: function(data){
            //data = JSON.parse(data);
            for(var field in data){
                if('imagenes-' + field.replace(/\"/g, "") == 'imagenes-url'){
                    $('[id=imagenes-' + field.replace(/\"/g, "") + ']').val('');
                }
                else{
                    $('[name="Imagenes['+ field.replace(/\"/g, "")+ ']"'+ ']').val(data[field]);
                    //$('[id=imagenes-' + field.replace(/\"/g, "") + ']').val(data[field]);
                }
            }
            $('#modal_imagen').modal('show');

        }
    });
});

//Submit

$('body').on('beforeSubmit','form#form_servicio',function(e){
    var form = $(this);

    if(form.find('.has_error').length){
        return false;
    }

    $.ajax({
        url:form.attr('action'),
        type:'post',
        data: form.serialize(),

        success: function(data){
            $.pjax.defaults.timeout = false;//IMPORTANT
            $.pjax.reload({container:'#servicio_pjax'}).done(function(){
                load_edit();
                delete_servicio();
                data = $.parseJSON(data);
                $('#message_ajax').html(data['data']);
                $('#modal_servicio').modal('hide');
            });


        }
    });
    return false;
});

$('body').on('beforeSubmit','form#form_categoria',function(){
    var form = $(this);
    if(form.find('.has_error').length){
        return false;
    }

    $.ajax({
        url:form.attr('action'),
        type:'post',
        data: form.serialize(),
        success: function(data){
            $.pjax.defaults.timeout = false;//IMPORTANT
            $.pjax.reload({container:'#categoria_pjax'});
            $('#message_ajax').html(data);
            $('#modal_categoria').modal('hide');
        }
    });
    return false;
});

$('body').on('beforeSubmit','form#form_sillon',function(){
    var form = $(this);
    if(form.find('.has_error').length){
        return false;
    }

    $.ajax({
        url:form.attr('action'),
        type:'post',
        data: form.serialize(),
        success: function(data){
            $.pjax.defaults.timeout = false;//IMPORTANT
            $.pjax.reload({container:'#sillon_pjax'}).done(function(){
                load_sillon_edit();
                delete_sillon();
                $('#message_ajax').html(data);
                $('#modal_sillon').modal('hide');
            });

        }
    });
    return false;
});


var uploadImageFile = function(){
    var form = $('#form_imagenes');
    var fd = new FormData();
    fd.append("Imagenes[nombre]", $('#imagenes-nombre').val());
    fd.append("Imagenes[principal]", $('#imagenes-principal').val());
    fd.append("Imagenes[descripcion]", $('#imagenes-descripcion').val());
    fd.append("Imagenes[url]", $('#imagenes-url')[0].files[0]);

    $.ajax({
        url: form.attr('action'),
        type: "POST",
        data: fd,
        processData: false,
        contentType: false,
        success: function(data) {
            //console.log('aaaa');
            //$("#cnt").html(response);
            $('#modal_imagen').modal('hide');
            $.pjax.defaults.timeout = false;//IMPORTANT
            $.pjax.reload({container:'#imagenes_pjax'});
            $('#message_ajax').html(data);

        },
        error: function(jqXHR, textStatus, errorMessage) {
            console.log(errorMessage); // Optional
        }
    });
    return false;
}

var delete_servicio = function() {
    $('.delete_servicio').click(function () {
        if (confirm('Está seguro de que desea eliminar este servicio')) {
            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                //data: fd,
                processData: false,
                contentType: false,
                success: function (response) {
                    $.pjax.defaults.timeout = false;//IMPORTANT
                    $.pjax.reload({container: '#servicio_pjax'}).done(function () {
                        delete_servicio();
                        load_edit();
                    });
                    $('#message_ajax').html(response);
                },
                error: function (jqXHR, textStatus, errorMessage) {
                    console.log(errorMessage); // Optional
                }
            });
        }
    });
}

var delete_sillon = function() {
    $('.delete_sillon').click(function () {
        if (confirm('Está seguro de que desea eliminar este sillón')) {
            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                //data: fd,
                processData: false,
                contentType: false,
                success: function (response) {
                    $.pjax.defaults.timeout = false;//IMPORTANT
                    $.pjax.reload({container: '#sillon_pjax'}).done(function () {
                        delete_sillon();
                        load_sillon_edit();
                    });
                    $('#message_ajax').html(response);
                },
                error: function (jqXHR, textStatus, errorMessage) {
                    console.log(errorMessage); // Optional
                }
            });
        }
    });
}




/*
$('body').on('beforeSubmit','form#form_imagenes',function(){
    var form = $('#form_imagenes');
    var fd = new FormData(form);
    fd.append("fileToUpload", $('#imagenes-url')[0].files[0]);
    $.ajax({
        url: $(this).attr('action'),
        type: "POST",
        data: fd,
        processData: false,
        contentType: false,
        success: function(response) {
            //console.log('aaaa');
            //$("#cnt").html(response);
            $('#message_ajax').html(data);
        },
        error: function(jqXHR, textStatus, errorMessage) {
            console.log(errorMessage); // Optional
        }
    });
});*/

/*
$('.delete_cat').click(function(){
    var form = $(this);
    var id = $(this).attr('value');


    $.ajax({
        url:form.attr('action'),
        type:'post',
        data: form.serialize(),
        success: function(errors){
            $('#modal_servicio').modal('hide');
        }
    });
    return false;
});*/