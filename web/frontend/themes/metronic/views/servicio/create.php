<?php

use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\models\Servicio

$this->title = 'Crear Servicio';
$this->params['breadcrumbs'][] = ['label' => 'Servicios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;*/
?>

<h3 class="page-title">
    Crear Servicio<small></small>
</h3>

<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <?= Html::a('Inicio',  Url::toRoute('site/index')) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <?= Html::a('Servicios',  Url::toRoute('servicio/services')) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            Crear Servicio
        </li>
    </ul>
</div>

<p>
    Agregue un servicio que brindará su salón. Puede asociar además a sillones del salón que
    brinden este servicio, los usuarios usarán esta información para hacer sus reservaciones por servicio y sillón.
</p>

<div class="servicio-create">

    <!--<h1><?php //Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'sillones' => $sillones,
        'selected' => $selected,
        'message' => $message,
    ]) ?>

</div>
