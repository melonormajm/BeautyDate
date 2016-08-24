<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SalonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Salons';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="salon-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Salon', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'nombre',
            'cantidad_sillas',
            'thumbnail',
            'ubicacion',
            // 'estado',
            // 'hora_inicio',
            // 'hora_fin',
            // 'usuarioid',
            // 'descripcion',
            // 'descripcion_corta',
            // 'licenciaid',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
