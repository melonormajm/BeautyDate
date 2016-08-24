<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Enum;
use \yii\helpers\Url;
use \common\models\Salon;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SalonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Salones';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="salon-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'nombre',
            //'cantidad_sillas',
//            'thumbnail',
            'ubicacion',
            [
                'attribute' => 'estado',
                'filter'    => Html::activeDropDownList($searchModel, 'estado',
                    [Salon::ESTADO_ACTIVADO => 'Activo', Salon::ESTADO_INACTIVO => 'Inactivo' ],
                    ['class' => 'form-control', 'style' => 'width: 100px;', 'prompt'=>'']),
                'contentOptions' => array('style' => 'width: 100px;'),
            ],
            //'estado',
            [
                'filter'=>false,
                'attribute' => 'hora_inicio',
                'format' => 'time',
                'value' => function ($model) {
                    return date_create_from_format('Hi', $model->hora_inicio);
                },
                'options'=>['width'=>'100px']
            ],
            [
                'filter'=>false,
                'attribute' => 'hora_fin',
                'format' => 'time',
                'value' => function ($model) {
                    return date_create_from_format('Hi', $model->hora_fin);
                },
                'options'=>['width'=>'100px']
            ],
            // 'usuarioid',
            // 'descripcion',
            // 'descripcion_corta',
            [
                'attribute' => 'licencia.fecha_inicio',
                'label' => 'Inicio Licencia',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->licencia ? Yii::$app->formatter->asDateTime(
                        date_create_from_format('Y-m-d H:i:s', $model->licencia->fecha_inicio))
                        : '-';
                },
            ],
            [
                'attribute' => 'licencia.fecha_fin',
                'label' => 'Fin Licencia',
                'format' => 'raw',
                'value' => function ($model) {
                    return ($model->licencia && $model->licencia->fecha_fin) ? Yii::$app->formatter->asDateTime(
                        date_create_from_format('Y-m-d H:i:s', $model->licencia->fecha_fin))
                        : '-';
                },
            ],
            [
                'attribute' => 'Tipo Licencia',
                'format' => 'raw',
                'value' => function ($model) {
                    return isset($model->licencia) ? (
                            '<div style="float: left;">' . $model->licencia->licenciaSpec->duracion . ' '
                            . Enum::getPlural($model->licencia->licenciaSpec->tipo_duracion) . '</div>'
                            . "<a id='toma' href='" . Url::toRoute(['salon/eliminar-licencia', 'salonid'=>$model->id]) . "' style='float: right;' onclick=\"return confirm('Esta seguro que desea revocar la licencia')\">
                            <span class='label label-sm label-icon label-success'
                                style='padding: 4px 6px 3px 6px;'>
                                <i class='fa fa-minus'></i>
                            </span></a>"
                        ) :
                        (
                            "<span class='label label-sm label-danger'>Sin Licencia</span>
                            <a href='#' style='float: right;'>
                            <!--
                            <span class='label label-sm label-icon label-success'
                                style='padding: 4px 6px 3px 6px;'>
                                <i class='fa fa-plus'></i>
                            </span></a> -->"
                        );
                },
            ],
            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'],
        ],
    ]); ?>

</div>



<?php $this->registerJs("
/*
jQuery('#toma').click(function(e){
    console.log('esto es pa ti');
    e.preventDefault();
    jQuery('#dellicenciaconfirm').modal('show');
});*/
")
?>