<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CategoriaSalonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Categoria Salons';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categoria-salon-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Categoria Salon', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'categoriaid',
            'salonid',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
