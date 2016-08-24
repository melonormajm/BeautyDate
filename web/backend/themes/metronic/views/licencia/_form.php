<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\helpers\ArrayHelper;
use common\models\LicenciaSpec;

/* @var $this yii\web\View */
/* @var $model backend\models\Licencia */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="portlet-body form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="form-body">
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'fecha_inicio')->textInput() ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'fecha_fin')->textInput() ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'licencia_specid')->dropDownList(ArrayHelper::map( LicenciaSpec::find()->all() , 'id', 'nombre') ); ?>
            </div>
        </div>
    </div>
    <div class="form-actions">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => 'btn blue']) ?>
        <a href="<?= \yii\helpers\Url::toRoute('licencia/index'); ?>" class="btn default">Cancelar</a>
    </div>
    <?php ActiveForm::end(); ?>
</div>
