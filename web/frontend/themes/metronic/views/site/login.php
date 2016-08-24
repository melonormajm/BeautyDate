<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="main">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="<?=Url::toRoute('site/inicio')?>">Inicio</a></li>
            <li class="active">Autenticación</li>
        </ul>
        <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40">
            <!-- BEGIN SIDEBAR -->
            <div class="sidebar col-md-3 col-sm-3">
                <ul class="list-group margin-bottom-25 sidebar-menu">

                </ul>
            </div>
            <!-- END SIDEBAR -->

            <!-- BEGIN CONTENT -->
            <div class="col-md-9 col-sm-9">
                <h1>Iniciar Sesión</h1>
                <div class="content-form-page">
                    <div class="row">
                        <div class="col-md-7 col-sm-7">

                            <div class="form-horizontal form-without-legend" role="form">
                                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                                <div class="form-group">
                                    <label for="username" class="col-lg-4 control-label">Correo o Usuario <span class="require">*</span></label>
                                    <div class="col-lg-8">
                                        <input type="text" id="loginform-username" class="form-control" name="LoginForm[username]">
                                        <div class="help-block" style="color: red"><?php if(isset ($model->errors['username'])) echo $model->errors['username'][0];?></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="col-lg-4 control-label">Contraseña <span class="require">*</span></label>
                                    <div class="col-lg-8">
                                        <input type="password" id="loginform-password" class="form-control" name="LoginForm[password]">
                                        <div class="help-block" style="color: red"><?php if(isset ($model->errors['password'])) echo $model->errors['password'][0];?></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-8 col-md-offset-4 padding-left-0 padding-top-20">
                                        <?= $form->field($model, 'rememberMe')->checkbox()->label('Recordarme siempre') ?>
                                        <div style="color:#999;margin:1em 0">
                                            Si olvidó su contraseña puede <span><?= Html::a('resetearla', ['site/request-password-reset']) ?></span>.
                                        </div>
                                        <div class="form-group">
                                            <?= Html::submitButton('Entrar', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                                        </div>
                                        <?php ActiveForm::end(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 pull-right">
                            <div class="form-info">
                                <h2><em>¿No tienes una Cuenta?</em> ¡Crea una!</h2>
                                <p>Puedes crearte una nueva cuenta siguiendo el siguiente enlace.</p>

                                <a href="<?=Url::toRoute('site/signup')?>" class="btn btn-default">Registro</a>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END CONTENT -->
        </div>
        <!-- END SIDEBAR & CONTENT -->
    </div>
</div>