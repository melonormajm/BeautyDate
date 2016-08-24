<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\LicenciaSpec */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="licencia-spec-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'precio')->textInput() ?>

        <?= $form->field($model, 'duracion')->textInput() ?>

        <?= $form->field($model, 'tipo_duracion')->textInput(['maxlength' => 20]) ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
