<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

//$this->title = $name;
$this->title = 'Error';
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <p>
        <?= nl2br(Html::encode($message)) ?>
        </p><p>
        <?= $exception->getMessage(); ?>
        </p>
    </div>

    <p>
        Por favor cont&aacute;ctenos si piensa que esto es un error
    </p>

</div>
