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
class MetronicAppAsset extends AssetBundle
{
    public $basePath = '@webroot/themes/metronic';
    public $baseUrl = '@web/themes/metronic';
public $css = [
        'assets/global/plugins/font-awesome/css/font-awesome.min.css',
        'assets/global/plugins/simple-line-icons/simple-line-icons.min.css',
        //'assets/global/plugins/bootstrap/css/bootstrap.min.css',
        'assets/global/plugins/uniform/css/uniform.default.css',
        'assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css',
        'assets/global/plugins/clockface/css/clockface.css',
        'assets/global/plugins/bootstrap-datepicker/css/datepicker3.css',
        'assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css',
        'assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css',
        'assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css',
        'assets/global/plugins/bootstrap-datetimepicker/css/datetimepicker.css',
        'assets/global/css/components.css',
        'assets/admin/pages/css/lock.css',
        'assets/global/css/plugins.css',
        'assets/admin/layout/css/layout.css',
        'assets/admin/layout/css/themes/default.css',
        'assets/admin/layout/css/custom.css',
        'assets/admin/pages/css/pricing-table.css',
        'assets/global/plugins/kartik-v-bootstrap-star-rating/css/star-rating.min.css',
        'assets/global/plugins/bootstrap-select/bootstrap-select.min.css',
        'assets/global/plugins/select2/select2.css',
        'assets/global/plugins/jquery-multi-select/css/multi-select.css',
        'assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css',
        //'assets/global/plugins/typeahead/typeahead.css',
        'css/jcrop/jquery.Jcrop.css',
        'css/jcrop/demos.css'

    ];
    public $js = [
        //'assets/global/plugins/jquery-1.11.0.min.js',
        'assets/global/plugins/jquery-migrate-1.2.1.min.js',
        'assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js',
        //'assets/global/plugins/bootstrap/js/bootstrap.min.js',
        'assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js',
        'assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js',
        'assets/global/plugins/jquery.blockui.min.js',
        'assets/global/plugins/jquery.cokie.min.js',
        'assets/global/plugins/uniform/jquery.uniform.min.js',
        'assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js',
        'assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
        'assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js',
        'assets/global/plugins/clockface/js/clockface.js',
        'assets/global/plugins/bootstrap-daterangepicker/moment.min.js',
        'assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js',
        'assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js',
        'assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',
        'assets/global/scripts/metronic.js',
        'assets/admin/layout/scripts/layout.js',
        'assets/admin/layout/scripts/quick-sidebar.js',
        'assets/admin/layout/scripts/demo.js',
        'assets/admin/pages/scripts/components-pickers.js',
        'assets/admin/pages/scripts/ui-general.js',
        'scripts/main.js',
        'scripts/reservaciones.js',
        'assets/global/plugins/kartik-v-bootstrap-star-rating/js/star-rating.js',
        'assets/global/plugins/bootstrap-select/bootstrap-select.min.js',
        'assets/global/plugins/select2/select2.min.js',
        'assets/global/plugins/jquery-multi-select/js/jquery.multi-select.js',
        'assets/admin/pages/scripts/components-dropdowns.js',
        'http://maps.google.com/maps/api/js?sensor=false',
        'assets/global/plugins/gmaps/gmaps.min.js',
        'assets/admin/pages/scripts/maps-google.js',
        'assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js',
        //'assets/global/plugins/typeahead/handlebars.min.js',
        //'assets/global/plugins/typeahead/typeahead.bundle.min.js',
        //'assets/admin/pages/scripts/components-form-tools.js'
        'scripts/jquery.Jcrop.min.js'


    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
