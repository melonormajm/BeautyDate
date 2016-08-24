<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \common\models\Enum;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tipos de Licencia';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
<div class="col-md-12">

    <p>
        <?= Html::a('Adicionar', ['create'], ['class' => 'btn blue']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'nombre',
            [
                'attribute' => 'precio_moneda',
                'label' => 'Precio',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<div>' . Yii::$app->formatter->asCurrency($model->precio, $model->moneda->siglas) . '</div>';
                },
            ],
            [
                'attribute' => 'estado',
                'label' => 'Estado',
                'format' => 'raw',
                'filter'    => Html::activeDropDownList($searchModel, 'estado',
                    Enum::listEstado(), ['prompt'=>'', 'class' => 'form-control']),
                'value' => function ($model) {
                    return '<div>'. Enum::getLabel($model->estado) . '</div>';
                },
            ],
            [
                'attribute' => 'tipo',
                'label' => 'Tipo de licencia',
                'format' => 'raw',
                'filter'    => Html::activeDropDownList($searchModel, 'tipo',
                    Enum::listTipoLicencia(), ['prompt'=>'', 'class' => 'form-control']),
                'value' => function ($model) {
                    return '<div>'. Enum::getLabel($model->tipo) . '</div>';
                },
            ],
            //'duracion',
            [
                'attribute' => 'duracion_label',
                'label' => 'Duración',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<div>'.$model->duracion . ' '
                    . Enum::getPlural($model->tipo_duracion) . '</div>';
                },
            ],
            [
                'attribute' => 'descripcion',
                //'label' => 'Duración',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->descripcion ? $model->descripcion : '';
                },
            ],

            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
        ],
    ]); ?>

</div>
</div>