<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ReservacionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reservacion-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'estado') ?>

    <?= $form->field($model, 'fecha') ?>

    <?= $form->field($model, 'aplicacion_cliente') ?>

    <?= $form->field($model, 'usuarioid') ?>

    <?php // echo $form->field($model, 'sillon_servicioid') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
