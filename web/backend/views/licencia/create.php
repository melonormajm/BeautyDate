<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Licencia */

$this->title = 'Create Licencia';
$this->params['breadcrumbs'][] = ['label' => 'Licencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="licencia-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
