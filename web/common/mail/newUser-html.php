<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

?>
<div class="password-reset">
    <p>Bienvenido <?= Html::encode($user->username) ?>,</p>

    <p>Usted se ha registrado en BeautyDate con los siguientes datos:</p>

    <p>Usuario: <?= $user->username; ?><br />
        <?= Html::encode('Contraseña:' . $pass) ; ?>
    </p>
</div>
