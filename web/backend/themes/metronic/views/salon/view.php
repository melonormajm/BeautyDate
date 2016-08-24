<link rel="stylesheet" type="text/css" href="themes/beauty/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css"/>
<link href="themes/beauty/admin/pages/css/profile.css" rel="stylesheet" type="text/css"/>
<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \common\models\Salon;
use \common\models\Enum;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model backend\models\Categoria */

$this->title = $model->nombre;
//$this->params['title_detail'] = 'hoy va a ser un buen dia';
$this->params['breadcrumbs'][] = ['label' => 'Salones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['title_2'] = $this->title;
?>

<style>
.modal-dialog{
    z-index: 10500;
}
</style>

<div class="row profile">

    <div class="col-md-6">
        <div class="portlet box blue-steel">
            <div class="portlet-title">
                <div class="caption">
                    General
                </div>

            </div>
            <div class="portlet-body">
                <div class="row static-info">
                    <div class="col-md-5 name">
                        Horario
                    </div>
                    <div class="col-md-5 value">
                        <?=Yii::$app->formatter->asTime(date_create_from_format('Hi', $model->hora_inicio)) ?>
                        -
                        <?=Yii::$app->formatter->asTime(
                            date_create_from_format('Hi', $model->hora_fin)) ?>
                    </div>
                </div>
                <div class="row static-info">
                    <div class="col-md-5 name">
                        Ubicación
                    </div>
                    <div class="col-md-5 value">
                        <?=$model->ubicacion ?>
                    </div>
                </div>
                <div class="row static-info">
                    <div class="col-md-5 name">
                        Estado
                    </div>
                    <div class="col-md-5 value">
                        <span class="label label-<?=$model->estado == Salon::ESTADO_ACTIVADO ? 'success' : 'danger' ?>" style="margin-top: 5px;" >
                                <?=$model->estado ?>
                        </span>
                    </div>
                </div>
                <div class="row static-info">
                    <div class="col-md-5 name">
                        Estado sistema
                    </div>
                    <div class="col-md-5 value">
                        <span class="label label-<?=$model->estado_sistema == Salon::ESTADO_ACTIVADO ? 'success' : 'danger' ?>" style="margin-top: 5px;" >
                            <?=$model->estado_sistema ?>
                        </span>
                    </div>

                </div>
                <div class="row static-info">
                    <div class="col-md-5 name">
                        Moneda
                    </div>
                    <div class="col-md-5 value">
                        <?=$model->moneda->nombre . ' (' . $model->moneda->siglas . ')' ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="portlet box blue-steel">
            <div class="portlet-title">
                <div class="caption">
                    Descripcion
                </div>
            </div>
            <div class="portlet-body">
                <p>
                    <?=$model->descripcion ?>
                </p>
            </div>
        </div>
    </div>
</div>
<div class="row">
<div class="col-md-6">
    <?php if( $model->licencia):?>
        <div class="portlet box green-haze">
            <div class="portlet-title">
                <div class="caption">
                    Licencia
                </div>
            </div>
            <div class="portlet-body">
                <div class="row static-info">
                    <div class="col-md-5 name">
                        Licencia ID
                    </div>
                    <div class="col-md-5 value">
                        <?php echo $model->licencia->id;?>
                    </div>
                </div>
                <div class="row static-info">
                    <div class="col-md-5 name">
                        Nombre
                    </div>
                    <div class="col-md-5 value">
                        <?php echo $model->licencia->licenciaSpec->nombre;?>
                    </div>
                </div>
                <div class="row static-info">
                    <div class="col-md-5 name">
                        Tipo de licencia
                    </div>
                    <div class="col-md-5 value">
                        <?php echo \common\models\Enum::getLabel($model->licencia->licenciaSpec->tipo);?>
                    </div>
                </div>
                <div class="row static-info">
                    <div class="col-md-5 name">
                        Estado
                    </div>
                    <div class="col-md-5 value">

                        <span class="label label-<?=$model->licencia->estado == Salon::ESTADO_ACTIVADO ? 'success' : 'danger' ?>" style="margin-top: 5px;" >
                            <?=$model->licencia->estado; ?>
                        </span>
                    </div>
                </div>
                <div class="row static-info">
                    <div class="col-md-5 name">
                        Desde
                    </div>
                    <div class="col-md-5 value">
                        <?php echo $model->licencia->fecha_inicio;?>
                    </div>
                </div>
                <div class="row static-info">
                    <div class="col-md-5 name">
                        Hasta
                    </div>
                    <div class="col-md-5 value">
                        <?php echo $model->licencia->fecha_fin;?>
                    </div>
                </div>
                <div class="row static-info">
                    <div class="col-md-5 name">
                        Detalles
                    </div>
                    <div class="col-md-5 value">
                        <?php echo $model->licencia->detalles;?>
                    </div>
                </div>
            </div>
            </div>
    <?php endif;?>
    </div>
    <div class="col-md-6">
        <div class="portlet box green-haze">
            <div class="portlet-title">
                <div class="caption">
                    Propietario
                </div>
            </div>
            <div class="portlet-body">
                <div class="row static-info">
                    <div class="col-md-6 value">
                        <?php echo $model->usuario->first_name . ' ' . $model->usuario->last_name?>
                        <br/>
                        <?php echo $model->usuario->email;?>
                    </div>
                </div>

            </div>
    </div>
</div>

<?php if(isset($model->licencia)):?>
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box green-haze">
                <div class="portlet-title">
                    <div class="caption">
                        Transacciones
                    </div>
                </div>
                <div class="portlet-body">

                        <?= GridView::widget([
                            'dataProvider' => $ipnNotificacionesDp,
                            'columns' => [
                                [
                                    'attribute'=> 'txn_type',
                                    'value' => function ($model) {
                                        return \common\models\IpnNotification::getLabelTypeTxn($model->txn_type);
                                    },

                                ],
                                //'txn_type',
                                'subscr_id',
                                'txn_id',
                                'payment_type',
                                'payment_status',
                                'payer_email',
                                'receiver_email',
                                'mc_gross',
                                'mc_currency'

                            ],
                        ]); ?>

                </div>
            </div>
        </div>

    </div>
   <?php endif;?>

<?php if(!isset($model->licencia)): ?>
    <?php Modal::begin(['header' => 'Crear licencia','id' => 'modal_licencia']);?>
        <?php $form = ActiveForm::begin(['action'=>Url::toRoute('salon/add-licencia-admin'),'id'=>'form_licencia']); ?>
            <?php echo Html::activeHiddenInput($lic, 'usuario_id'); ?>
            <?php echo Html::hiddenInput('salonid', $model->id); ?>

            <div class="row" style="margin-top: 15px;">
                <div class="col-md-4">
                    <span>Tipo de licencia</span>
                </div>
                <div class="col-md-8">
                    <?php echo Html::activeDropDownList($lic, 'licencia_specid', \yii\helpers\ArrayHelper::map($lic_especs, 'id', 'nombre'), ['class' => 'form-control']);?>
                </div>
            </div>
            <div class="row" style="margin-top: 15px;">
                <div class="col-md-4">
                    <span>Estado</span>
                </div>
                <div class="col-md-8">
                    <?php echo Html::activeDropDownList($lic, 'estado',  Enum::listEstado(), ['class' => 'form-control']);?>
                </div>
            </div>
            <div class="row" style="margin-top: 15px;">
                <div class="col-md-4">
                    <span>Detalles</span>
                </div>
                <div class="col-md-8">
                    <?php echo Html::activeTextarea($lic, 'detalles', ['class' => 'form-control']);?>
                </div>
            </div>
            <div class="row" style="margin-top: 30px;">
                <div class="col-md-12">
                    <div class="clearfix">
                        <button type="reset" class="btn red" id="cancelar_reserva" onclick="$('#modal_licencia').modal('hide');">Cancelar</button>
                        <button type="submit" class="btn green" id="confirmar_reserva">Crear</button>
                    </div>
                </div>
            </div>

        <?php ActiveForm::end(); ?>
    <?php Modal::end();?>


     <div class="row">
         <div class="col-md-2">
            <button class="btn btn-success" onclick="$('#modal_licencia').modal('show');">Adicionar Licencia</button>
         </div>
     </div>

<?php endif;?>


<?php $this->registerJsFile('@web/themes/beauty/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js',
    ['depends' => 'yii\web\JqueryAsset']); ?>



<!--
<script type="text/javascript">
$(document).ready(function(){
    $.getScript("themes/beauty/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js");
});
</script>
<script type="text/javascript" src="themes/beauty/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js"></script>
-->