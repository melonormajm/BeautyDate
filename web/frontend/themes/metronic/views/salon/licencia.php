<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\models\Servicio;
use app\models\ServicioSearch;
use app\models\Categoria;
use app\models\Sillon;

/* @var $this yii\web\View */
/* @var $model app\models\Salon

$this->title = 'Update Salon: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Administrar Salón', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nombre];*/
?>

<h3 class="page-title">
    Licencia <small> pagar licencia</small>
</h3>

<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <?= Html::a('Inicio',  Url::toRoute('site/home')) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <?= Html::a('Ver Salón',  Url::toRoute('salon/manage')) ?>
        </li>

    </ul>

</div>

<div class="row">
<div class="col-md-12 blog-page">
<div class="row">

<div class="col-md-9 article-block">


</div>
<!--end col-md-9-->
<div class="col-md-3 blog-sidebar">



</div>

<!--end col-md-3-->
</div>
</div>
</div>