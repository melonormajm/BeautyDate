<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="main">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="<?=Url::toRoute('site/inicio')?>">Inicio</a></li>
            <li class="active">Registrarse</li>
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
                <h1>Crear una cuenta</h1>
                <div class="content-form-page">
                    <div class="row">
                        <div class="col-md-7 col-sm-7">

                            <?php $form = ActiveForm::begin(['id' => 'form-signup','options'=> ['class'=>'form-horizontal']]); ?>
                                <fieldset>
                                    <legend>Sus datos personales</legend>
                                    <div class="form-group">
                                        <label for="usuario" class="col-lg-4 control-label">Usuario <span class="require">*</span></label>
                                        <div class="col-lg-8">
                                            <input type="text" id="signupform-username" class="form-control" name="SignupForm[username]">
                                            <div class="help-block" style="color: red"><?php if(isset ($model->errors['username'])) echo $model->errors['username'][0];?></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="correo" class="col-lg-4 control-label">Correo <span class="require">*</span></label>
                                        <div class="col-lg-8">
                                            <input id="signupform-email" type="text" class="form-control" name="SignupForm[email]">
                                            <div class="help-block" style="color: red"><?php if(isset ($model->errors['email'])) echo $model->errors['email'][0];?></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="col-lg-4 control-label">Contraseña <span class="require">*</span></label>
                                        <div class="col-lg-8">
                                            <input type="password" id="signupform-password" class="form-control" name="SignupForm[password]">
                                            <div class="help-block" style="color: red"><?php if(isset ($model->errors['password'])) echo $model->errors['password'][0];?></div>
                                        </div>
                                    </div>
                                </fieldset>

                                <div class="row">
                                    <div class="col-lg-8 col-md-offset-4 padding-left-0 padding-top-20">
                                        <?= Html::submitButton('Crear una cuenta', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                                        <?= Html::a('Cancelar',  Url::toRoute('site/index'), ['class' => 'btn btn-default']) ?>
                                    </div>
                                </div>
                            <?php ActiveForm::end(); ?>


                        </div>
                        <div class="col-md-4 col-sm-4 pull-right">
                            <div class="form-info">
                                <h2><em>Información</em> Importante</h2>
                                <p>Su información personal estará protegida. Sus datos sólo se usarán para crear una cuenta en nuestro sistema.</p>

                                <p>Una vez que llene los datos del registro recibirá un link de activación en su correo.</p>

                                <button type="button" class="btn btn-default">Más detalles</button>
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
