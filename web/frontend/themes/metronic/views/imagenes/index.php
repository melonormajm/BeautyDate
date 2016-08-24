<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ImagenesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Imagenes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="imagenes-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Imagenes', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'nombre',
            'descripcion',
            'url:url',
            'principal',
            // 'salonid',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
