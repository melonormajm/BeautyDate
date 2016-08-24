<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Servicio

$this->title = 'Actualizar Servicio: ' . ' ' . $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Servicios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';*/
?>

<h3 class="page-title">
    Modificar Servicio<small></small>
</h3>

<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <?= Html::a('Inicio',  Url::toRoute('site/home')) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <i class="fa fa-home"></i>
            <?= Html::a('Servicios',  Url::toRoute('servicios/services')) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            Modificar Servicio
        </li>

    </ul>

</div>

<div class="servicio-update">

    <!--<h1><?php //Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'sillones' => $sillones,
        'selected' => $selected,
        'message' => $message,
    ]) ?>

</div>
