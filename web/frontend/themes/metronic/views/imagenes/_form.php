<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Imagenes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="imagenes-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'nombre')->textInput(['maxlength' => 50]) ?>

            <?= $form->field($model, 'principal')->checkbox() ?>

            <?= $form->field($model, 'url')->fileInput() ?>

            <?= $form->field($model, 'descripcion')->textArea(['maxlength' => 100]) ?>

            <?php //$form->field($model, 'principal')->checkbox() ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Agregar' : 'Modificar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Cancelar',  Url::toRoute('salon/manage'), ['class' => 'btn default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
