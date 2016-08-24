<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Licencia Specs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="licencia-spec-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Licencia Spec', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'precio',
            'duracion',
            'tipo_duracion',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
