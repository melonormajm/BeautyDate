<?php

use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model common\models\Promocion */

/*$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Promocion',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Promocions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="promocion-create">

    <h3 class="page-title">
        Crear Promoción<small></small>
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
                Crear Promoción
            </li>
        </ul>
    </div>

    <div><?=isset($message)?$message:null?></div>

    <?= $this->render('_form', [
        'model' => $model,
        'servicios' => $servicios,
    ]) ?>

</div>
