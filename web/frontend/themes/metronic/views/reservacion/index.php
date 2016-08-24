<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use common\models\Reservacion;

use yii\web\JsExpression;

/* @var $this yii\web\View */


$DragJS = <<<EOF
/* initialize the external events
-----------------------------------------------------------------*/

$('#external-events .fc-event').each(function() {
    // store data so the calendar knows to render an event upon drop
    $(this).data('event', {
        title: $.trim($(this).text()), // use the element's text as the event title
        stick: true // maintain when user navigates (see docs on the renderEvent method)
    });
    // make the event draggable using jQuery UI
    $(this).draggable({
        zIndex: 999,
        revert: true,      // will cause the event to go back to its
        revertDuration: 0  //  original position after the drag
    });
});

EOF;

$this->registerJs($DragJS);

?>
<div class="site-index">

    <div class="body-content">

        <?php

        $JSCode = <<<EOF
function(start, end) {
    $('#modal_evento').modal('show');
    var title = 'prueba';
    //var title = prompt('Nombre del Evento:');
    var eventData;
    if (title) {
        eventData = {
            title: title,
            start: start,
            end: end
        };
        $('#w0').fullCalendar('renderEvent', eventData, true);
    }
    $('#w0').fullCalendar('unselect');
}
EOF;

        $JSDropEvent = <<<EOF
function(date) {
    alert("Dropped on " + date.format());
    if ($('#drop-remove').is(':checked')) {
        // if so, remove the element from the "Draggable Events" list
        $(this).remove();
    }
}
EOF;

        $JSEventClick = <<<EOF
function(calEvent, jsEvent, view) {

    var id = '&id='+calEvent.id;
    var form = $('form#form_evento');
    var url = form.attr('action');
    form.attr('action',url+id);
    $('#modal_evento').modal('show');
    //alert('Event: ' + calEvent.title);
    //alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
    //alert('View: ' + view.name);

    // change the border color just for fun
    $(this).css('border-color', 'red');

}

EOF;
        $JSPrueba = <<<EOF
function(calEvent, jsEvent, view){
    alert(calEvent.title + ' ' + calEvent.start.format() + ' '+calEvent.end.format());
}
EOF;
        $JSDropped = <<<EOF
function(event, delta, revertFunc, jsEvent, ui, view ){
    alert('soltaste a: '+ event.title);
}
EOF;
        $JSDescription = <<<EOF
function(event, element, view ){
    element.find('.fc-event-title').append("<br />" + event.description);
}
EOF;
        $JSEventReceive = <<<EOF
function(event){
    event.title = 'jorge';
    alert(event.start.format('YYYY-MM-DD[T]HH:MM:SS'));
    //element.find('.fc-event-title').append("<br />" + event.description);
}
EOF;
?>
        <style>
            .fc-day-grid-event > .fc-content{
                white-space: normal;
            }
            .modal-dialog{
                z-index: 10500;
            }
            .fc-event-container{
                cursor: pointer;
            }
            .fc-event {
                border: none;
            }
        </style>
        <script type="application/javascript">
            function changeAvalableTime(ev){
                var day = RESERVACIONES.getCurrentDay();
                $("#wait-spinner").show();
                $("#load-failed").hide();
                $("#load-success").hide();
                $.getJSON(
                    '<?php echo Url::toRoute('reservacion/get-available-time')?>',
                    {
                        'date'   : day,
                        'servid' : ev.value

                    }
                    ).done(
                        function(data){
                            if(data.success){
                                //var vartmp = ["1000", "1100", "1200"];
                                $("#wait-spinner").hide();
                                var vartmp = data.content;
                                if(vartmp == ""){
                                    $("#load-success").html('No se encontraron horarios disponibles.');
                                    $("#load-success").show();
                                }else{

                                    var str ='<select id="hora_inicio" name="hora_inicio" class = "form-control" ><option value="-">- Seleccione la Hora -</option>';

                                    for (var i= 0; i < vartmp.length; i++){
                                        str +="<option value='"+ vartmp[i]+"'>" + RESERVACIONES.formatTime(vartmp[i]) + "</option>";

                                    }
                                    str += "</select>";
                                    $("#hora_inicio").remove();
                                    $("#time_cont").append(str);
                                    $("#time_cont_col").show();
                                }

                            }else{
                                $("#wait-spinner").hide();
                                $("#load-failed").html('Ha ocurrido un error en el servidor');
                                $("#load-failed").show();
                            }
                        });
            }

            function createReservacionAjax(){
                var valid = true;
                if($("#reservacion_new_client").prop('checked')){
                    if($('#reservacion-nombre_cliente').val() == ""){
                        $('#name-help-block').show();
                        valid = false;
                    }
                    if($('#reservacion-correo_cliente').val() == ""){
                        $('#mail-help-block').show();
                        valid = false;
                    }

                }else{
                    if($('#users').val()==""){
                        $('#register-name-help-block').show();
                        valid = false;
                    }
                }

                if($('#servicios_list').val()==""){
                    $('#services-help-block').show();
                    valid = false;
                }

                if($('#hora_inicio').val() == ""){
                    $('#time-help-block').show();
                    valid = false;
                }

                if(valid){
                    var form = $('#form_create_evento');
                    $.ajax({
                        url: form.attr('action'),
                        type: "POST",
                        data: form.serialize(),
                        success: function (response) {
                            RESERVACIONES.onEventReserved(response);
                            $('#w0').fullCalendar('refetchEvents');
                            $('#modal_create_evento').modal('hide');
                            // $('#message_ajax').html(response);
                        },
                        error: function (jqXHR, textStatus, errorMessage) {
                            console.log(errorMessage); // Optional
                        }
                    });
                }else{
                    return;
                }
            }

            function confirmReservacionAjax(){
                var form = $('#form_evento');
                $.ajax({
                    url: "<?=Url::toRoute('reservacion/confirmar')?>",
                    type: "POST",
                    data: form.serialize(),
                    success: function(response){
                        $('#w0').fullCalendar('refetchEvents');
                        $('#modal_evento').modal('hide');
                    },
                    error: function(jqXHR, textStatus, errorMessage){

                    }
                })
            }

            function cancelarReservacionAjax(){
                var form = $('#form_evento');
                $.ajax({
                    url: "<?=Url::toRoute('reservacion/cancelar')?>",
                    type: "POST",
                    data: form.serialize(),
                    success: function(response){
                        $('#w0').fullCalendar('refetchEvents');
                        $('#modal_evento').modal('hide');
                        //alert('success');
                    },
                    error: function(jqXHR, textStatus, errorMessage){

                    }
                })
            }

        </script>



        <h3 class="page-title">
            Reservaciones del Salón<small></small>
        </h3>

        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <?= Html::a('Inicio',  Url::toRoute('site/home')) ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    Reservaciones del Salón
                </li>

            </ul>
        </div>
        <p>
            Este calendario le permite visualizar las reservaciones hechas en su salón por los clientes desde la aplicación móvil.
            También le permite crear nuevas reservaciones haciendo clic en el día deseado e insertando los datos correspondientes.
            Además, puede visualizar y modificar el estado de una reservación haciendo clic en la misma.
        </p>

        <p>
            <span class="label label-info" style="background-color: #6E5C7F;"> Realizada </span>
            <span class="label label-success" style="background-color: #3d3d3d;"> Pendiente </span>
            <span class="label label-danger"> Cancelada </span>
        </p>

        <div class="row">
            <div class="col-md-12 col-sm-12">
                <?= yii2fullcalendar\yii2fullcalendar::widget(array(
                    'clientOptions' => [
                        'selectable' => true,
                        'selectHelper' => true,
                        'droppable' => true,
                        'editable' => true,
                        //'drop' => new JsExpression($JSDropEvent),
                        //'eventReceive' => new JsExpression($JSEventReceive),
                        //'eventReceive' => new JsExpression("RESERVACIONES.onEventReceive"),
                        //'select' => new JsExpression($JSCode),
                        'select' => new JsExpression("RESERVACIONES.onEventSelect"),
                        //'eventClick' => new JsExpression($JSEventClick),
                        'eventClick' => new JsExpression('RESERVACIONES.onEventClick'),
                        'defaultDate' => date('Y-m-d'),
                        //'eventResize' => new JsExpression($JSPrueba),
                        //'eventDrop' => new JsExpression($JSDropped),
                        //'eventRender' => new JsExpression($JSDescription),
                        'eventRender' => new JsExpression("RESERVACIONES.onEventRender"),
                        'lang' => 'es',
                        'timeFormat'=> 'h:mm a',
                        'eventLimit'=> 'true',
                    ],
                    'ajaxEvents' => Url::toRoute(['/reservacion/jsoncalendar'])
                ));
                ?>
            </div>
        </div>
        <?php Modal::begin(['header' => 'Actualizar estado de reservación','id' => 'modal_evento']);?>
            <?php $form = ActiveForm::begin(['action'=>Url::toRoute('reservacion/confirmarestado'),'id'=>'form_evento']); ?>
            <input type="hidden" id="event_id" name="eventoid"/>
            <div class="row" style="margin-top: 15px;">
                <div class="col-md-4">
                    <span>Cliente:</span>
                </div>
                <div class="col-md-8">
                    <span id="cliente_info"></span>
                </div>
            </div>
            <div class="row" style="margin-top: 15px;">
                <div class="col-md-4">
                    <span>Servicio:</span>
                </div>
                <div class="col-md-8">
                    <span id="cliente_servicio"></span>
                </div>
            </div>
            <div class="row" style="margin-top: 15px;">
                <div class="col-md-4">
                    <span>Sillón:</span>
                </div>
                <div class="col-md-8">
                    <span id="cliente_sillon"></span>
                </div>
            </div>
            <div class="row" style="margin-top: 15px;">
                <div class="col-md-4">
                    <span>Hora de Reserva:</span>
                </div>
                <div class="col-md-4">
                    <span id="time_info"></span>
                </div>
            </div>
            <div class="row" style="margin-top: 30px;">
                <div class="col-md-12">
                    <div class="clearfix">
                        <button type="button" class="btn red" id="cancelar_reserva" onclick="cancelarReservacionAjax();">Cancelar Reserva</button>
                        <button type="button" class="btn green" id="confirmar_reserva" onclick="confirmReservacionAjax();">Confirmar Reserva</button>
                    </div>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        <?php Modal::end();?>

        <?php Modal::begin(['header' => 'Crear reservación','id' => 'modal_create_evento', 'clientOptions' =>['backdrop'=> 'static']]);?>
            <?php $form = ActiveForm::begin(['action'=>Url::toRoute('reservacion/reservar'),'id'=>'form_create_evento']); ?>
                <div class="row">
                    <div id="wait-spinner" class="col-md-12" style="display: none;">
                        <img src="<?=Url::to('@web/'.'images/ajaxspinner.gif', true)?>" style="width: 25px;">
                        <span>Cargando horarios disponibles</span>
                    </div>
                    <div id="message" class="col-md-12">
                        <div id="load-success" class="alert alert-success" style="display: none;">
                            <strong>Success!</strong> The page has been added.
                        </div>
                        <div id="load-failed" class="alert alert-danger" style="display: none;">
                            <strong>Error!</strong>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-bottom: 15px;">
                        <div class="col-md-6">
                            <input type="checkbox" id="reservacion_new_client" name="nuevo_cliente">Agregar nuevo cliente</input>
                        </div>
                    </div>
                    <div id="usuario_registrado" class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group field-reservacion-estado">
                                <label class="control-label" for="reservacion-estado">Cliente</label>
                                <?= Html::dropDownList('users',null,$users,['id'=>'users','class'=>'form-control','prompt'=>'- Seleccione Usuario -'])?>
                                <input type="hidden" name="fecha" id="fecha" />
                                <div id="register-name-help-block" class="help-block"  style="display: none">Debe seleccionar un nombre de usuario</div>
                            </div>
                        </div>
                    </div>
                    <div id="nuevo_usuario" class="col-md-12" style="display: none;">
                        <div class="col-md-6">
                            <div class="form-group field-reservacion-nombre_cliente required">
                                <label class="control-label" for="reservacion-nombre_cliente">Nombre</label>
                                <input type="text" id="reservacion-nombre_cliente" class="form-control" name="nombre_cliente" maxlength="255">
                                <div id="name-help-block" class="help-block" style="display: none">Nombre no puede estar vacío</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group field-reservacion-correo_cliente required">
                                <label class="control-label" for="reservacion-correo_cliente">Correo</label>
                                <input type="text" id="reservacion-correo_cliente" class="form-control" name="correo_cliente" maxlength="255">
                                <span id="email-error" for="email" class="help-block help-block-error" style="color:#a94442;display: none;">Por favor inserte un correo válido.</span>
                                <div id="mail-help-block" class="help-block" style="display: none">Correo no puede estar vacío</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group field-reservacion-estado has-success">
                                <label class="control-label" for="reservacion-estado">Servicio</label>
                                <?= Html::dropDownList('servicios',null,$datalist,['id'=>'servicios_list','class'=>'form-control','prompt'=>'- Seleccione servicio -', 'onChange' => 'changeAvalableTime(this);'])?>
                                <div id="services-help-block" class="help-block" style="display: none">Servicios no puede estar vacío</div>
                            </div>
                        </div>
                        <div  class="col-md-6" style="display: none;" id="time_cont_col">
                            <div class="form-group field-reservacion-estado has-success" id="time_cont">
                                <label class="control-label" for="reservacion-estado">Hora de inicio</label>
                                <div id="time-help-block" class="help-block" style="display: none">Hora de inicio no puede estar vacío</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="form-group" style="margin-top: 20px;">
                            <?= Html::button('Reservar', ['class' => 'btn btn-success', 'onClick' => 'createReservacionAjax();']) ?>
                            <?= Html::button('Cancelar', ['class' => 'btn default', 'onClick' => 'RESERVACIONES.removeCurrentEvent();']) ?>
                        </div>
                    </div>
                </div>
            <?php ActiveForm::end(); ?>
        <?php Modal::end();?>

    </div>
</div>