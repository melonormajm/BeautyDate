<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
$this->title = 'Beauty Date';
?>

<h3 class="page-title">
    Información Bancaria<small> pago por transferencia</small>
</h3>


<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <?= Html::a('Inicio',  Url::toRoute('site/index')) ?>
        </li>
        <li>
            <?= Html::a('Inicio',  Url::toRoute('licenciaspec/especificaciones')) ?>
        </li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <h3>Ha seleccionado la licencia <?=$licencia->nombre?>, use la información listada a continuación para realizar el pago.</h3>
    </div>
    <div class="col-md-12">
        <div class="note note-info">
            <h4 class="block">Información de transferencia</h4>
            <h4>
                Nombre del propietario: <?=$transferencia->propietario?><br />
                Detalles: <?=$transferencia->detalles?><br />
                Dirección: <?=$transferencia->direccion?><br />
            </h4>
        </div><br />


        <a href="<?=Url::toRoute(['licenciaspec/comprarlicencia','id'=>$licencia->id])?>" class="btn yellow-crusta">
            Confirmar <i class="m-icon-swapright m-icon-white"></i>
        </a>
    </div>
</div>