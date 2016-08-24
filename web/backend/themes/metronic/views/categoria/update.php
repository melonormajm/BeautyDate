<link rel="stylesheet" type="text/css" href="themes/beauty/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css"/>
<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Categoria */

$this->title = Yii::t('backend', 'Update Category') . ': ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="row">
<div class="col-md-6 ">
    <!-- BEGIN SAMPLE FORM PORTLET-->
    <div class="portlet box blue">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-tags"></i> <?=Yii::t('backend', 'Category') ?>
            </div>
        </div>
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
    <!-- END SAMPLE FORM PORTLET-->

</div>
</div>

<?php $this->registerJsFile('@web/themes/beauty/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js',
    ['depends' => 'yii\web\JqueryAsset']); ?>
