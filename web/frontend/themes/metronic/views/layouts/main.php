<?php
use frontend\themes\metronic\assets\MetronicAppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\bootstrap\Modal;

/* @var $this \yii\web\View */
/* @var $content string */

MetronicAppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title>BeautyDate</title>
    <?php $this->head() ?>

</head>
<body>
    <?php $this->beginBody() ?>

    <div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner">
    <!-- BEGIN LOGO -->
    <div class="page-logo">
        <a href="<?=Url::toRoute('site/index')?>">
            <img src="<?=Url::to('@web/images/logo_backend.png', true)?>" alt="logo" class="logo-default" style="margin: 4px 0 0 0;
  max-width: 195px;">
        </a>
        <div class="menu-toggler sidebar-toggler hide">
            <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
        </div>
    </div>
    <!-- END LOGO -->
    <!-- BEGIN RESPONSIVE MENU TOGGLER -->
    <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
    </a>
    <!-- END RESPONSIVE MENU TOGGLER -->
    <!-- BEGIN TOP NAVIGATION MENU -->
    <div class="top-menu">
    <ul class="nav navbar-nav pull-right">

    <li>
        <div class="" style="background: none;  top: 15px; right: 0px; position: absolute;">
            <?php if(Yii::$app->user->getIdentity()->estado):?>
                <?php if(Yii::$app->user->getIdentity()->estado == \common\models\Salon::ESTADO_SISTEMA_ACTIVADO):?>
                    <a href="<?=Url::toRoute('salon/configuraciones')?>">
                        <span class="label label-success"><span class="fa fa-refresh"></span>Estado Activo</span>
                    </a>
                <?php endif;?>
                <?php if(Yii::$app->user->getIdentity()->estado == \common\models\Salon::ESTADO_SISTEMA_INACTIVO):?>
                    <a href="<?=Url::toRoute('salon/configuraciones')?>">
                        <span class="label label-danger"><span class="fa fa-refresh"></span>Estado Inactivo</span>
                    </a>
                <?php endif;?>
            <?php endif;?>
        </div>
    </li>

    <!--<li>
        <div class="" style="background: none;  top: 15px; right: 0px; position: absolute;">
            <?php if(Yii::$app->user->getIdentity()->licencia):?>
                <a href="<?php Url::toRoute('licenciaspec/especificaciones')?>">
                    <span class="label label-danger">No existe Licencia activa.</span>
                </a>
            <?php endif;?>
        </div>
    </li>-->
    <!-- BEGIN USER LOGIN DROPDOWN -->
    <li class="dropdown dropdown-user">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
            <!--<img alt="" class="img-circle hide1" src="">-->
					<span class="username username-hide-on-mobile">
					Hola <?=Yii::$app->user->getIdentity()->username?> </span>
            <i class="fa fa-angle-down"></i>
        </a>
        <ul class="dropdown-menu">
            <li>
                <a href="<?=Url::toRoute('salon/perfil')?>">
                    <i class="icon-user"></i> Mi Perfil </a>
            </li>
            <li>
                <a href="<?=Url::toRoute('salon/general')?>">
                    <i class="fa fa-star"></i> Mi Salón </a>
            </li>
            <li>
                <a href="<?=Url::toRoute('reservacion/index')?>">
                    <i class="icon-calendar"></i> Reservaciones </a>
            </li>
            <li class="divider">
            </li>
            <li>
                <a href="<?=Url::toRoute('site/logout')?>" data-method="post">
                    <i class="icon-key"></i> Salir </a>
            </li>
        </ul>
    </li>
    <!-- END USER LOGIN DROPDOWN -->
    </ul>
    </div>
    <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END HEADER INNER -->
    </div>

    <div class="wrap" style="margin-top: 45px;">

    <!-- END HEADER -->


        <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar-wrapper">
    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
    <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
    <div class="page-sidebar navbar-collapse collapse">
    <!-- BEGIN SIDEBAR MENU -->
    <ul class="page-sidebar-menu" data-auto-scroll="true" data-slide-speed="200">
    <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
    <li class="sidebar-toggler-wrapper">
        <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
        <div class="sidebar-toggler" style="margin-bottom: 15px;">
        </div>
        <!-- END SIDEBAR TOGGLER BUTTON -->
    </li>
        <li class="start <?= Yii::$app->controller->route == 'site/index' ? 'active' : ''?>">
            <a href="<?=Url::toRoute('site/index')?>">
                <i class="icon-home"></i>
                <span class="title">Inicio</span>
            </a>
        </li>
        <!--<li id="salonMenu">
            <a href="<?php //Url::toRoute('salon/preview')?>">
                <i class="icon-settings"></i>
                <span class="title">Salón</span>
            </a>
        </li>-->
        <li>
            <a href="javascript:;">
                <i class="icon-settings"></i>
                <span class="title">Mi Salón</span>
                <span class="arrow "></span>
            </a>
            <ul class="sub-menu">
                <li>
                    <a href="<?=Url::toRoute('salon/general')?>">
                        Información General</a>
                </li>
                <!--<li>
                    <a href="<?=Url::toRoute('categoriasalon/categorysalon')?>">
                        Categorías</a>
                </li>-->
                <li>
                    <a href="<?=Url::toRoute('servicio/services')?>">
                        Servicios</a>
                </li>
                <li>
                    <a href="<?=Url::toRoute('sillon/index')?>">
                        Sillones</a>
                </li>
                <li>
                    <a href="<?=Url::toRoute('imagenes/list')?>">
                        Imágenes</a>
                </li>
                <li>
                    <a href="<?=Url::toRoute('cliente/index')?>">
                        Clientes</a>
                </li>
                <li>
                    <a href="<?=Url::toRoute('reservacion/resumen')?>">
                        Resumen Reservaciones</a>
                </li>
            </ul>
        </li>
        <li class="<?= Yii::$app->controller->route == 'reservacion/index' ? 'active' : ''?>">
            <a href="<?=Url::toRoute('reservacion/index')?>">
                <i class="fa fa-calendar"></i>
                <span class="title">Reservaciones</span>
            </a>
        </li>
        <li class="<?= Yii::$app->controller->route == 'licenciaspec/especificaciones' ? 'active' : ''?>">
            <a href="<?=Url::toRoute('licenciaspec/especificaciones')?>">
                <i class="fa fa-certificate"></i>
                <span class="title">Licencia</span>
            </a>
        </li>
        <li class="<?= Yii::$app->controller->route == 'salon/evaluacion' ? 'active' : ''?>">
            <a href="<?=Url::toRoute('salon/evaluacion')?>">
                <i class="icon-star"></i>
                <span class="title">Evaluaciones</span>
            </a>
        </li>
        <li class="<?= Yii::$app->controller->route == 'promocion/index' ? 'active' : ''?>">
            <a href="<?=Url::toRoute('promocion/index')?>">
                <i class="fa fa-gift"></i>
                <span class="title">Promociones</span>
            </a>
        </li>
        <li class="<?= Yii::$app->controller->route == 'salon/perfil' ? 'active' : ''?>">
            <a href="<?=Url::toRoute('salon/perfil')?>">
                <i class="icon-user"></i>
                <span class="title">Perfil de usuario</span>
            </a>
        </li>


    </ul>
    <!-- END SIDEBAR MENU -->
    </div>
    </div>


        <!-- END SIDEBAR -->

        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <div class="page-content">
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>

                <?= $content ?>
            </div>
        </div>

    </div>

    <!-- BEGIN FOOTER -->
    <div class="page-footer">
        <div class="page-footer-inner">
            <div class="">
                <?= date('Y') ?> &copy; BeautyDate.
            </div>
        </div>
        <div class="" style="float: right;color: #a3a3a3;">
            Desarrollado por
            <span><img src="<?=Url::to('@web/images/softok_blanco.png', true)?>" alt="logo" class="logo-default" ></span>
        </div>
        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
    </div>

    <?php $this->endBody() ?>
    <!-- END PAGE LEVEL SCRIPTS -->

    <script>
        jQuery(document).ready(function() {
            Metronic.init(); // init metronic core components
            Layout.init(); // init current layout
            //QuickSidebar.init(); // init quick sidebar
            Demo.init(); // init demo features
            ComponentsPickers.init();
            ComponentsDropdowns.init();
            //ComponentsFormTools.init();
        });
    </script>
    <script>
        /*jQuery(document).ready(function() {
            $("#salonMenu").addClass("active");
            $("#salonMenu a").append('<span class="selected"></span>');
        });*/
    </script>
    <!-- END JAVASCRIPTS -->
</body>
</html>
<?php $this->endPage() ?>
