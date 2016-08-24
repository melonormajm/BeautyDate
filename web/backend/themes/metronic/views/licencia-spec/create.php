<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\LicenciaSpec */

$this->title = Yii::t('backend', 'Create License');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'License Type'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
<div class="col-md-6 ">
    <!-- BEGIN SAMPLE FORM PORTLET-->
    <div class="portlet box blue">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-certificate"></i> <?=Yii::t('backend', 'License Type') ?>
            </div>
        </div>
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
    <!-- END SAMPLE FORM PORTLET-->

</div>
</div>
