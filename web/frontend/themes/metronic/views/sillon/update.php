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
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <?= Html::a('Inicio',  Url::toRoute('site/home')) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <?= Html::a('Sillones',  Url::toRoute('sillon/index')) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            Modificar Sillón
        </li>

    </ul>

</div>

<div class="servicio-update">

    <!--<h1><?php //Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'selected'=>$selected,
        'servicios'=>$servicios,
    ]) ?>

</div>
