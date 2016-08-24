<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .help-block {
        color: #A94442;
    }
</style>
<!-- BEGIN LOGIN FORM -->
<!-- <form class="login-form" action="<?php echo \yii\helpers\Url::toRoute('site/login'); ?>" method="post"> -->
<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
    <h3 class="form-title">Inicie Sesi&oacute;n</h3>
<!--
    <div class="alert alert-danger display<?php echo $invalid_cred ? '' : '-hide' ?>">
        <button class="close" data-close="alert"></button>
			<span>
			Enter any username and password. </span>
    </div>
-->
    <div class="form-group">
        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            <label class="control-label visible-ie8 visible-ie9">Usuario</label>
        <div class="input-icon">
            <i class="fa fa-user"></i>
            <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Usuario" name="LoginForm[username]"/>
        </div>
        <div class="help-block"><?= isset($model->errors['username']) ? $model->errors['username'][0] : '' ?></div>
    </div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">Password</label>
        <div class="input-icon">
            <i class="fa fa-lock"></i>
            <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="LoginForm[password]"/>
        </div>
        <div class="help-block"><?= isset($model->errors['password']) ? $model->errors['password'][0] : '' ?></div>
    </div>
    <div class="form-actions">
        <label class="checkbox">
            <input type="checkbox" name="remember" value="1"/> Remember me </label>
        <button type="submit" class="btn green pull-right">
            Login <i class="m-icon-swapright m-icon-white"></i>
        </button>
    </div>

<?php ActiveForm::end(); ?>
<!-- END LOGIN FORM -->

