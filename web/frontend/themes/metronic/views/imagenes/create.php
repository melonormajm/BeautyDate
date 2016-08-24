<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Imagenes

$this->title = 'Create Imagenes';
$this->params['breadcrumbs'][] = ['label' => 'Imagenes', 'url' => ['index']];
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
            Agregar Imagen
        </li>
    </ul>
</div>

<div class="imagenes-create">

    <!--<h1><?php //Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
