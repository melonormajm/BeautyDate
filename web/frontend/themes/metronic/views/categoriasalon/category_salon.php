<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;


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
    Categorías<small></small>
</h3>

<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <?= Html::a('Inicio',  Url::toRoute('site/home')) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            Categorías del salón
        </li>

    </ul>
</div>

<div>
    <p>
        Seleccione las categorías que brinda su salón. Los clientes potenciales buscan los salones por categoría en la aplicación móvil.
    </p>
</div>


<div id="message_ajax"><?=$message?></div>

<?php $form = ActiveForm::begin(); ?>
<div class="row">
<div class="salon-category">

    <div class="form-group">
        <div class="col-md-12">

            <?php if($categorias != null):?>
                <?php foreach($categorias as $cat):?>
                    <?php $url = str_replace("beautydate", "beautydate.backend", Url::to('@web/images/categoria/' . $cat->id . '.' . $cat->thumbnail, true)); ?>
                    <div class="col-md-2" style="margin-bottom: 15px;">
                        <input type="checkbox" name="categorias_list[]" value="<?= $cat->id?>" /><?= $cat->nombre?><br />
                        <img src="<?php echo $url; ?>" width="100" height="100" alt="">
                    </div>

                <?php endforeach;?>
            <?php endif?>
            <?php if($selected != null):?>
                <?php foreach($selected as $cat):?>
                    <?php $url = str_replace("beautydate", "beautydate.backend", Url::to('@web/images/categoria/' . $cat->id . '.' . $cat->thumbnail, true)); ?>
                    <div class="col-md-2" style="margin-bottom: 15px;">
                        <input type="checkbox" name="categorias_list[]" value="<?= $cat->id?>" checked/><?= $cat->nombre?><br />
                        <img src="<?php echo $url; ?>" width="100" height="100" alt="">
                    </div>

                <?php endforeach;?>
            <?php endif?>
        </div>
    </div>

    <div class="form-group" style="margin-bottom: 25px;">
        <div class="col-md-8">
            <div class="form-group">

            </div>
        </div>
    </div>



</div>
</div>

<div class="row" style="margin-top: 20px;">
    <div class="col-md-4">
        <a href="<?=Url::toRoute('salon/general')?>" class="btn btn-success"> Regresar a General</a>
        <?= Html::submitButton('Guardar',['class' => 'btn btn-primary']) ?>
    </div>
    <div class="col-md-4 col-md-offset-4">
        <a href="<?=Url::toRoute('servicio/services')?>" class="btn default" style="float: right;"> Ir a Servicios</a>
    </div>
</div>
<?php ActiveForm::end(); ?>
