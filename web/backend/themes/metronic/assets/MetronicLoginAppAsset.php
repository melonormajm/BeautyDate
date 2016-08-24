<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\themes\metronic\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class MetronicAppAsset extends AssetBundle
{
    public $basePath = '@webroot/themes/beauty';
    public $baseUrl = '@web/themes/beauty';
public $css = [
        'global/plugins/font-awesome/css/font-awesome.min.css',
        'global/plugins/simple-line-icons/simple-line-icons.min.css',
        //'global/plugins/bootstrap/css/bootstrap.min.css',
        'global/plugins/uniform/css/uniform.default.css',
        'global/plugins/bootstrap-switch/css/bootstrap-switch.min.css',
        //'global/plugins/clockface/css/clockface.css',
        'global/plugins/select2/select2.css',
        'admin/pages/css/login.css',
        //'global/plugins/bootstrap-datepicker/css/datepicker3.css',
        //'global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css',
        //'global/plugins/bootstrap-colorpicker/css/colorpicker.css',
        //'global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css',
        //'global/plugins/bootstrap-datetimepicker/css/datetimepicker.css',
        'global/css/components.css',
        //'admin/pages/css/lock.css',
        'global/css/plugins.css',
        'admin/layout/css/layout.css',
        'admin/layout/css/themes/default.css',
        'admin/layout/css/custom.css',
    ];
    public $js = [
        'global/plugins/respond.min.js',
        'global/plugins/excanvas.min.js',
        //'global/plugins/jquery-1.11.0.min.js',
        'global/plugins/jquery-migrate-1.2.1.min.js',
        'global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js',
        //'global/plugins/bootstrap/js/bootstrap.min.js',
        'global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js',
        'global/plugins/jquery-slimscroll/jquery.slimscroll.min.js',
        'global/plugins/jquery.blockui.min.js',
        'global/plugins/jquery.cokie.min.js',
        'global/plugins/uniform/jquery.uniform.min.js',
        'global/plugins/bootstrap-switch/js/bootstrap-switch.min.js',
        'global/plugins/jquery-validation/js/jquery.validate.min.js',
        'global/plugins/select2/select2.min.js',
        //'global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
        //'global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js',
        //'global/plugins/clockface/js/clockface.js',
        //'global/plugins/bootstrap-daterangepicker/moment.min.js',
        //'global/plugins/bootstrap-daterangepicker/daterangepicker.js',
        //'global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js',
        //'global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',
        'global/scripts/metronic.js',
        'admin/layout/scripts/layout.js',
        'admin/layout/scripts/quick-sidebar.js',
        'admin/layout/scripts/demo.js',
        'admin/pages/scripts/login.js',
        //'admin/pages/scripts/components-pickers.js',
        //'scripts/main.js',

    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
