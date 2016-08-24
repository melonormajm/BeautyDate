<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SillonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = 'Sillons';
//$this->params['breadcrumbs'][] = $this->title;


        $JSCode = <<<EOF
function(start, end) {
    $('#modal_evento').modal('show');
    var title = 'prueba';
    //var title = prompt('Nombre del Evento:');
    var eventData;
    if (title) {
        eventData = {
            title: title,
            start: start,
            end: end
        };
        $('#w0').fullCalendar('renderEvent', eventData, true);
    }
    $('#w0').fullCalendar('unselect');
}
EOF;
?>
<style>
    .modal-dialog{
        z-index: 10500;
    }
</style>


<h3 class="page-title">
    Sillones del Salón<small></small>
</h3>

<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <?= Html::a('Inicio',  Url::toRoute('site/index')) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            Sillones
        </li>
    </ul>
</div>

<p>Ingrese los sillones/lugares en los cuales se ofrecen servicios en su salón.
    En cada uno, agregue todos los servicios que pueden ser realizados en ese sillón.
    Los usuarios harán citas para su salón escogiendo un servicio, y la reservación se guardará en un sillón que brinde el servicio seleccionado.</p>

<div class="sillon-index">
    <div id="message_ajax"><?=isset($message)?$message:null?></div>
    <p>
        <a href="#" class="btn btn-success" id="crear_sillon" action="/index.php?r=sillon%2Fcreate"><i class="fa fa-plus"></i> Crear Sillón</a>
        <a href="<?=Url::toRoute(['salon/general'])?>" class="btn default"><i class="fa fa-cog"></i> Ir a Salón</a>
    </p>

    <?php \yii\widgets\Pjax::begin(['id'=>'sillon_pjax']); ?>
    <?php
    $data = new ActiveDataProvider([
        'query' => $dataProvider,
        'pagination' => [
            'pageSize' => 15,
        ],
    ]);
    echo GridView::widget([
        'dataProvider' => $data,
        'summary' => 'Mostrando {begin} a {end} de {count} resultados',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'nombre',
            'estado',
            // ['class' => 'yii\grid\ActionColumn','template' => '{update}{delete}','controller'=>'servicio'],
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{edit_sillon}{delete_sillon}',
                'controller'=>'sillon',
                'buttons' => [
                    'edit_sillon' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', [
                            'title' => Yii::t('app', 'editar sillon'),
                            'class' => 'edit_sillon',
                            'value' => $model->id,
                            'load' => Url::toRoute('sillon/edit'),
                            //'action'=>Url::toRoute(['sillon/ajaxupdate', 'id' => $model->id]),
                            'action'=>Url::toRoute(['sillon/ajaxupdate', 'id' => $model->id, 'XDEBUG_SESSION_START'=> 'phpstorm']),
                        ]);},
                    'delete_sillon' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', '#', [
                                'title' => Yii::t('app', 'eliminar sillón'),
                                'class' => 'delete_sillon',
                                'value' => $model->id,
                                'action'=>Url::toRoute(['sillon/ajaxdelete', 'id' => $model->id]),
                            ]);
                        }
                ]
            ]
        ],
    ]);?>
    <?php \yii\widgets\Pjax::end(); ?>
    <?php /*GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'nombre',
            'estado',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);*/ ?>

</div>

<div class="row">
    <div class="col-md-4">
        <a href="<?=Url::toRoute('servicio/services')?>" class="btn btn-success"> Regresar a Servicios</a>
    </div>
    <div class="col-md-4 col-md-offset-4">
        <a href="<?=Url::toRoute('imagenes/list')?>" class="btn default" style="float: right;"> Ir a Imágenes</a>
    </div>
</div>


<?php Modal::begin(['header' => 'Sillones','id' => 'modal_sillon']);?>
    <?php $form = ActiveForm::begin(['action'=>Url::toRoute('sillon/create'),'id'=>'form_sillon']); ?>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'nombre')->textInput(['maxlength' => 20]) ?>
            </div>
            <div class="col-md-6">
                <div class="form-group field-servicio-estado has-success">
                    <label class="control-label" for="sillon-estado">Estado</label>
                    <?= Html::activeDropDownList($model, 'estado',[\common\models\Servicio::ESTADO_ACTIVO=>'Activo',\common\models\Servicio::ESTADO_INACTIVO=>'Inactivo'],
                        ['class'=>'form-control','prompt'=>'- Seleccione Estado -']) ?>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 10px;">
            <div class="form-group">
                <div class="col-md-12">
                    <!--<label class="control-label" for="sillon-servicios">Servicios del sillón</label>-->
                    <select multiple="multiple" class="multi-select" id="my_multi_select1" name="servicios_list[]">
                        <?php foreach($servicios as $servicio):?>
                            <option value="<?= $servicio->id?>"> <?= $servicio->nombre?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 40px;">
            <div class="col-md-6">
                <div class="form-group">
                    <?= Html::submitButton('Aceptar',['class' => 'btn btn-success']) ?>
                    <?= Html::button('Cancelar',['class' => 'btn default','onclick'=>'$("#modal_sillon").modal("hide")']) ?>
                </div>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
<?php Modal::end();?>
