<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
$this->title = 'Beauty Date';
?>

<h3 class="page-title">
    Licencia <small>adquirida</small>
</h3>


<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <?= Html::a('Inicio',  Url::toRoute('site/index')) ?>
        </li>
    </ul>
</div>

<div class="note note-success">
    <h4 class="block">Solicitud de Licencia Realizada!</h4>
    <p>
        Uno de los administradores comprobará y aprobará su solicitud. Es ese momento podrá empezar a recibir reservaciones.
    </p>
</div>