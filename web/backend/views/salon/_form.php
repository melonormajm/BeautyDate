<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Salon */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="salon-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'cantidad_sillas')->textInput() ?>

    <?= $form->field($model, 'thumbnail')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'ubicacion')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'estado')->textInput(['maxlength' => 25]) ?>

    <?= $form->field($model, 'hora_inicio')->textInput(['maxlength' => 4]) ?>

    <?= $form->field($model, 'hora_fin')->textInput(['maxlength' => 4]) ?>

    <?= $form->field($model, 'usuarioid')->textInput() ?>

    <?= $form->field($model, 'descripcion')->textInput(['maxlength' => 500]) ?>

    <?= $form->field($model, 'descripcion_corta')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'licenciaid')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
