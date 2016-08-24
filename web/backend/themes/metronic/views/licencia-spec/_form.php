<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \common\models\Enum;
use \common\models\Moneda;
use \yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\LicenciaSpec */
/* @var $form yii\widgets\ActiveForm */

$scriptJS = <<<EOF

$('#licenciaspec-tipo').on('change',function(e){
    var estado_seleccionado = $(this).val();
    if (estado_seleccionado == 'SUSCRIPCION')
        $('#host_button_cont').show();
    else
        $('#host_button_cont').hide();
});
EOF;

$this->registerJs($scriptJS);
?>

<div class="portlet-body form">
    <?php $form = ActiveForm::begin([
        'options'=>['enctype' => 'multipart/form-data']
    ]); ?>
    <div class="form-body">
        <div class="row">
            <div class="col-md-12">
            <?= $form->field($model, 'nombre')->textInput() ?>
            <?= $form->field($model, 'descripcion')->textarea(['rows'=>2]) ?>
        </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'estado')->dropDownList(Enum::listEstado()); ?>
                <?= $form->field($model, 'tipo')->dropDownList(Enum::listTipoLicencia(), $model->isNewRecord ? [] : ['disabled' => '']) ; ?>
                <?= $form->field($model, 'hosted_button_id', ['options' => ['id' => 'host_button_cont',
                    $model->tipo == Enum::TIPO_LICENCIA_SUSCRIPTION ? '' : 'style' => 'display: none']])
                    ->textInput(); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <?= $form->field($model, 'precio')->textInput() ?>
            </div>
            <div class="col-md-3">
                <!--
                <?php /*echo $form->field($model, 'moneda_id')->dropDownList(ArrayHelper::map( Moneda::find()->all() , 'id', 'siglas') );*/ ?>
                -->
                <div style="margin-top: 32px;">
                <?= Html::label(Yii::$app->params['moneda.pago']); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <?= $form->field($model, 'duracion')->textInput() ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'tipo_duracion')->dropDownList(Enum::listTipoDuracion()); ?>
            </div>
        </div>
		<!--
        <div class="row">
            <div class="col-md-12">
                <?//= $form->field($model, 'boton')->textarea(['rows'=>3]) ?>
                <?//= $form->field($model, 'boton_unsub')->textarea(['rows'=>3]) ?>
            </div>
        </div>
		-->
    </div>
    <div class="form-actions">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'btn blue']) ?>
        <a href="<?= \yii\helpers\Url::toRoute('licencia-spec/index'); ?>" class="btn default"><?=Yii::t('backend', 'Cancel'); ?></a>
    </div>
    <?php ActiveForm::end(); ?>
</div>