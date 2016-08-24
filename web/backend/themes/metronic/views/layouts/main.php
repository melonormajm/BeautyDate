<?php
use backend\themes\metronic\assets\MetronicAppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

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
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>

    <div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner">
    <!-- BEGIN LOGO -->
    <div class="page-logo">
        <a href="index.html">
            <img src="<?=Url::to('@web/images/'.'logo.png', true)?>" alt="logo" class="logo-default" style="margin: 3px 0 0 0;max-width: 197px;">
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
                <a href="extra_profile.html">
                    <i class="icon-user"></i> Mi Perfil </a>
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
        <div class="sidebar-toggler">
        </div>
        <!-- END SIDEBAR TOGGLER BUTTON -->
    </li>
        <li class="start <?= Yii::$app->controller->route == 'site/index' ? 'active' : ''?>"">
            <a href="<?=Url::toRoute('site/index')?>">
                <i class="icon-home"></i>
                <span class="title">Inicio</span>
            </a>
        </li>
        <li class="<?= Yii::$app->controller->route == 'licencia-spec/index' ? 'active' : ''?>">
            <a href="<?=Url::toRoute('licencia-spec/index')?>">
                <i class="fa fa-certificate"></i>
                <span class="title">Tipos de Licencia</span>
            </a>
        </li>
        <li class="<?= Yii::$app->controller->route == 'categoria/index' ? 'active' : ''?>"">
            <a href="<?=Url::toRoute('categoria/index')?>">
                <i class="fa fa-tags"></i>
                <span class="title">Categor&iacute;a</span>
            </a>
        </li>
        <li class="<?= Yii::$app->controller->route == 'salon/index' ? 'active' : ''?>"">
            <a href="<?=Url::toRoute('salon/index')?>">
                <i class="fa fa-cog"></i>
                <span class="title">Salones</span>
            </a>
        </li>
        <!--
        <li class="<?= Yii::$app->controller->route == 'transferencia/index' ? 'active' : ''?>">
            <a href="<?=Url::toRoute('transferencia/index')?>">
                <i class="fa fa-bank"></i>
                <span class="title">Métodos de Pago</span>
            </a>
        </li>
        -->
        <li class="<?= Yii::$app->controller->route == 'licencia/index' ? 'active' : ''?>"">
            <a href="<?=Url::toRoute('licencia/index')?>">
                <i class="fa fa-certificate"></i>
                <span class="title">Solicitudes de Licencia</span>
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
            <?= date('Y') ?> &copy; Salones de belleza.
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
