<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Servicio */
/* @var $form yii\widgets\ActiveForm */
?>

<div><?=$message?></div>

<div class="form-body">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'nombre')->textInput(['maxlength' => 255]) ?>

            <!--<div class="form-group field-servicio-horario_inicio required has-success">
                <label class="control-label" for="servicio-horario_inicio">Hora Inicio</label>
                <div class="input-icon">
                    <i class="fa fa-clock-o"></i>
                    <input type="text" id="servicio-horario_inicio" class="form-control  timepicker timepicker-default" name="Servicio[horario_inicio]" value="<?=$model->horario_inicio;?>" maxlength="12">
                </div>
                <div class="help-block"></div>
            </div>-->

            <?= $form->field($model, 'duracion')->textInput()->label('Duración') ?>

            <?= $form->field($model, 'descripcion')->textarea(['maxlength' => 255])->label('Descripción') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'precio')->textInput() ?>

            <!--<div class="form-group field-servicio-horario_fin required has-success">
                <label class="control-label" for="servicio-horario_fin">Hora Inicio</label>
                <div class="input-icon">
                    <i class="fa fa-clock-o"></i>
                    <input type="text" id="servicio-horario_fin" class="form-control  timepicker timepicker-default" name="Servicio[horario_fin]" value="<?=$model->horario_fin;?>" maxlength="12">
                </div>
                <div class="help-block"></div>
            </div>-->

            <div class="form-group field-servicio-estado has-success">
                <label class="control-label" for="servicio-estado">Estado</label>
                <?= Html::activeDropDownList($model, 'estado',[\common\models\Servicio::ESTADO_ACTIVO=>'Activo',\common\models\Servicio::ESTADO_INACTIVO=>'Inactivo'],
                    ['class'=>'form-control','prompt'=>'- Seleccione Estado -']) ?>
                <div class="help-block"></div>
            </div>


        </div>

    </div>
    <div class="row">
        <div class="col-md-12">
        <!--<label class="control-label" for="servicio-descripcion">Sillones que brindarán este servicio</label>-->

        <?php
            $sillones = \yii\helpers\ArrayHelper::map($sillones,'id','nombre');
             yii\helpers\Html::checkboxList('sillones_list',$selected,$sillones);
        ?>
        </div>
    </div>

</div>
<div class="form-actions" style="margin-top: 40px;">
    <div class="row">
        <div class="col-md-9">
            <?= Html::submitButton($model->isNewRecord ? 'Crear Servicio' : 'Modificar', ['class' => $model->isNewRecord ? 'btn green' : 'btn green']) ?>
            <?= Html::a('Cancelar',  Url::toRoute(['servicio/services']), ['class' => 'btn default']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>





