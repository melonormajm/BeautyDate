<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Transferencia */

$this->title = 'Create Transferencia';
$this->params['breadcrumbs'][] = ['label' => 'Transferencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transferencia-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
