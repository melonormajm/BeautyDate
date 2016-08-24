<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Usuario */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="usuario-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre_usuario')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'apellidos')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'tipo')->dropDownList([$model::TIPO_SISTEMA =>'Sistema',
       $model::TIPO_CLIENTE =>'Cliente', $model::TIPO_PROPIETARIO =>'Propietario']) ?>

    <?= $form->field($model, 'tipo_autenticacion')->dropDownList([$model::AUTH_LOCAL=>'Local', $model::AUTH_FACEBOOK=>'Facebook']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
