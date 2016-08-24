<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\Promocion;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Promocion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="promocion-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group field-salon-hora_inicio required has-success">
                <!--<label class="control-label" for="salon-hora_inicio">Fecha Inicio:</label>-->
                <!--<input id="promocion-fecha_inicio" class="form-control form-control-inline date-picker" size="16" name="Promocion[fecha_inicio]" value="<?=$model->fecha_inicio;?>" type="text" value="">-->
                <?= $form->field($model, 'fecha_inicio')->textInput(['class'=>'form-control form-control-inline date-picker'])->label('Fecha Inicio') ?>
                <div class="help-block"></div>
            </div>

            <?php $form->field($model, 'operador')->textInput(['maxlength' => 10]) ?>


            <div class="form-group field-promocion-activa has-success">
                <label class="control-label" for="promocion-activa">Estado</label>
                <?= Html::activeDropDownList($model, 'activa',[Promocion::ESTADO_ACTIVO=>'Activo',Promocion::ESTADO_INACTIVO=>'Inactivo'],
                    ['class'=>'form-control','prompt'=>'- Seleccione Estado -']) ?>
                <div class="help-block"></div>
            </div>

            <div class="form-group field-promocion-operador required">
                <label class="control-label" for="promocion-operador">Operador</label>
                <?= /*Html::activeDropDownList($model, 'operador',[Promocion::SIGNO_IGUAL=>'igual  (=)',Promocion::SIGNO_DIVISION=>'división  (/)',
                        Promocion::SIGNO_SUMA=>'suma  (+)',Promocion::SIGNO_RESTA=>'resta  (-)',Promocion::SIGNO_MULTIPLICAR=>'multiplicar  (*)'],
                    ['class'=>'form-control','prompt'=>'- Seleccione Operador -']) */
                Html::activeDropDownList($model, 'operador',[Promocion::SIGNO_RESTA=>'% de Descuento'],
                            ['class'=>'form-control'])
                ?>
                <div class="help-block"></div>
            </div>

            <?= $form->field($model, 'descripcion')->textArea(['maxlength' => 255,'style'=>'min-height:150px;'])->label('Descripción') ?>
        </div>
        <div class="col-md-6">
            <div class="form-group field-salon-hora_inicio required has-success">
                <!--<label class="control-label" for="salon-hora_inicio">Fecha Fin:</label>
                <input id="promocion-fecha_fin" class="form-control form-control-inline date-picker" size="16" name="Promocion[fecha_fin]" value="<?=$model->fecha_fin;?>" type="text" value="">-->
                <?= $form->field($model, 'fecha_fin')->textInput(['class'=>'form-control form-control-inline date-picker'])->label('Fecha Fin') ?>
                <div class="help-block"></div>
            </div>

            <?= $form->field($model, 'valor')->textInput() ?>

            <?php //$form->field($model, 'servicioid')->textInput() ?>


            <?= $form->field($model, 'servicioid')->dropDownList(ArrayHelper::map($servicios, 'id', 'nombre'), ['class'=>'form-control','prompt'=>'- Seleccione Servicio -']) ?>

            <div class="form-group field-promocion-imagen has-success">
                <?php //$form->field($model, 'imagen')->fileInput() ?>
                <div class="fileinput fileinput-new" data-provides="fileinput" style="margin-top: 25px;">
                    <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                        <?php if($model->imagen):?>
                            <?=Html::img(\common\helper\SalonHelper::getPromocionImgUrlFromArray(["imagen"=>$model->imagen], $model->servicio->salonid))?>
                        <?php endif?>
                    </div>
                    <div>
                        <span class="btn default btn-file">
                        <span class="fileinput-new">
                        Seleccionar Imagen </span>
                        <span class="fileinput-exists">
                        Cambiar </span>
                        <input type="file" id="promocion-imagen" name="Promocion[imgfile]">
                        </span>
                        <a href="#" class="btn red fileinput-exists" data-dismiss="fileinput"> Eliminar </a>
                    </div>
                </div>
                <div class="help-block"></div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Crear Promoción') : Yii::t('app', 'Modificar'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Cancelar',  Url::toRoute(['promocion/index']), ['class' => 'btn default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
