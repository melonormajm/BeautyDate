<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

$this->title = 'Petición de recuperación de contraseña';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container">
    <div class="row" style="margin-bottom: 200px;">
        <div class="col-md-12">
            <div class="site-request-password-reset">
                <h1><?= Html::encode($this->title) ?></h1>


                <p>El proceso se ha llevado a cabo satisfactoriamente. Revise su correo para más instrucciones.</p>

            </div>
        </div>
    </div>
</div>
