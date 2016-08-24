<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\LicenciaSpec */

$this->title = Yii::t('backend', 'License Type Update') . ': ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'License Type'), 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="row">
<div class="col-md-6 ">
    <!-- BEGIN SAMPLE FORM PORTLET-->
    <div class="portlet box blue">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-certificate"></i> Tipo de Licencia
            </div>
        </div>
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
    <!-- END SAMPLE FORM PORTLET-->

</div>
</div>