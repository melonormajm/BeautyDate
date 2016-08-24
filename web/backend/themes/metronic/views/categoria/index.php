<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Categories');
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    #categoria-grid td img {
        max-width: 80px;
        max-height: 50px;
    }
    #categoria-grid .column-image {
        width: 60px;
        text-align: center;
        padding: 3px;
    }
    #categoria-grid .column-image .fa {
        margin: 15px 0 14px 0;
        vert-align: middle;
    }
</style>
<div class="row">
<div class="col-md-12">

    <p>
        <?= Html::a(Yii::t('backend', 'Add'), ['create'], ['class' => 'btn blue']) ?>
    </p>

    <?= GridView::widget([
        'id' => 'categoria-grid',
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['style' => 'width: 15px;'],
            ],

//            'id',
            [
                'format' => 'html',
                'value' => function($model) {
                    return $model->portada_img ? Html::img($model->getImageurl('portada'))
                        : '<i class="fa fa-tags fa-3x"></i>';
                },
                'contentOptions' => ['class' => 'column-image'],
            ],
            'orden',
            'nombre',
            'descripcion',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
        ],
    ]); ?>

</div>
</div>