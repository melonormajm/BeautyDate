<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
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
?>
<style>
    .modal-dialog{
        z-index: 10500;
    }
</style>

<h3 class="page-title">
    Licencia<small></small>
</h3>

<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <?= Html::a('Inicio',  Url::toRoute('site/index')) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            Detalles
        </li>
    </ul>
</div>

<div class="note <?= $licencia_vencida ? 'note-danger' : 'note-success'?>">
    <h4 class="block">Nombre de la Licencia: <?=$licencia->licenciaSpec->nombre?></h4>
    <p>
       Descripción: <?=$licencia->licenciaSpec->descripcion?>
    </p>
    <p>
        Fecha Comprada: <?=Yii::$app->formatter->asDate($licencia->fecha_inicio)?>
    </p>
    <p>
        Fecha Vencimiento: <?=Yii::$app->formatter->asDate($licencia->fecha_fin)?>
    </p>
    <p>
        Estado: <?=$licencia_vencida ? 'Inactiva' : 'Activa'?>
    </p>
    <?php if(isset($licencia->detalles)):?>
        <p>
            Detalles: <?=$licencia->detalles?>
        </p>
    <?php endif;?>
    <?php if($licencia_vencida):?>
        <a href="<?=Url::toRoute('licenciaspec/nuevalicencia')?>" class="btn btn-success"><i class="fa fa-plus"></i> Adquirir Licencia</a>
    <?php endif;?>

</div>
    <div class="portlet box green-haze">
        <div class="portlet-title">
            <div class="caption">
                Transacciones
            </div>
        </div>
        <div class="portlet-body">
            <?php

                echo GridView::widget([
                    'dataProvider' => $ipnNotificacionesDp,
                    'summary' => 'Mostrando {begin} a {end} de {count} resultados',
                    //'filterModel' => $searchModel,
                    'columns' => [
                        'txn_type', 'subscr_id', 'txn_id', 'payment_type','payment_status','receiver_email', 'payer_email','mc_gross', 'mc_currency'
                    ],
                ]);

            ?>
        </div>
    </div>
