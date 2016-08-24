<link rel="stylesheet" type="text/css" href="themes/beauty/admin/pages/css/pricing-table.css"/>
<?php
use yii\helpers\Html;
use yii\helpers\Url;
use \common\models\Moneda;
use \yii\widgets\ActiveForm;
use \yii\helpers\ArrayHelper;
use \common\models\Salon;
use common\models\Enum;

/* @var $this yii\web\View */
$this->title = 'Beauty Date';
?>

<h3 class="page-title">
    Licencias <small>disponibles</small>
</h3>


<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <?= Html::a('Inicio',  Url::toRoute('site/index')) ?>
        </li>
    </ul>
</div>

<?php if ($salones): ?>

<div class="row">
    <div class="col-md-12">
<div class="portlet">
    <div class="portlet-body">
        <div class="margin-bottom-40">
            <?php $form = ActiveForm::begin([
                'id' => 'esp_form',
                'action' => Url::toRoute('crear')
            ]); ?>
            <div class="row" style="margin-bottom: 30px;">
                <div class="col-md-3">
                <?= Html::label( Yii::t('backend', 'Salón'), 'salonid') ?>
                <?= Html::dropDownList('salon', null, ArrayHelper::map( $salones , 'id', 'nombre'),
                    ['id'=>'salonid', 'class'=>'form-control']); ?>
                <?= Html::hiddenInput('lic-spec', '', ['id'=>'lic-spec-id']); ?>
                </div>
            </div>
            <div class="row">
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
                            <?php $p = explode('.',$license->precio); ?>
                            <?php echo $p[0]?><i>.<?php echo isset($p[1]) ? $p[1] : '' ?></i>
                            <?php if ($license->moneda->orden_visualizacion == Moneda::ORDENV_DESPUES): ?>
                                <i><?php echo $license->moneda->simbolo ?></i>
                            <?php endif; ?>
                            <span>
                                <?=$license->duracion < 2 ? Yii::t('common', $license->tipo_duracion) :
                                    Yii::t('common', '{duracion} ' . Enum::getPlural($license->tipo_duracion), ['duracion' => $license->duracion]) ?> </span>
                        </h4>
                    </div>

                    <div class="pricing-footer">
                        <p>
                            <?=$license->descripcion?>
                        </p>
                        <p>
                            <?= Html::button(Yii::t('backend', 'Purchase'), ['class' => 'btn blue test', 'id'=>$license->id]) ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php endforeach;?>
            <!--//End Pricing -->
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
    </div>
</div>

<script type="application/javascript">
    /*
    $('#esp_form').on('submit', function(event){
        console.log('me mandaron pa lla');
        event.preventDefault();
    })*/
</script>

<?php $this->registerJs("$('.test').on('click', function(event){
        $('#lic-spec-id').val($(this).attr('id'));
        //console.log($('#salonid').val());
        $('#esp_form').submit();
    })"); ?>

<?php else: ?>
    <div class="note note-danger">
        <h4 class="block">No existen salones disponibles</h4>
        <p>
            No existen salones sin licencia
        </p>
    </div>
<?php endif; ?>