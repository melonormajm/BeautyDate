<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ClienteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Clientes';
//$this->params['breadcrumbs'][] = $this->title;
?>


<h3 class="page-title">
    Clientes
</h3>

<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <?= Html::a('Inicio',  Url::toRoute('site/home')) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            Clientes
        </li>

    </ul>
</div>




<div class="salon-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <!--<p>
        <?= Html::a('Create Salon', ['create'], ['class' => 'btn btn-success']) ?>
    </p>-->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'header' => 'Nombre',
                'content'=> function($model, $key, $index, $column){

                    if(isset($model->userRedsocials) and count($model->userRedsocials) > 0){
                        $redsocial = $model->userRedsocials[0];
                        return $redsocial->nombre . ' ' . $redsocial->apellido;
                    }
                    else if($model->first_name){
                        return $model->first_name . ' ' . $model->last_name;
                    }else {
                        return $model->email;
                    }
                },
                'filter' => Html::input('text', 'first_name', $searchModel->first_name, ['class'=>'form-control'])
            ],
            'email',
            [
                'header' => 'Reservaciones',
                'content'=> function($model, $key, $index, $column){
                    return count($model->reservaciones);
                }
            ]

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
