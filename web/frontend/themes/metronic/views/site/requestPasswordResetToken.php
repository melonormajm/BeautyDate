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

                <div style="color: red; margin-bottom: 25px;">
                <?php
                    foreach(Yii::$app->session->getAllFlashes(true) as $key => $message) {
                        echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
                    }
                ?>
                </div>
                <p>Por favor introduzca su correo. Un enlace para resetear su contraseña le será enviado.</p>

                <div class="row">
                    <div class="col-lg-5">
                        <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
                        <?= $form->field($model, 'email') ?>
                        <div class="form-group">
                            <?= Html::submitButton('Enviar', ['class' => 'btn btn-primary']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
