<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SillonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = 'Sillons';
//$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .modal-dialog{
        z-index: 10500;
    }
</style>

<h3 class="page-title">
    Categorías<small></small>
</h3>

<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <?= Html::a('Inicio',  Url::toRoute('site/index')) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            Categorías
        </li>
    </ul>
</div>

<div id="message_ajax"><?=$message?></div>