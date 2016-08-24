<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\LicenciaSpec */

$this->title = 'Create Licencia Spec';
$this->params['breadcrumbs'][] = ['label' => 'Licencia Specs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="licencia-spec-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
