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
    <?= $model->nombre?> <small>horario <?= $model->hora_inicio." - ".$model->hora_fin?></small>
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
    <a class="pull-left" href="#">
        <img class="media-object" src="<?php if(isset($thumbnail))echo $thumbnail->getImageUrl();?>" alt="No se ha especificado imagen principal" data-src="holder.js/64x64" style="min-width: 200px;
  min-height: 200px;max-width: 320px; margin-right: 20px;">
    </a>
    <div class="media-body">
        <h4 class="media-heading">Descripción Corta</h4>
        <p>
            <?= $model->descripcion_corta?>
        </p>
        <br />
        <h4 class="media-heading">Descripción</h4>
        <p>
            <?= $model->descripcion?>
        </p>

    </div>
    <h3>Imagenes</h3>
    <ul class="list-inline blog-images">
        <?php foreach($imagenes as $imagen):?>
            <li>
                <a class="fancybox-button" data-rel="fancybox-button" title="390 x 220 - keenthemes.com" href="<?= $imagen->getImageUrl()?>">
                    <img alt="" src="<?= $imagen->getImageUrl()?>">
                </a>
            </li>
        <?php endforeach?>
    </ul>

    <a href="<?=Url::toRoute('salon/manage')?>" type="button" class="btn green" style="margin-top: 20px;">
        <i class="fa fa-cogs"></i> Editar
    </a>

</div>
<!--end col-md-9-->
<div class="col-md-3 blog-sidebar">

    <h3>Categorías</h3>
    <ul class="list-inline sidebar-tags">
        <?php foreach($categorias as $cat):?>
        <li>
            <a href="#">
                <i class="fa fa-tags"></i><?=$cat->nombre?> </a>
        </li>
        <?php endforeach?>
    </ul>
    <div class="space20"></div>
    <div style="margin-top: 40px;">
        <h4 class="media-heading">Ubicación</h4>
        <p>
            <?= $model->ubicacion?>
        </p>
    </div>

</div>

<!--end col-md-3-->
</div>
</div>
</div>