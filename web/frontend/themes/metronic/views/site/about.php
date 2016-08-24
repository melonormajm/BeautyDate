<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View
$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;*/
?>

<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <?= Html::a('Inicio',  Url::toRoute('servicio/create')) ?>
        </li>
    </ul>
</div>

<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>This is the About page. You may modify the following file to customize its content:</p>

    <code><?= __FILE__ ?></code>
</div>
