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
            <?php if ($success): ?>
                <div class="note note-success">
                    <h4 class="block">Operación Exitosa</h4>
                    <p>
                        Su licencia ha sido activada satisfactoriamente.
                    </p>
                </div>
            <?php else: ?>
                <div class="note note-danger">
                    <h4 class="block">Error</h4>
                    <p>
                        Ha ocurrido un error durante el proceso de activación de su licencia.
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>