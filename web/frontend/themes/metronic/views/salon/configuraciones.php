<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
$this->title = 'Beauty Date';
?>


<h3 class="page-title">
    Configuraciones<small></small>
</h3>

<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <?= Html::a('Inicio',  Url::toRoute('site/index')) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            Configuración del salón
        </li>

    </ul>
</div>

<h4 class="block">Pasos para configurar su salón</h4>
<p><span class="label label-sm label-success"> Completado </span>
<span class="label label-sm label-warning"> Información </span>
<span class="label label-sm label-danger"> Incompleto </span></p>
<div class="list-group">
    <a href="<?=Url::toRoute('salon/general')?>" class="list-group-item">


        <h4 class="list-group-item-heading"><span class="badge badge-success">1 </span> Configuración del salón</h4>
        <p class="list-group-item-text">
            Agregar información básica del salón, nombre, horario de servicio, su ubicación física y una breve descripción opcional.
        </p>
    </a>
    <a href="<?=Url::toRoute('servicio/services')?>" class="list-group-item">
        <h4 class="list-group-item-heading"><span class="badge badge-success">3 </span> Servicios</h4>
        <span class="label label-sm label-warning">Cantidad <?=$cantidad_servicios?></span>
        <?php if($servicios):?>
            <span class="label label-sm label-success">Existen servicios activos</span>
        <?php endif;?>
        <?php if(!$servicios):?>
            <span class="label label-sm label-danger">No existen servicios activos</span>
        <?php endif;?>

        <p class="list-group-item-text">
            Servicios que se brindan en su salón. Los servicios se asocian a sillones del salón. Los usuarios reservan en un sillón para un servicio determinado.
        </p>
    </a>
    <a href="<?=Url::toRoute('imagenes/list')?>" class="list-group-item">
        <h4 class="list-group-item-heading"><span class="badge badge-success">4 </span> Imágenes</h4>
        <p class="list-group-item-text">
            Imágenes de su salón que le permiten al usuario conocer un poco más de su local antes de visitarlo.
        </p>
    </a>
    <a href="<?=Url::toRoute('sillon/index')?>" class="list-group-item">
        <h4 class="list-group-item-heading"><span class="badge badge-success">5 </span> Sillones</h4>
        <span class="label label-sm label-warning">Cantidad <?=$cantidad_sillones?></span>
        <?php if($sillones_servicios):?>
            <span class="label label-sm label-success">Existen sillones con servicios asignados</span>
        <?php endif;?>
        <?php if(!$sillones_servicios):?>
            <span class="label label-sm label-danger">No existen sillones con servicios asignados</span>
        <?php endif;?>
        <p class="list-group-item-text">
            Los sillones de su salón, estos brindan determinados servicios de los que se crearon arriba.
        </p>
    </a>
    <a href="<?=Url::toRoute('licenciaspec/especificaciones')?>" class="list-group-item">
        <h4 class="list-group-item-heading"><span class="badge badge-success">6 </span> Licencia</h4>
        <?php if($licencia and $licencia->estado == \common\models\Licencia::ESTADO_ACTIVO):?>
            <span class="label label-sm label-success">El salón posee una licencia, y se encuentra activa. </span>
        <?php endif;?>

        <?php if($licencia and $licencia->estado == \common\models\Licencia::ESTADO_PROCESANDO):?>
            <span class="label label-sm label-danger">La licencia se encuentra pendiente de la confirmaci&oacute;n de pago.</span>
        <?php endif;?>
        <?php if(!$licencia):?>
            <span class="label label-sm label-danger">El salón no tiene licencia</span>
        <?php endif;?>
        <p class="list-group-item-text">
            Su salón requiere una licencia para poder ser visualizado desde la aplicación móvil.
        </p>
    </a>
</div>