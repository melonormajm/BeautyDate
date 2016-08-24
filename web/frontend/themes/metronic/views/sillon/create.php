<?php

use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\models\Sillon

$this->title = 'Create Sillon';
$this->params['breadcrumbs'][] = ['label' => 'Sillons', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;*/
?>

<h3 class="page-title">
    Crear Sillón<small></small>
</h3>

<p>Los sillones de su salón brindarán servicios, los usuarios realizarán citas para su salón dependiendo de los servicios que brinde un sillón determinado.</p>

<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <?= Html::a('Inicio',  Url::toRoute('site/index')) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <?= Html::a('Sillón',  Url::toRoute('sillon/index')) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            Agregar Sillón
        </li>
    </ul>
</div>

<div class="sillon-create">

    <!--<h1><?php //Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'servicios'=>$servicios,
        'selected'=>$selected,
        'message'=>$message,
    ]) ?>

</div>
