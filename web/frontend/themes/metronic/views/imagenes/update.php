<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Imagenes

$this->title = 'Update Imagenes: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Imagenes', 'url' => ['index']];
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
            <i></i>
            <?= Html::a('Salón',  Url::toRoute('salon/preview')) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <?= Html::a('Administrar Salón',  Url::toRoute('salon/manage')) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            Editar Imagen
        </li>

    </ul>
</div>

<div class="imagenes-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
