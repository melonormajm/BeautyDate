<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Salon */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs('MapsGoogle.init();');
?>

<div class="salon-form" style="margin-bottom: 20px;">

    <div class="form-body">
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'nombre')->textInput(['maxlength' => 255,'style'=>'max-width:400px;'])->label('Nombre del Salón:') ?>

                <div class="form-group field-salon-hora_inicio required has-success">
                    <label class="control-label"  for="salon-hora_inicio">Hora Apertura Salón:</label>
                    <div class="input-group" style="max-width:400px;">
                        <input type="text" id="salon-hora_inicio" class="form-control timepicker timepicker-no-seconds" name="Salon[hora_inicio]" value="<?=$model->hora_inicio;?>" maxlength="8" style=",style="max-width:400px;">
                        <span class="input-group-btn">
                        <button class="btn default" type="button"><i class="fa fa-clock-o"></i></button>
                        </span>
                    </div>
                    <div class="help-block"></div>
                </div>

                <div class="form-group field-salon-dias_no_laborables">
                    <label class="control-label" for="salon-dias_no_laborables">Días no laborables:</label>
                    <div  style="max-width:400px;">
                        <?php
                        $days = [];
                        $days['1']='Lunes';
                        $days['2']='Martes';
                        $days['3']='Miercoles';
                        $days['4']='Jueves';
                        $days['5']='Viernes';
                        $days['6']='Sabado';
                        $days['0']='Domingo';
                        echo yii\helpers\Html::checkboxList('dias_no_laborables',$selected,$days);
                        ?>
                    </div>
                </div>

                <div class="form-group field-salon-estado has-success">
                    <label class="control-label" for="salon-estado">Estado</label>
                    <?= Html::activeDropDownList($model, 'estado',[\common\models\Salon::ESTADO_ACTIVADO=>'Activo',\common\models\Salon::ESTADO_INACTIVO=>'Inactivo'],
                        ['class'=>'form-control','prompt'=>'- Seleccione Estado -','style'=>'max-width:400px;']) ?>
                    <p class="help-block">
                        Estado de servicio de su salón.
                    </p>
                </div>

                <?= $form->field($model, 'descripcion')->textarea(['maxlength' => 500,'style'=>'min-height:150px;max-width:400px;'])->label('Descripción:') ?>

                <?php //$form->field($model, 'ubicacion')->textarea(['maxlength' => 255,'style'=>'min-height:150px;'])->label('Ubicación del salón:') ?>

            </div>

            <div class="col-md-6">

                <div class="form-group field-salon-monedaid has-success">
                    <label class="control-label" for="salon-monedaid">Tipo de Moneda</label>
                    <?= Html::activeDropDownList($model, 'monedaid',$monedas,
                        ['class'=>'form-control','prompt'=>'- Seleccione Moneda -','style'=>'max-width:400px;']) ?>
                    <div class="help-block">Moneda en la que cobrará su servicio</div>
                </div>

                <div class="form-group field-salon-hora_fin required has-success">
                    <label class="control-label"  for="salon-hora_fin">Hora Cierre Salón:</label>
                    <div class="input-group" style="max-width:400px;">
                        <input type="text" id="salon-hora_fin" class="form-control timepicker timepicker-no-seconds" name="Salon[hora_fin]" value="<?=$model->hora_fin;?>" maxlength="8">
                        <span class="input-group-btn">
                        <button class="btn default" type="button"><i class="fa fa-clock-o"></i></button>
                        </span>
                    </div>
                    <div class="help-block"></div>
                </div>


                <div class="form-group field-salon-ubicacion">
                    <label class="control-label" for="salon-ubicacion">Ubicación del salón:</label>
                    <!--<textarea id="salon-ubicacion" class="form-control" name="Salon[ubicacion]" maxlength="255" style="min-height:150px;"></textarea>-->
                    <div class="input-group">
                        <input id="ubicacion_latitud" type="hidden" name="Salon[ubicacion_latitud]" value="<?= $model->ubicacion_latitud;?>"/>
                        <input id="ubicacion_longitud" type="hidden" name="Salon[ubicacion_longitud]" value="<?= $model->ubicacion_longitud;?>"/>
                        <input type="text" class="form-control" id="gmap_geocoding_address" name="Salon[ubicacion]" placeholder="dirección..." value="<?= $model->ubicacion;?>">
									<span class="input-group-btn">
									<button class="btn blue" id="gmap_geocoding_btn"><i class="fa fa-search"></i>
									</span>
                    </div>
                    <div id="gmap_geocoding" class="gmaps">
                    </div>
                    <div class="help-block"></div>
                </div>

            </div>
        </div>

    </div>


    <div class="form-actions">
        <div class="row">
            <div class="col-md-4">
                <?= Html::submitButton($model->isNewRecord ? 'Crear Salón' : 'Guardar Cambios', ['class' => 'btn btn-success', 'onclick' => 'this.form.submit()']) ?>
            </div>
            <div class="col-md-4 col-md-offset-4">
                <a href="<?=Url::toRoute('servicio/services')?>" class="btn default" style="float: right;"> Ir a Servicios</a>
            </div>

        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

