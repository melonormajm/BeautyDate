<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Salon

$this->title = 'Update Salon: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Salons', 'url' => ['index']];
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
            <i class="fa fa-home"></i>
            <?= Html::a('Administrar Salón',  Url::toRoute('salon/manage')) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            Modificar Salón
        </li>

    </ul>

</div>

<div class="salon-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
