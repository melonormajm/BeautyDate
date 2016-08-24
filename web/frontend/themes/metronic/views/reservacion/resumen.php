<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ServicioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reservaciones';
//$this->params['breadcrumbs'][] = $this->title;
?>

<h3 class="page-title">
    Resumen de reservaciones
</h3>

<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <?= Html::a('Inicio',  Url::toRoute('site/home')) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            Reservaciones
        </li>

    </ul>
</div>

<div class="reservacion-index">
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => 'Mostrando {begin} a {end} de {count} resultados',
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'fecha',
                //'model' => $searchModel,
                //'value' => 'fecha',
                //'filter' => \yii\jui\DatePicker::widget(['language' => 'ru', 'dateFormat' => 'dd-MM-yyyy']),
                //'format' => 'html',
                'content' => function($model, $key, $index, $column){
                    return date('d/m/Y', strtotime($model->fecha));
                    //return date_format(date($model->fecha), 'm/d/Y');
                 },
                'filterInputOptions' =>['class'=>'date-picker form-control', 'dateFormat' => 'dd-mm-yy']
            ],
            [   'attribute' => 'hora_inicio',
                'value' => 'hora_inicio',
                'filterInputOptions' =>['class'=>'form-control timepicker'],
                'content'=> function($model, $key, $index, $column){
                    return date_format(date_create($model->hora_inicio), 'H:i A');
                }
            ],
            [   'header' => 'Hora fin',
                'content'=> function($model, $key, $index, $column){
                    return date_format(date_create($model->hora_fin), 'H:i A');
                }
            ],
            [
                'header' => 'Usuario',
                'content' => function($model, $key, $index, $column){

					$user = $model->usuario;
                    if(!$user)
                        return '';

					if(isset($user->userRedsocials) and count($user->userRedsocials) > 0){
						$redsocial = $user->userRedsocials[0];
						return $redsocial->nombre . ' ' . $redsocial->apellido;
					}
					else if($user->first_name){
						return $user->first_name . ' ' . $user->last_name;											
					}else {
						return $user->email;
					}
                },
                'filter' => Html::input('text', 'cliente', $searchModel->cliente, ['class'=>'form-control'])
            ],
            [

                'attribute' => 'estado',
                'filter'    => Html::activeDropDownList($searchModel, 'estado', [\common\models\Reservacion::ESTADO_PENDIENTE => 'Pendiente',\common\models\Reservacion::ESTADO_EJECUTADA => 'Ejecutada', \common\models\Reservacion::ESTADO_CANCELADA => 'Cancelada' ],['class' => 'form-control']),
            ]
            //'estado',
            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>