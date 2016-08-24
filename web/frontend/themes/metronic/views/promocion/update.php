<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Promocion */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Promocion',
]) . ' ' . $model->id;
/*$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Promocions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');*/
?>
<div class="promocion-update">

    <h3 class="page-title">
        Modificar Promoción<small></small>
    </h3>

    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <?= Html::a('Inicio',  Url::toRoute('site/index')) ?>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <?= Html::a('Promociones',  Url::toRoute('promocion/index')) ?>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                Modificar Promoción
            </li>
        </ul>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'servicios' => $servicios,
    ]) ?>

</div>
