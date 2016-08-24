<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\themes\metronic\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class MetronicLoginAppAsset extends AssetBundle
{
    public $basePath = '@webroot/themes/metronic';
    public $baseUrl = '@web/themes/metronic';
public $css = [
        'assets/global/plugins/fancybox/source/jquery.fancybox.css',
        'assets/global/plugins/carousel-owl-carousel/owl-carousel/owl.carousel.css',
        'assets/global/plugins/slider-revolution-slider/rs-plugin/css/settings.css',
        'assets/global/plugins/bootstrap/css/bootstrap.min.css',
        'assets/global/css/components.css',
        'assets/frontend/layout/css/style.css',
        'assets/frontend/pages/css/style-revolution-slider.css',
        'assets/frontend/layout/css/style-responsive.css',
        'assets/frontend/layout/css/themes/blue.css',
        'assets/frontend/layout/css/custom.css',
    ];
    public $js = [
        //'assets/global/plugins/jquery-1.11.0.min.js',
        'assets/global/plugins/jquery-migrate-1.2.1.min.js',
        'assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js',
        //'assets/global/plugins/bootstrap/js/bootstrap.min.js',
        'assets/frontend/layout/scripts/back-to-top.js',
        'assets/global/plugins/fancybox/source/jquery.fancybox.pack.js',
        'assets/global/plugins/carousel-owl-carousel/owl-carousel/owl.carousel.min.js',
        'assets/global/plugins/slider-revolution-slider/rs-plugin/js/jquery.themepunch.plugins.min.js',
        'assets/global/plugins/slider-revolution-slider/rs-plugin/js/jquery.themepunch.revolution.min.js',
        'assets/global/plugins/slider-revolution-slider/rs-plugin/js/jquery.themepunch.tools.min.js',
        'assets/frontend/pages/scripts/revo-slider-init.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
