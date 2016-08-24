<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
$this->title = 'Beauty Date';
?>


<h3 class="page-title">
    Administración <small>reservaciones &amp; estadísticas</small>
</h3>


<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            Inicio
        </li>
    </ul>
</div>

<div class="row">

    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat blue-madison">
            <div class="visual">
                <i class="fa fa-comments"></i>
            </div>
            <div class="details">
                <?php if(isset($ganancias)&& $ganancias != 0):?>
                    <div class="number">
                        <?=$ganancias?>$
                    </div>
                    <div class="desc">
                        Ingresos recibidos
                    </div>
                <?php endif?>
                <?php if(!isset($ganancias)|| $ganancias == 0):?>
                    <div class="number">

                    </div>
                    <div class="desc" style="margin-top: 36px;">
                        No hay ingresos
                    </div>
                <?php endif?>

            </div>
            <a class="more" href="<?=Url::toRoute('reservacion/index')?>#">
                Ver más <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat red-intense">
            <div class="visual">
                <i class="fa fa-bar-chart-o"></i>
            </div>
            <div class="details">
                <?php if(isset($ganancias_futuras)&& $ganancias_futuras != 0):?>
                    <div class="number">
                        <?=$ganancias_futuras?>$
                    </div>
                    <div class="desc">
                        Ingresos programados
                    </div>
                <?php endif?>
                <?php if(!isset($ganancias_futuras)|| $ganancias_futuras == 0):?>
                    <div class="number">

                    </div>
                    <div class="desc" style="margin-top: 36px;">
                        No hay ingresos programados
                    </div>
                <?php endif?>
            </div>
            <a class="more" href="<?=Url::toRoute('reservacion/index')?>">
                Ver más <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat green-haze">
            <div class="visual">
                <i class="fa fa-shopping-cart"></i>
            </div>
            <div class="details">
                <div class="number">
                    <?=$new_reserv?>
                </div>
                <div class="desc">
                    Nuevas Reservaciones
                </div>
            </div>
            <a class="more" href="<?=Url::toRoute('reservacion/index')?>">
                Ver más <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat purple-plum">
            <div class="visual">
                <i class="fa fa-globe"></i>
            </div>
            <div class="details">
                <?php if(isset($evaluacion)&& $evaluacion != 0):?>
                    <div class="number">
                        <?=$evaluacion?>/5
                    </div>
                    <div class="desc">
                        Evaluación
                    </div>
                <?php endif?>
                <?php if(!isset($evaluacion)|| $evaluacion == 0):?>
                    <div class="number">

                    </div>
                    <div class="desc" style="margin-top: 36px;">
                        No hay evaluaciones
                    </div>
                <?php endif?>
            </div>
            <a class="more" href="<?=Url::toRoute('salon/evaluacion')?>">
                Ver más <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
</div>

