<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */


?>
<h3 class="page-title">
    Activación de licencias
</h3>


<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <?= Html::a('Inicio',  \yii\helpers\Url::toRoute('site/index')) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            activación
        </li>

    </ul>
</div>

<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-9">
                <div class="note note-success">
                    <h4 class="block">Operación Cancelada</h4>
                    <p>
                        Usted ha cancelado el pago de su licencia
                    </p>
                </div>
        </div>
    </div>
</div>