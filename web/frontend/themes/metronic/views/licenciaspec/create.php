<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\LicenciaSpec */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Licencia Spec',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Licencia Specs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="licencia-spec-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
