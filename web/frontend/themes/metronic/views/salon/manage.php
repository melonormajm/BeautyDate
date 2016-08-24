<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use common\models\Servicio;
use app\models\ServicioSearch;
use common\models\Categoria;
use common\models\Sillon;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model app\models\Salon

$this->title = 'Update Salon: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Administrar Salón', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nombre];*/
?>
<style>
    .modal-dialog{
        z-index: 10500;
    }
</style>
<h3 class="page-title">
    <?= $model->nombre?> <small>modificar datos</small>
</h3>

<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <?= Html::a('Inicio',  Url::toRoute('site/home')) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <i></i>
            <?= Html::a('Salón',  Url::toRoute('salon/preview')) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li location="salon" class="salon">
            <?= Html::a('Administrar Salón',  Url::toRoute('salon/manage')) ?>
        </li>

    </ul>
</div>

<div id="message_ajax"></div>

<div class="tabbable tabbable-custom boxless tabbable-reversed">
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#tab_0" data-toggle="tab">
                General </a>
        </li>
        <li class="">
            <a href="#tab_1" data-toggle="tab">
                Servicios </a>
        </li>
        <li class="">
            <a href="#tab_2" data-toggle="tab">
                Categoría </a>
        </li>
        <li class="">
            <a href="#tab_3" data-toggle="tab">
                Imágenes </a>
        </li>
        <li>
            <a href="#tab_4" data-toggle="tab">
                Sillones </a>
        </li>


    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_0">

                <div class="portlet-body form">
                    <!-- BEGIN FORM-->

                    <div class="form-body">
                        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'nombre')->textInput(['maxlength' => 255]) ?>

                                <div class="form-group field-salon-hora_inicio required has-success">
                                    <label class="control-label" for="salon-hora_inicio">Hora Inicio</label>
                                    <div class="input-icon">
                                        <i class="fa fa-clock-o"></i>
                                        <input type="text" id="salon-hora_inicio" class="form-control  timepicker timepicker-default" name="Salon[hora_inicio]" value="<?=$model->hora_inicio;?>" maxlength="8">
                                    </div>
                                    <div class="help-block"></div>
                                </div>

                                <?= $form->field($model, 'ubicacion')->textarea(['maxlength' => 255]) ?>

                                <?= $form->field($model, 'descripcion_corta')->textarea(['maxlength' => 255]) ?>
                            </div>

                            <div class="col-md-6">

                                <div class="form-group field-salon-estado required">
                                    <label class="control-label" for="salon-estado">Estado</label>
                                    <select id="salon-estado" class="form-control" name="Salon[estado]">
                                        <option value="<?=$model->estado ? $model->estado : null;?>" selected="selected"><?=$model->estado ? $model->estado : 'seleccione';?> </option>
                                        <option value="Activo">Activo</option>
                                        <option value="Inactivo">Inactivo</option>
                                    </select>
                                    <div class="help-block"></div>
                                </div>

                                <div class="form-group field-salon-hora_fin required has-success">
                                    <label class="control-label" for="salon-hora_fin">Hora fin</label>
                                    <div class="input-icon">
                                        <i class="fa fa-clock-o"></i>
                                        <input type="text" id="salon-hora_fin" class="form-control  timepicker timepicker-default" name="Salon[hora_fin]" value="<?=$model->hora_fin;?>" maxlength="8">
                                    </div>
                                    <div class="help-block"></div>
                                </div>

                                <?= $form->field($model, 'descripcion')->textarea(['maxlength' => 500]) ?>

                            </div>
                        </div>

                    </div>

                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-9">
                                <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Modificar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                            </div>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>
                    <!-- END FORM-->
                </div>

        </div>
        <div class="tab-pane" id="tab_1">
            <p>
                <?= Html::button('Crear Servicio', ['class' => 'btn btn-success','id' => 'crear_servicio','action'=>Url::toRoute('servicio/create')]) ?>
            </p>
            <?php \yii\widgets\Pjax::begin(['id'=>'servicio_pjax']); ?>
            <?php
            $dataProvider = new ActiveDataProvider([
                'query' => Servicio::find()->where(['salonid'=>$model->id]),
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'nombre',
                    'precio',
                    'duracion',
                    'descripcion',
                   // ['class' => 'yii\grid\ActionColumn','template' => '{update}{delete}','controller'=>'servicio'],
                    ['class' => 'yii\grid\ActionColumn',
                        'template' => '{edit_servicio}{delete}',
                        'controller'=>'servicio',
                        'buttons' => [
                            'edit_servicio' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', [
                                    'title' => Yii::t('app', 'edit_servicio'),
                                    'class' => 'edit_servicio',
                                    'value' => $model->id,
                                    'action'=>Url::toRoute(['servicio/update', 'id' => $model->id]),
                                ]);
                            },/*
                            'delete_service' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                    'title' => Yii::t('yii', 'Delete'),
                                    'data-pjax'=>'servicio_pjax',
                                ]);
                            }*/
                        ]
                    ],
                ],
            ]);?>
            <?php \yii\widgets\Pjax::end(); ?>
        </div>
        <div class="tab-pane" id="tab_2">
            <p>
                <?= Html::button('Agregar Categoría', ['class' => 'btn btn-success','id' => 'crear_categoria']) ?>
            </p>
            <?php \yii\widgets\Pjax::begin(['id'=>'categoria_pjax']); ?>
            <?php

            $dataProvider = new ActiveDataProvider([
                'query' => $categorias,
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);

            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'nombre',
                    'descripcion',
                    [
                        'attribute' => 'thumbnail',
                        'format' => 'html',
                        'value' => function($model) { return Html::img($model->getImageUrl(), ['width'=>'100']); },
                        'contentOptions'=>['style'=>'width: 100px;'],
                    ],
                    ['class' => 'yii\grid\ActionColumn',
                        'template' => '{deletecat}',
                        'buttons' => [
                            'deletecat' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                    'title' => Yii::t('app', 'deletecat'),
                                ]);
                            }
                        ]
                    ],/*
                    ['class' => 'yii\grid\ActionColumn',
                        'template' => '{deletecat}',
                        'controller'=>'servicio',
                        'buttons' => [
                            'deletecat' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', '#', [
                                    'title' => Yii::t('app', 'desasociar categoria'),
                                    'class' => 'delete_cat',
                                    'value' => $model->id,
                                ]);
                            }
                        ]
                    ]*/
                ],
            ]);?>
            <?php \yii\widgets\Pjax::end(); ?>
        </div>
        <div class="tab-pane" id="tab_3">
            <p>
                <?= Html::button('Agregar Imagen', ['class' => 'btn btn-success','id' => 'crear_imagen']) ?>
            </p>
            <?php \yii\widgets\Pjax::begin(['id'=>'imagenes_pjax']); ?>
            <?php
            $dataProvider = new ActiveDataProvider([
                'query' => \common\models\Imagenes::find()->where(['salonid'=>$model->id]),
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'attribute' => 'imagen',
                        'format' => 'html',
                        'value' => function($model) { return Html::img($model->getImageUrl(), ['width'=>'100']); },
                        'contentOptions'=>['style'=>'width: 100px;'],
                    ],
                    [
                        'attribute' => 'principal',
                        'format' => 'raw',
                        'value' => function ($model, $index, $widget) {
                            return Html::checkbox('principal[]', $model->principal, ['value' => $index, 'disabled' => true,'width'=>'100']);
                        },
                    ],
                    'nombre',
                    'descripcion',
                    ['class' => 'yii\grid\ActionColumn',
                        'controller'=>'imagenes',
                        'template' => '{edit_imagen} {delete}',
                        'buttons' => [
                            'edit_imagen' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', [
                                    'title' => Yii::t('app', 'edit_imagen'),
                                    'class' => 'edit_imagen',
                                    'value' => $model->id,
                                ]);
                            }
                        ]],
                ],
            ]);?>
            <?php \yii\widgets\Pjax::end(); ?>

        </div>
        <div class="tab-pane" id="tab_4">
            <p>
                <?= Html::button('Agregar Sillón', ['class' => 'btn btn-success','id' => 'crear_sillon']) ?>
            </p>
            <?php
            $dataProvider = new ActiveDataProvider([
                'query' => Sillon::find()->where(['salonid'=>$model->id]),
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);

            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'nombre',
                    'estado',
                    [   'header' => 'Servicios',
                        'content' => function($model, $key, $index, $column) {
                        //echo var_dump($servicios->all());die;
                            $string = "";
                        foreach($model->getSillonServicios()->all() as $servicio){
                            $string .= $servicio->servicio->nombre.", ";
                        }
                        return rtrim($string, ',');
                    }],
                    ['class' => 'yii\grid\ActionColumn','controller'=>'sillon', 'template' => '{update}{delete}'],
                ],
            ]);?>

        </div>



    </div>
</div>

<!--Modales-->
<?php Modal::begin(['header' => 'Servicio','id' => 'modal_servicio']);?>
    <?php $form = ActiveForm::begin(['action'=>Url::toRoute('servicio/create'),'id'=>'form_servicio','options' => ['class' => 'create']]); ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($servicio, 'nombre')->textInput(['maxlength' => 255]) ?>

                <?= $form->field($servicio, 'duracion')->textInput() ?>

                <?= $form->field($servicio, 'descripcion')->textarea(['maxlength' => 255]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($servicio, 'precio')->textInput() ?>

                <?php $estado = $servicio->estado ? $servicio->estado : "Activo"?>
                <div class="form-group field-servicio-estado required">
                    <label class="control-label" for="servicio-estado">Estado</label>
                    <select id="servicio-estado" class="form-control" name="Servicio[estado]">
                        <option value="<?= $estado?>" selected="selected"><?= $estado?></option>
                        <option value="ACTIVO" >Activo</option>
                        <option value="INACTIVO">Inactivo</option>
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-9">
                <?= Html::submitButton($servicio->isNewRecord ? 'Crear' : 'Modificar', ['class' => $servicio->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
<?php Modal::end();?>

<?php Modal::begin(['header' => 'Categoría','id' => 'modal_categoria']);?>
    <?php $form = ActiveForm::begin(['action'=>Url::toRoute('categoriasalon/create'),'id'=>'form_categoria']); ?>
    <div class="row">
        <div class="col-md-5">
            <?=$form->field($categorysalon, 'categoriaid')->dropDownList($dataList,
                ['prompt'=>'-Seleccione una categoría-'])->label('Categoría') ?>
        </div>

        <div class="col-md-8">
            <div class="form-group">
                <?= Html::submitButton($categorysalon->isNewRecord ? 'Agregar' : 'Update', ['class' => $categorysalon->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
<?php Modal::end();?>

<?php Modal::begin(['header' => 'Imagen','id' => 'modal_imagen']);?>
    <form id="form_imagenes" action="<?=Url::toRoute('imagenes/create')?>" method="post" enctype="multipart/form-data" onsubmit="return uploadImageFile();">
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($imagenes, 'nombre')->textInput(['maxlength' => 50]) ?>

                <?= $form->field($imagenes, 'principal')->checkbox() ?>

                <?= $form->field($imagenes, 'url')->fileInput() ?>

                <?= $form->field($imagenes, 'descripcion')->textArea(['maxlength' => 100]) ?>

                <?php //$form->field($model, 'principal')->checkbox() ?>
            </div>
        </div>

        <div class="form-group">
            <button class="btn btn-success">Aceptar</button>
            <?= Html::submitButton($imagenes->isNewRecord ? 'Agregar' : 'Modificar', ['class' => $imagenes->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::a('Cancelar',  Url::toRoute('salon/manage'), ['class' => 'btn default']) ?>
        </div>
    </form>
<?php Modal::end();?>

<?php Modal::begin(['header' => 'Sillones','id' => 'modal_sillon']);?>
    <?php $form = ActiveForm::begin(['action'=>Url::toRoute('sillon/savesillon'),'id'=>'form_sillon']); ?>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($sillon, 'nombre')->textInput(['maxlength' => 20]) ?>
            </div>
            <div class="col-md-6">

                <?php $estado = $sillon->estado ? $sillon->estado : 'Activo'?>
                <div class="form-group field-sillon-estado required">
                    <label class="control-label" for="sillon-estado">Estado</label>
                    <select id="sillon-estado" class="form-control" name="Sillon[estado]">
                        <option value="<?= $estado?>" selected="selected"><?= $estado?></option>
                        <option value="Activo" >Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
        <?php //$form->field($model, 'salonid')->textInput() ?>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <?= Html::submitButton($sillon->isNewRecord ? 'Crear' : 'Modificar', ['class' => $sillon->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    <?= Html::a('Cancelar',  Url::toRoute('salon/manage'), ['class' => 'btn default']) ?>
                </div>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
<?php Modal::end();?>

<?php Modal::begin(['header' => 'Advertencia','id' => 'modal_delete_servicio']);?>
    <div class="row">
        Está seguro de que desea eliminar este servicio?
    </div>
    <div class="form-group">
        <button class="btn btn-success" onclick="eliminar_servicio()">Aceptar</button>
        <button class="btn default" onclick="$('#modal_delete_servicio').modal('hide')">Cancelar</button>
    </div>
<?php Modal::end()?>