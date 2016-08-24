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

<?php if(isset($salon->licencia)):?>
    <?php $class = $salon->licencia->estado == \common\models\Licencia::ESTADO_INACTIVO ? 'danger' : 'warning'; ?>
    <div class="note note-<?php echo $class; ?>">
        <h4 class="block">Nombre de la Licencia: <?=$salon->licencia->licenciaSpec->nombre?></h4>
        <p>
            Descripción: <?=$salon->licencia->licenciaSpec->descripcion?>
        </p>
        <p>
            Precio: <?=$salon->licencia->licenciaSpec->precio;?>
        </p>
        <p>
            Fecha Comprada: <?=Yii::$app->formatter->asDate($salon->licencia->fecha_inicio)?>
        </p>
        <p>
            Fecha Vencimiento: <?=Yii::$app->formatter->asDate($salon->licencia->fecha_fin)?>
        </p>
        <p>
            Estado: <?=$salon->licencia->estado; ?>
        </p>
        <p>
            Detalles: <?=$salon->licencia->detalles; ?>
        </p>

    </div>
<?php endif;?>


<?php if(!isset($salon->licencia) or $salon->licencia->estado == \common\models\Licencia::ESTADO_INACTIVO or $salon->licencia->estado == \common\models\Licencia::ESTADO_OLVIDADA):?>
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
                                        <?php if ($license->duracion > 1) echo $license->duracion; ?>
                                        <?=\common\models\Enum::getLabel($license->tipo_duracion)?> </span>
                                </h4>
                            </div>

                            <div class="pricing-footer">
                                <p>
                                    <?=$license->descripcion?>
                                </p>

                                <a href="<?=Url::toRoute(['licenciaspec/addlic','id'=>$license->id])?>" class="btn green btn-sm" title="Obtener">
                                    <i class="fa fa-asterisk"></i>
                                    <span> Solicitar</span>
                                </a>

                            </div>
                        </div>
                    </div>
                <?php endforeach;?>
                <!--//End Pricing -->
            </div>
        </div>
    </div>
<?php endif;?>

