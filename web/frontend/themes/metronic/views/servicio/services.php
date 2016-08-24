<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use common\models\Servicio;


/* @var $this yii\web\View */
/* @var $model app\models\Salon

$this->title = 'Crear Salón';
$this->params['breadcrumbs'][] = ['label' => 'Salons', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;*/
?>
<style>
    .modal-dialog{
        z-index: 10500;
    }
</style>

<h3 class="page-title">
    Servicios del Salón<small></small>
</h3>

<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <?= Html::a('Inicio',  Url::toRoute('site/home')) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            Servicios del salón
        </li>

    </ul>
</div>


<p>
    Agregue todos los servicios que se ofrecen en su salón. El Estado General aparecerá como Incompleto hasta que el servicio sea asignado a un sillón en el siguiente paso.
</p>


<?php if($model == null):?>
    <div class="alert alert-info">
        <strong>Info!</strong> Antes de agregar servicios debe configurar su salón.
        <a href="<?=Url::toRoute('salon/general')?>" class="btn purple" style="  float: right; margin-top: -10px;"> Ir a configurar salón</a>
    </div>
<?php endif;?>

<?php if($model!=null):?>
<div id="message_ajax"></div>
<p>
    <a href="#" id="crear_servicio" action="<?=Url::toRoute('servicio/ajaxcreate')?>" class="btn btn-success"><i class="fa fa-plus"></i> Crear Servicio</a>
    <a href="<?=Url::toRoute(['salon/general'])?>" class="btn default"><i class="fa fa-cog"></i> Ir a Salón</a>
</p>

<?php \yii\widgets\Pjax::begin(['id'=>'servicio_pjax']); ?>
<?= GridView::widget([
    'dataProvider' => $dataprovider,
    'summary' => 'Mostrando {begin} a {end} de {count} resultados',
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        /*[
            'attribute' => 'imagen',
            'format' => 'html',
            'value' => function($model) { return Html::img($model->getImageUrl(), ['width'=>'100']); },
            'contentOptions'=>['style'=>'width: 100px;'],
        ],*/
        'nombre',
        'precio',
        'duracion',
        'estado',
        [  'header' => 'Categoría',
           'content' => function($model, $key, $index, $column){
               if($model->categoria){
                   return $model->categoria->nombre;
               }
               return 'ninguna';
           }
        ],
        [   'header' => 'Estado General',
            'content' => function($model, $key, $index, $column) {
                if($model->estado_sistema == Servicio::ESTADO_SISTEMA_ACTIVO){
                    $tag = '<span class="label label-sm label-success"> Completado </span>';
                }
                else{
                    $tag = '<span class="label label-sm label-danger"> Incompleto </span>';
                }
                return $tag;
            }],
        [   'header' => 'Sillones',
            'content' => function($model, $key, $index, $column) {
                //echo var_dump($servicios->all());die;
                $string = "";
                foreach($model->sillonServicios as $sillon){
                    $string .= $sillon->sillon->nombre.",";
                }
                return rtrim($string, ",");
            }],
        ['class' => 'yii\grid\ActionColumn',
            'template' => '{edit_servicio}{delete_servicio}',
            'controller'=>'servicio',
            'buttons' => [
                'edit_servicio' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', [
                        'title' => Yii::t('app', 'editar servicio'),
                        'class' => 'edit_servicio',
                        'value' => $model->id,
                        'load' => Url::toRoute('servicio/edit'),
                        'action'=>Url::toRoute(['servicio/ajaxupdate', 'id' => $model->id]),
                    ]);
                },'delete_servicio' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', '#', [
                        'title' => Yii::t('app', 'eliminar servicio'),
                        'class' => 'delete_servicio',
                        'value' => $model->id,
                        'action'=>Url::toRoute(['servicio/ajaxdelete', 'id' => $model->id]),
                    ]);
                }
            ]
        ],
        // ['class' => 'yii\grid\ActionColumn','template' => '{update}{delete}','controller'=>'servicio'],
        //['class' => 'yii\grid\ActionColumn']
    ],
]);?>
<?php \yii\widgets\Pjax::end(); ?>

<div class="row">
    <div class="col-md-4">
        <a href="<?=Url::toRoute('salon/general')?>" class="btn btn-success"> Regresar a General</a>
    </div>
    <div class="col-md-4 col-md-offset-4">
        <a href="<?=Url::toRoute('sillon/index')?>" class="btn default" style="float: right;"> Ir a Sillones</a>
    </div>
</div>



<?php Modal::begin(['header' => 'Servicio','id' => 'modal_servicio']);?>
    <?php $form = ActiveForm::begin(['action'=>Url::toRoute('servicio/create'),'id'=>'form_servicio']); ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($servicio, 'nombre')->textInput(['maxlength' => 255]) ?>

                <?= $form->field($servicio, 'duracion')->textInput()->label("Duración(Minutos)") ?>

                <div class="form-group field-servicio-categoriaid has-success">
                    <label class="control-label" for="servicio-categoriaid">Categoría</label>
                    <?= Html::activeDropDownList($servicio, 'categoriaid',$categorias,
                        ['class'=>'form-control']) ?>
                    <div class="help-block"></div>
                </div>

            </div>
            <div class="col-md-6">
                <?= $form->field($servicio, 'precio')->textInput() ?>

                <div class="form-group field-servicio-estado has-success">
                    <label class="control-label" for="servicio-estado">Estado</label>
                    <?= Html::activeDropDownList($servicio, 'estado',[\common\models\Servicio::ESTADO_ACTIVO=>'Activo',\common\models\Servicio::ESTADO_INACTIVO=>'Inactivo'],
                        ['class'=>'form-control']) ?>
                    <div class="help-block"></div>
                </div>

                <?= $form->field($servicio, 'descripcion')->textarea(['maxlength' => 255,'style'=>'min-height:150px;'])->label('Descripción') ?>

                <!--<div class="fileinput fileinput-new" data-provides="fileinput" style="margin-top: 25px;">
                    <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                    </div>
                    <div>
                        <span class="btn default btn-file">
                        <span class="fileinput-new">
                        Seleccionar Imagen </span>
                        <span class="fileinput-exists">
                        Cambiar </span>
                        <input type="file" id="servicio-imagen" name="Servicio[imagen]">
                        </span>
                        <a href="#" class="btn red fileinput-exists" data-dismiss="fileinput">
                            Eliminar </a>
                    </div>
                </div>-->

            </div>
        </div>

        <div class="row">
            <div class="col-md-9">
                <?= Html::submitButton('Aceptar', ['class' => 'btn btn-success']) ?>
                <?= Html::button('Cancelar',['class' => 'btn default','onclick'=>'$("#modal_servicio").modal("hide")']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
<?php Modal::end();?>
<?php endif;?>