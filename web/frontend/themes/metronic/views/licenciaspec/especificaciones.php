<?php
use yii\helpers\Html;
use yii\helpers\Url;
use \common\models\Moneda;

/* @var $this yii\web\View */
$this->title = 'Beauty Date';
?>
<style>
    .paypal-button {
        border-radius: 21px !important;
    }
</style>

<h3 class="page-title">
    Licencia <small>disponibles</small>
</h3>


<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <?= Html::a('Inicio',  Url::toRoute('site/index')) ?>
        </li>
    </ul>
</div>


<div class="portlet">
    <div class="portlet-body">
        <div class="row margin-bottom-40">
            <!-- Pricing -->
            <?php foreach($model as $license):?>
            <div class="col-md-3">
                <div class="pricing hover-effect">
                    <div class="pricing-head">
                        <h3><?=$license->nombre?> <span> </span>
                        </h3>
                        <h4>
                            <?php if ($license->moneda->orden_visualizacion == Moneda::ORDENV_ANTES): ?>
                                <i><?php echo $license->moneda->simbolo ?></i>
                            <?php endif; ?>
                            <!--<?php //echo explode('.',$license->precio)[0]?><i>.<?php //echo explode('.',$license->precio)[1] ?></i>-->
                            <?php  echo $license->precio; ?>
                            <?php if ($license->moneda->orden_visualizacion == Moneda::ORDENV_DESPUES): ?>
                                <i><?php echo $license->moneda->simbolo ?></i>
                            <?php endif; ?>
                            <span>Por
                                <?php echo $license->duracion; ?>
                                <?=\common\models\Enum::getLabel($license->tipo_duracion)?>
                            </span>
                        </h4>
                    </div>

                    <div class="pricing-footer">
                        <p>
                            <?=$license->descripcion?>
                        </p>
                        <?php if($license->tipo == \common\models\Enum::TIPO_LICENCIA_ONEPAY):?>
                            <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">

                                <!-- Identify your business so that you can collect the payments. -->
                                <input type="hidden" name="business" value="<?php echo Yii::$app->params['paypal-business-id']; ?>">

                                <!-- Specify a Buy Now button. -->
                                <input type="hidden" name="cmd" value="_xclick">

                                <!-- Specify details about the item that buyers will purchase. -->
                                <input type="hidden" name="item_name" value="item_name">
                                <input type="hidden" name="item_number" value="item_number1">
                                <input type="hidden" name="amount" value="<?php  echo $license->precio; ?>">
                                <input type="hidden" name="currency_code" value="USD">

                                <!-- Specify URLs -->
                                <input type='hidden' name='cancel_return' value='<?php echo Yii::$app->params['url_paypal_reponse_cancel_now']; ?>'>
                                <input type='hidden' name='return' value='<?php echo Yii::$app->params['url_paypal_reponse_ok_now']; ?>'>
                                <input type="hidden" name="rm" value="2">
                                <input type="hidden" name="custom" value="<?php echo $userid . '_' .   $license->id . '_' . $salon->id . '_' . md5(uniqid($salon->id, true));  ?>">
                                <!-- Display the payment button. -->
                                <input type="image" name="submit" border="0"
                                       src="https://www.paypalobjects.com/es_ES/i/btn/btn_buynow_LG.gif" alt="PayPal - The safer, easier way to pay online">
                                <img alt="" border="0" width="1" height="1" src="https://www.paypalobjects.com/es_ES/i/scr/pixel.gif" >

                            </form>
                        <!--<script
                            data-env="sandbox"
                            data-callback="http://beautydate.local.api/index.php?r=v1/site/paypal-notification"
                            data-custom="<?php echo $license->id ?>"
                            data-currency="<?php echo Yii::$app->params['moneda.pago'] ?>"
                            data-amount="<?php echo $license->precio; ?>"
                            data-quantity="1"
                            data-name="BeautyDate Platinum License"
                            data-button="buynow" src="./themes/metronic/scripts/paypal-button.min.js?merchant=aleruiz-facilitator@softok2.com" async="async">
                        </script>-->
                        <?php elseif($license->tipo == \common\models\Enum::TIPO_LICENCIA_SUSCRIPTION):?>

                            <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
                                <input type="hidden" name="cmd" value="_s-xclick">
                                <input type="hidden" name="hosted_button_id" value="<?php echo $license->hosted_button_id; ?>">
                                <input type="hidden" name="custom" value="<?php echo $userid . '_' .   $license->id . '_' . $salon->id . '_' . md5(uniqid($salon->id, true));  ?>">
                                <input type="hidden" name="rm" value="2">

                                <!--<input type="hidden" name="notify_url" value="http://beautydate.softok2.com/index.php?r=site/ipn">-->
                                <!--<input type="hidden" name="notify_url" value="<?php echo Url::to('site/ipn'); ?>">-->
                                <input type="hidden" name="return" value="<?php echo Yii::$app->params['url_paypal_reponse_ok']; ?>">
                                <!--<input type="hidden" name="return" value="http://beautydate.softok2.com/payresponse.php">-->
                                <input type="hidden" name="cancel_return" value="<?php echo Yii::$app->params['url_paypal_reponse_cancel']; ?>">
                                <!--<input type="hidden" name="cancel_return" value="http://beautydate.softok2.com/payresponse-cancel.php">-->
                                <input type="image" src="https://www.sandbox.paypal.com/es_XC/MX/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal, la forma más segura y rápida de pagar en línea.">
                                <img alt="" border="0" src="https://www.sandbox.paypal.com/es_XC/i/scr/pixel.gif" width="1" height="1">
                            </form>

                        <?php endif;?>
                    </div>
                </div>
            </div>
            <?php endforeach;?>
            <!--//End Pricing -->
        </div>
    </div>
</div>

<!--<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="hosted_button_id" value="373NVKZTCEVM6">
    <input type="image" src="https://www.sandbox.paypal.com/es_XC/MX/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal, la forma más segura y rápida de pagar en línea.">
    <img alt="" border="0" src="https://www.sandbox.paypal.com/es_XC/i/scr/pixel.gif" width="1" height="1">
    <input type="hidden" name="rm" value="2">
</form>-->
