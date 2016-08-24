<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Categoria;

/* @var $this yii\web\View */
/* @var $model app\models\CategoriaSalon */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="categoria-salon-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-5">
        <?=$form->field($model, 'categoriaid')->dropDownList($dataList,
            ['prompt'=>'-Seleccione una categoría-'])->label('Categoría') ?>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Agregar' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>


</div>
