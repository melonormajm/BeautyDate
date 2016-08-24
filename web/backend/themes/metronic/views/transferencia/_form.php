<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Transferencia */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="portlet-body form">
    <?php $form = ActiveForm::begin([
        'options'=>['enctype' => 'multipart/form-data']
    ]); ?>
    <div class="form-body">
        <?= $form->field($model, 'propietario')->textInput(['maxlength' => 150]) ?>
        <?= $form->field($model, 'detalles')->textarea(['rows'=>3]) ?>

        <span class="help-block">Ej: Sucursal de banco, No.Cuenta, etc. </span>
        <?= $form->field($model, 'direccion')->textarea(['rows'=>2]) ?>
    </div>
    <div class="form-actions">
        <?= Html::submitButton('Actualizar', ['class' => 'btn blue']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
