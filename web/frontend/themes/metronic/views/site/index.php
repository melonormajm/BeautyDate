<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
$this->title = 'Beauty App';
?>

<?php if(!Yii::$app->user->isGuest):?>
<h3 class="page-title">
    Administración <small>reservaciones &amp; estadísticas</small>
</h3>
<?php endif;?>

<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            Inicio
        </li>
    </ul>
</div>

<?php if(Yii::$app->user->isGuest):?>
    <div class="alert alert-info">
        <strong>Info!</strong> Por favor autentiquese para ver su información de salón..
    </div>
<?php endif;?>

<?php if(!Yii::$app->user->isGuest):?>
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat blue-madison">
            <div class="visual">
                <i class="fa fa-comments"></i>
            </div>
            <div class="details">
                <div class="number">
                    0
                </div>
                <div class="desc">
                    Nuevos Comentarios
                </div>
            </div>
            <a class="more" href="#">
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
                <div class="number">
                    0$
                </div>
                <div class="desc">
                    Ganancias
                </div>
            </div>
            <a class="more" href="#">
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
                    0
                </div>
                <div class="desc">
                    Nuevas reservaciones
                </div>
            </div>
            <a class="more" href="#">
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
                <div class="number">
                    0%
                </div>
                <div class="desc">
                    Popularidad
                </div>
            </div>
            <a class="more" href="#">
                Ver más <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
</div>
<?php endif;?>