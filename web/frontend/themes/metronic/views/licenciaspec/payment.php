<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use \common\models\Moneda;
?>

<h3 class="page-title">
    Licencia<small></small>
</h3>

<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <?= Html::a('Inicio',  Url::toRoute('site/index')) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            Detalles
        </li>
    </ul>
</div>

<div class="note note-success">
    <div class="row">
        <div class="col-md-12">

        <h4 class="block">Nombre de la Licencia: <?=$licencia->licenciaSpec->nombre?></h4>

        <p>
            Licencia ID: <?=$licencia->id; ?>
        </p>

        <p>
            Descripción: <?=$licencia->licenciaSpec->descripcion?>
        </p>
        <p>
            Precio: <?=$licencia->licenciaSpec->precio;?>
        </p>
        <p>
            Fecha Comprada: <?=Yii::$app->formatter->asDate($licencia->fecha_inicio)?>
        </p>
        <p>
            Fecha Vencimiento: <?=Yii::$app->formatter->asDate($licencia->fecha_fin)?>
        </p>
        <p>
            Estado: <?=$licencia->estado; ?>
        </p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-1">
            <a href="<?=Url::toRoute(['salon/eliminar-licencia', 'salonid' => $salonid])?>" class="btn btn-danger" style="margin-top: 15px;">Regresar</a>
        </div>
    </div>
</div>


<div style="margin-bottom:20px">
    <h4 style="color: red;">IMPORTANTE</h4>
    <ul>
        <?php if($licencia->estado != \common\models\Licencia::ESTADO_ACTIVO){ ?>
        <li>
    <p>Si usted realiz&oacute; el pago, el sitio puede estar pendiente de recibir la notificaci&oacute;n de PAYPAL, en ese caso por favor espere a que se reciba esa notificaci&oacute;n.
        Si no complet&oacute; su pago, puede subscribirse ahora.</p>
        </li>
        <li>
    <p>Tambien puede olvidar su licencia y escoger otra.</p>
        </li>
        <?php } else { ?>
            <li>
                <p>En caso de que su licencia sea de pago recurrente, si desea cancelarla debe ir a su cuenta de paypal.</p>
            </li>
        <?php } ?>
    </ul>
</div>
<div class="row">
    <div class="col-md-2">

<?php if($licencia->licenciaSpec->tipo == \common\models\Enum::TIPO_LICENCIA_ONEPAY):?>
    <!--<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">-->
    <form action="<?php echo Yii::$app->params['url_paypal']; ?>" method="post">
        <!-- Identify your business so that you can collect the payments. -->
        <input type="hidden" name="business" value="<?php echo Yii::$app->params['paypal-business-id']; ?>">

        <!-- Specify a Buy Now button. -->
        <input type="hidden" name="cmd" value="_xclick">

        <!-- Specify details about the item that buyers will purchase. -->
        <input type="hidden" name="item_name" value="item_name">
        <input type="hidden" name="item_number" value="item_number1">
        <input type="hidden" name="amount" value="<?php  echo $licencia->licenciaSpec->precio; ?>">
       <input type="hidden" name="currency_code" value="MXN">
       <!-- <input type="hidden" name="currency_code" value="USD">-->

        <!-- Specify URLs -->
        <input type='hidden' name='cancel_return' value='<?php echo Yii::$app->params['url_paypal_reponse_cancel_now']; ?>'>
        <input type='hidden' name='return' value='<?php echo Yii::$app->params['url_paypal_reponse_ok_now']; ?>'>
        <input type="hidden" name="rm" value="2">
        <!--<input type="hidden" name="custom" value="<?php //echo $userid . '_' .   $license->id . '_' . $salon->id . '_' . md5(uniqid($salon->id, true));  ?>">-->
        <input type="hidden" name="custom" value="<?php echo $licencia->id; ?>">

        <!-- Display the payment button. -->
        <input type="image" name="submit" border="0"
               src="https://www.paypalobjects.com/es_ES/i/btn/btn_buynow_LG.gif" alt="PayPal - The safer, easier way to pay online">
        <img alt="" border="0" width="1" height="1" src="https://www.paypalobjects.com/es_ES/i/scr/pixel.gif" >

    </form>
<?php elseif($licencia->licenciaSpec->tipo == \common\models\Enum::TIPO_LICENCIA_SUSCRIPTION):?>

    <form action="<?php echo Yii::$app->params['url_paypal']; ?>" method="post" target="_top">
        <!--<input type="hidden" name="cmd" value="_xclick-subscriptions">-->
		<input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="<?php echo $licencia->licenciaSpec->hosted_button_id; ?>">
		<input type="image" src="https://www.paypalobjects.com/en_US/MX/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
        <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
        <input type="hidden" name="custom" value="<?php echo $licencia->id; ?>">
        <input type="hidden" name="rm" value="2">

    </form>
    <!--<form action="<?php echo Yii::$app->params['url_paypal']; ?>" method="post" target="_top">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="<?php echo $licencia->licenciaSpec->hosted_button_id; ?>">
        <input type="image" src="https://www.paypalobjects.com/en_US/MX/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
        <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
        <input type="hidden" name="custom" value="<?php echo $licencia->id; ?>">
        <input type="hidden" name="rm" value="2">
    </form>-->
    <!--
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="RRSCEGB43JW7C">
        <input type="image" src="https://www.paypalobjects.com/en_US/MX/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
        <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
        <input type="hidden" name="custom" value="<?php echo $licencia->id; ?>">
        <input type="hidden" name="rm" value="2">
    </form>
    -->
<?php endif;?>

        </div>
</div>
