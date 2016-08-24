<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\StarRating;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use common\models\Servicio;

/* @var $this yii\web\View */
/* @var $model app\models\Sillon */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sillon-form">
    <div class="row">
        <div class="col-md-6">

        <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'nombre')->textInput(['maxlength' => 20]) ?>
            </div>
            <div class="col-md-6">

                <div class="form-group field-sillon-estado required">
                    <label class="control-label" for="sillon-estado">Estado</label>
                    <?= Html::activeDropDownList($model, 'estado',[\common\models\Servicio::ESTADO_ACTIVO=>'Activo',\common\models\Servicio::ESTADO_INACTIVO=>'Inactivo'],
                        ['class'=>'form-control','prompt'=>'- Seleccione Estado -']) ?>
                    <div class="help-block"></div>
                </div>
            </div>

        </div>

        <div class="row" style="margin-top: 40px;">
            <div class="col-md-12">
                <label class="control-label" for="servicio-descripcion">Servicios que brindará este sillón</label>
                <?php
                    $servicios = \yii\helpers\ArrayHelper::map($servicios,'id','nombre');
                    print_r($servicios);die;
                    echo yii\helpers\Html::checkboxList('servicios_list',$selected,$servicios);
                ?>
        </div>

            <?php //$form->field($model, 'salonid')->textInput() ?>
        </div>
        <div class="row" style="margin-top: 40px;">
            <div class="col-md-6">
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? 'Crear Sillón' : 'Modificar', ['class' => $model->isNewRecord ? 'btn green' : 'btn btn-primary']) ?>
                    <?= Html::a('Cancelar',  Url::toRoute('salon/manage'), ['class' => 'btn default']) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
