<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

$this->title = 'Contraseña cambiada con éxito';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container">
    <div class="row" style="margin-bottom: 200px;">
        <div class="col-md-12">
            <div class="site-request-password-reset">
                <h1><?= Html::encode($this->title) ?></h1>


                <p>Su contraseña fue cambiada satisfactoriamente.</p>

            </div>
        </div>
    </div>
</div>
