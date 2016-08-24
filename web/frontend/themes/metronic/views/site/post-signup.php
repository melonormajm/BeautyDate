<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Registro';
?>

<div class="main">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="<?=Url::toRoute('site/inicio')?>">Inicio</a></li>
            <li class="active">Registro exitoso</li>
        </ul>
        <div class="row" style="margin-bottom: 300px !important;">
            <div class="sidebar col-md-12 col-sm-12">
                <h1>Gracias por Registrarse en BeautyDate</h1>
                <p>Sus datos han sido registrados en nuestro sistema, ya puede iniciar sesión para comenzar a administrar su salón.</p>
            </div>
        </div>
    </div>
</div>