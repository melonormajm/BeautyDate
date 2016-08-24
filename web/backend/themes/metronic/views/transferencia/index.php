<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Transferencia */
/*
$this->title = 'Update Transferencia: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = 'Update';
*/
?>

<div class="row">

    <div class="col-md-12 ">
        <h3><?= Html::encode('Transferencia Bancaria') ?></h3><br>

        <b>Este módulo permite aceptar pagos por transferencia bancaria</b>
        <p>
            Si el cliente escoge este método de pago, su licencia cambiará su estado a "Esperando por pago",
            por lo tanto debe cambiar manualmente el estado de licencia en cuanto reciba la transferencia.
        </p>
        <br>
    </div>

</div>

<div class="row">
    <div class="col-md-6 ">

        <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i> Informaci&oacute;n de la Cuenta
                </div>
            </div>
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
        <!-- END SAMPLE FORM PORTLET-->

    </div>

</div>
