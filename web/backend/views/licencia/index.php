<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Licencias';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="licencia-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Licencia', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'fecha_inicio',
            'fecha_fin',
            'licencia_specid',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
