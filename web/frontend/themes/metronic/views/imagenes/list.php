<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
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



   /* #target {
        background-color: #ccc;
        width: 500px;
        height: 330px;
        font-size: 24px;
        display: block;
    }*/
</style>


<h3 class="page-title">
    Imágenes<small></small>
</h3>

<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <?= Html::a('Inicio',  Url::toRoute('site/home')) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            Imágenes del salón
        </li>
    </ul>
</div>

<div class="note note-success">
    <h4 class="block">Nota!</h4>
    <p>
        Las imágenes servirán para permitir que el cliente conozca más de su salón.
        Los salones con imágenes reciben un 70% más de reservaciones.
    </p>
    <p>
        La imagen que se cataloga como principal es la imagen que saldr&aacute; como THUMBNAIL en los listados de los salones. Para ello se utiliza la funcionalidad RECORTAR
        que permite tomar la porci&oacute;n de la imagen que se quiere mostrar. Los formatos permitodos son: <span class="bold">jpg</span>, <span class="bold">jpeg</span>, <span class="bold">gif</span> y <span class="bold">png</span>
    </p>
</div>


<?php if($model == null):?>
    <div class="alert alert-info">
        <strong>Info!</strong> Antes de agregar imágenes debe configurar su salón.
        <a href="<?=Url::toRoute('salon/general')?>" class="btn purple" style="  float: right; margin-top: -10px;"> Ir a configurar salón</a>
    </div>
<?php endif;?>

<?php if($model != null):?>


<div id="message_ajax"><?php if(isset($message)) echo $message?></div>
<p>
    <a href="#" id="agregar_imagen" class="btn btn-success"><i class="fa fa-plus"></i> Agregar Imagen</a>
</p>


<div class="row" style="margin-bottom: 40px;">
<?php foreach(\common\models\Imagenes::find()->where(['salonid'=>$model->id])->all() as $img):?>
<div class="col-md-12" style="background-color: #f7f7f7; padding: 5px;margin: 0px;border-top: 1px solid rgb(218, 211, 211)">
    <div class="col-md-4">
       <?php if(\common\helper\SalonHelper::existImageThumbFromArr($img)): ?>
            <a href="<?php echo \common\helper\SalonHelper::getImgUrlFromArray($img); ?>" target="_blank"><?=Html::img(\common\helper\SalonHelper::getImgUrlFromArray($img, true), ['width'=>'100','height'=>'100'])?></a>
        <?php else: ?>
            <a href="<?php echo \common\helper\SalonHelper::getImgUrlFromArray($img); ?>" target="_blank"><?=Html::img(\common\helper\SalonHelper::getImgUrlFromArray($img, false), ['width'=>'100','height'=>'100'])?></a>
        <?php endif; ?>
        <?php if($img->principal):?>
            <span class="label label-info" style="margin-right: 10px;" title="imagen principal o de portada">Imagen Principal</span>
        <?php endif?>
    </div>

    <div class="col-md-4">

    </div>
    <div class="col-md-4">
        <?php if(!$img->principal):?>
        <a href="<?=Url::toRoute(['imagenes/setprincipal','id'=>$img->id])?>" class="btn green delete btn-sm" title="Establecer como imagen principal">
            <i class="fa fa-asterisk"></i>
            <span> Principal</span>
        </a>
        <?php endif;?>
        <a class="btn purple btn-sm" href="javascript: showModelCrop('<?php echo \common\helper\SalonHelper::getImgUrlFromArray($img);?>',<?php echo $img->id ; ?>)">
            <i class="fa fa-cut"></i>
            <span>Recortar</span>
        </a>

        <a href="<?=Url::toRoute(['imagenes/delete','id'=>$img->id])?>" class="btn red delete btn-sm" title="Eliminar imagen">
            <i class="fa fa-trash-o"></i>
            <span> Eliminar</span>
        </a>
    </div>
</div>
<?php endforeach;?>
</div>

<?php endif;?>

<div class="row">
    <div class="col-md-4">
        <a href="<?=Url::toRoute('sillon/index')?>" class="btn btn-success"> Regresar a Sillones</a>
    </div>
    <div class="col-md-4 col-md-offset-4">
        <a href="<?=Url::toRoute('salon/general')?>" class="btn default" style="float: right;"> Ir a Salón</a>
    </div>
</div>

<?php Modal::begin(['header' => 'Agregar Imagen','id' => 'modal_imagen']);?>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="row">
        <div class="col-md-5">
            <?= $form->field($imagen, 'nombre')->textInput(['maxlength' => 50])->label('Nombre o identificativo:') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <label class="control-label" for="imagenes-nombre">Imagen:</label>
            <div class="fileinput fileinput-new" data-provides="fileinput" style="margin-top: 8px;">
                <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                </div>
                <div>
            <span class="btn default btn-file">
            <span class="fileinput-new">
            Seleccionar Imagen </span>
            <span class="fileinput-exists">
            Cambiar </span>
            <input type="file" id="imagenes-imgfile" name="Imagenes[imgfile]">
            </span>
                    <a href="#" class="btn red fileinput-exists" data-dismiss="fileinput"> Eliminar </a>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group" style="margin-bottom: 20px;margin-top: 20px;">
        <button type="submit" class="btn blue start">
            <i class="fa fa-upload"></i><span> Subir </span>
        </button>
        <?= Html::button('Cancelar',['class' => 'btn default','onclick'=>'$("#modal_imagen").modal("hide")']) ?>
        <?php //Html::submitButton($imagen->isNewRecord ? 'Agregar' : 'Modificar', ['class' => $imagen->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>
<?php Modal::end();?>

<?php Modal::begin(['header' => 'Crear thumbnail','id' => 'modal_crop']);?>

<div style="width:500px;" id="crop_cnt">


</div>
<div class="form-group" style="margin-bottom: 20px;margin-top: 20px;">
    <button class="btn blue start" onclick="uploadCropImg();">
        <i class="fa fa-cut"></i><span> Recortar </span>
    </button>
    <?= Html::button('Cancelar',['class' => 'btn default','onclick'=>'$("#modal_crop").modal("hide")']) ?>
    <?php //Html::submitButton($imagen->isNewRecord ? 'Agregar' : 'Modificar', ['class' => $imagen->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>
<?php Modal::end(); ?>

<script type="application/javascript">

    var OBJ_IMG_CROP = {};

    function showModelCrop(url, imgid){

        //$("#crop_cnt").html('<img id="cropbox" src="' + url + '" >');
        $.getJSON('<?php echo Url::to(['imagenes/ctmpthumb']);?>',
            {
                'imgid' : imgid

            },
            function(response){
                $("#crop_cnt").html('<img id="cropbox" src="' + response.url + '" >');
                $('#cropbox').Jcrop({
                    aspectRatio: 1,
                    onSelect: updateCoords,
                    minSize: [150, 150],
                    maxSize: [250, 250]
                });

                OBJ_IMG_CROP.url    = response.url;
                OBJ_IMG_CROP.imgid  = response.imgid;
                OBJ_IMG_CROP.path   = response.path;
                OBJ_IMG_CROP.nombre = response.nombre;
                OBJ_IMG_CROP.salonid= response.salonid;
                $("#modal_crop").modal("show");

            }


        )


    }

    function uploadCropImg(){
        $.post('<?php echo Url::to(['imagenes/cropimg']);?>',
            OBJ_IMG_CROP,
            function(response){
                window.location.href = window.location.href;
            }

        )
    }


    function updateCoords(c)
    {
        OBJ_IMG_CROP.x = c.x;
        OBJ_IMG_CROP.y = c.y;
        OBJ_IMG_CROP.h = c.h;
        OBJ_IMG_CROP.w = c.w;

        console.log(c.x);
        console.log(c.y);
        console.log(c.w);
        console.log(c.h);
    };

    function checkCoords()
    {
        if (parseInt($('#w').val())) return true;
        alert('Please select a crop region then press submit.');
        return false;
    };

</script>

</script>