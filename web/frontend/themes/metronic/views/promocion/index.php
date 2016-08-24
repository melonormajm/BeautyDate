<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PromocionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

/*$this->title = Yii::t('app', 'Promocions');
$this->params['breadcrumbs'][] = $this->title;*/
?>

<h3 class="page-title">
    Promociones <small></small>
</h3>


<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <?= Html::a('Inicio',  Url::toRoute('site/index')) ?>
        </li>
    </ul>
</div>

<div class="promocion-index">
    <div id="message_ajax"><?=isset($message)?$message:""?></div>
    <p>
        <a href="<?=Url::toRoute(['promocion/create'])?>" class="btn btn-success"><i class="fa fa-plus"></i> Crear Promoción</a>
        <a href="<?=Url::toRoute(['salon/general'])?>" class="btn default"><i class="fa fa-cog"></i> Ir a Modificar Salón</a>
    </p>

    <?php
    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'pagination' => [
            'pageSize' => 20,
        ],
    ]);

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => 'Mostrando {begin} a {end} de {count} resultados',
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'imagen' => [
                'attribute' => 'imagen',
                'format' => 'html',
                'value' => function($model) { return Html::img(\common\helper\SalonHelper::getPromocionImgUrlFromArray(["imagen"=>$model->imagen], $model->servicio->salonid), ['width'=>'100']); },
                'contentOptions'=>['style'=>'width: 100px;'],
            ],
            [   'header' => 'Fecha Inicio',
                'content'=> function($model, $key, $index, $column){
                    return date_format(date_create($model->fecha_inicio), 'Y-m-d');
                }
            ],
            [   'header' => 'Fecha Fin',
                'content'=> function($model, $key, $index, $column){
                    return date_format(date_create($model->fecha_fin), 'Y-m-d');
                }
            ],
            'operador' => [
                'attribute' => 'operador',
                'format' => 'html',
                'value' => function($model) { return $model->getOpLabel(); },
            ],
            'valor',
            'activa',
            'descripcion',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
