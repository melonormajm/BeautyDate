<?php

use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\models\Salon

$this->title = 'Crear Salón';
$this->params['breadcrumbs'][] = ['label' => 'Salons', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;*/
?>

<h3 class="page-title">
    Mi salón <small>información general</small>
</h3>

<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <?= Html::a('Inicio',  Url::toRoute('site/home')) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            Modificar Datos de Salón
        </li>
    </ul>
</div>

<div id="message_ajax"><?php if(isset($message)){echo $message;}?></div>

<div class="salon-create">

    <?= $this->render('_form', [
        'model' => $model,
        'monedas' => $monedas,
        'selected' => $selected
    ]) ?>


</div>
