<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Licencia */

$this->title = 'Create Licencia';
$this->params['breadcrumbs'][] = ['label' => 'Licencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-6 ">
        <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-certificate"></i> Licencia
                </div>
            </div>
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
        <!-- END SAMPLE FORM PORTLET-->

    </div>
</div>
