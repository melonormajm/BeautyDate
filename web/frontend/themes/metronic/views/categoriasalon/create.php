<?php

use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\models\CategoriaSalon

$this->title = 'Agregar Categoria';
$this->params['breadcrumbs'][] = ['label' => 'Categoria Salons', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;*/
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
            Agregar Categoría
        </li>
    </ul>
</div>

<div class="categoria-salon-create">

    <h1><?php //Html::encode($this->title) ?></h1>
    <?php if(isset($message)):?>
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
            <strong>Error!</strong> <?=$message?>
        </div>
    <?php endif?>

    <?= $this->render('_form', [
        'model' => $model,
        'dataList' => $dataList,
    ]) ?>

</div>
