<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Categoria;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\CategoriaSalon */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <?= Html::a('Inicio',  Url::toRoute('site/index')) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <?= Html::a('Administrar Salón',  Url::toRoute('salon/manage')) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            Agregar servicio a sillón
        </li>
    </ul>
</div>

<?php if(isset($message)):?>
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
        <strong>Error!</strong> <?=$message?>
    </div>
<?php endif?>

<div class="categoria-salon-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'sillonid')->hiddenInput()?>

    <div class="col-md-5">
        <?=$form->field($model, 'servicioid')->dropDownList($dataList,
            ['prompt'=>'-Seleccione un servicio-'])->label('Servicio') ?>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Agregar' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>


</div>
