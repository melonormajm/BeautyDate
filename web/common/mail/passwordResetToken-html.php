<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManagerFront->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token,
    'type' => $user->user_type == \common\models\User::TIPO_CLIENTE ? 'app' : '']);
?>
<div class="password-reset">
    <p>Hola <?= Html::encode($user->username) ?>,</p>

    <p>Visite este enlace para cambiar su contraseña:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
