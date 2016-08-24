<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

$this->title = 'Cambiar Contraseña';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="site-reset-password">
                <h1><?= Html::encode($this->title) ?></h1>

                <p>Por favor escoja una nueva contraseña:</p>

                <div class="row">
                    <div class="col-lg-5">
                        <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
                            <?= $form->field($model, 'password')->passwordInput() ?>
                            <div class="form-group">
                                <?= Html::submitButton('Aceptar', ['class' => 'btn btn-primary']) ?>
                            </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
