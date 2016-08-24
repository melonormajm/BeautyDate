<?php

use yii\grid\GridView;
use common\models\Enum;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SalonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Solicitudes de Licencias';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">

        <div class="alert alert-danger display<?php echo array_key_exists('error', $_GET) ? '' : '-hide' ?>">
            <strong>Error!</strong> La Operación no pudo ser realizada.
        </div>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'espec_nombre',
                    'value' => 'licenciaSpec.nombre',
                    'label' => 'Nombre de Licencia',
                    //'filter' => Html::input('', 'nombre_espec', ['prompt'=>'', 'class' => 'form-control'])
                ],
                [
                    'attribute' => 'estado',
                    'label' => 'Estado',
                    'filter'    => Html::activeDropDownList($searchModel, 'estado',
                        Enum::listEstadoLicencia(),
                        ['prompt'=>'', 'class' => 'form-control', 'style' => 'width: 100px;']),
                    'value' => function ($model) {
                        return Enum::listEstadoLicencia()[$model->estado];
                    },
                    'contentOptions' => array('style' => 'width: 100px;'),
                ],
                [
                    'attribute' => 'duracion',
                    'label' => 'Duración',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return '<div>'.$model->licenciaSpec->duracion . ' '
                            . Enum::getPlural($model->licenciaSpec->tipo_duracion) . '</div>';
                    },
                ],
                [
                    'attribute' => 'propietario_Viejo',
                    'label' => 'Propietario',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return isset($model->usuario) ? $model->propietario->email
                            : '';
                    },
                ],/*
                [
                    'label'=>'',
                    'format' => 'raw',
                    'value'=>function ($model) {
                        return Html::a('Confirmar', ['licencia/confirm', 'id' => $model->id]);
                    },
                ],*/
            ],
        ]); ?>

    </div>
</div>
