<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 22/08/2015
 * Time: 12:59
 */
$validateJS = <<<EOF

$('body').on('beforeSubmit','form#personal_data',function(e){
    var valid = true;
    if($('#user-first_name').val()==""){
        valid = false;
    }
    if($('#user-last_name').val()==""){
        valid = false;
    }
    if($('#user-email').val()==""){
        valid = false;
    }
    if(valid==false){
        $('#error_msg').show();
        return false;
    }
    return true;

});
$('body').on('beforeSubmit','form#user_pass_form',function(e){
    var valid = true;
    if($('#password_user').val()==""){
        valid = false;
    }
    if(valid==false){
        $('#error_msg_pass').show();
        return false;
    }
    return true;

});
EOF;

$this->registerJs($validateJS);
?>


<h3 class="page-title">
    Perfil <small>editar información de usuario</small>
</h3>


<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <?= Html::a('Inicio',  Url::toRoute('site/home')) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            Perfil del usuario
        </li>
    </ul>
</div>


<div class="salon-form" style="margin-bottom: 20px;">
    <div class="row">
        <div class="col-md-6">
            <div class="form-body">
                <h3>Datos Personales</h3>
                <div id="error_msg" class="alert alert-danger" style="display: none;max-width: 400px;">
                    <strong>Error!</strong> Hay campos vacíos en el formulario.
                </div>
                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data','id'=>'personal_data']]); ?>

                        <?= $form->field($model, 'first_name')->textInput(['maxlength' => 255,'style'=>'max-width:400px;'])->label('Nombre:') ?>

                        <?= $form->field($model, 'last_name')->textInput(['maxlength' => 255,'style'=>'max-width:400px;'])->label('Apellidos:') ?>

                        <?= $form->field($model, 'email')->textInput(['maxlength' => 255,'style'=>'max-width:400px;'])->label('Correo:') ?>

                        <div class="form-actions" style="margin-top: 50px;">
                            <?= Html::submitButton($model->isNewRecord ? 'Crear Usuario' : 'Guardar Cambios', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                        </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-body">
                <h3>Cambiar contraseña</h3>
                <div id="error_msg_pass" class="alert alert-danger" style="display: none;max-width: 400px;">
                    <strong>Error!</strong> Debe especificar una contraseña.
                </div>
                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data','id'=>'user_pass_form']]); ?>

                <?= $form->field($model, 'password')->passwordInput(['maxlength' => 50,'style'=>'max-width:400px;','id' => 'password_user'])->label('Nueva Contraseña:') ?>

                <div class="form-actions" style="margin-top: 50px;">
                    <?= Html::submitButton('Guardar Cambios', ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>