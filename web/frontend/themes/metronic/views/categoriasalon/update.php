<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CategoriaSalon */

$this->title = 'Update Categoria Salon: ' . ' ' . $model->categoriaid;
$this->params['breadcrumbs'][] = ['label' => 'Categoria Salons', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->categoriaid, 'url' => ['view', 'categoriaid' => $model->categoriaid, 'salonid' => $model->salonid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="categoria-salon-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
