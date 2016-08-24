<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\models\Servicio;
use app\models\ServicioSearch;
use app\models\Categoria;
use app\models\Sillon;

/* @var $this yii\web\View */
/* @var $model app\models\Salon

$this->title = 'Update Salon: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Administrar Salón', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nombre];*/
?>

<h3 class="page-title">
     Evaluación<small> votos de los clientes</small>
</h3>

<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <?= Html::a('Inicio',  Url::toRoute('site/home')) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            Evaluación
        </li>

    </ul>
</div>

<?php if($model->evaluacion != NULL): ?>
    <input id="input-5a" class="rating" data-readonly="true" value="<?=$model->evaluacion?>">
<?php else:?>
    <div class="alert alert-info">
        <strong>Info!</strong> Su salón aún no tiene votos de clientes.
    </div>
<?php endif?>




<?php
    $StarsJS = <<<EOF
           $("#input-id").rating(['min'=>1, 'max'=>10, 'step'=>2, 'size'=>'lg']);
EOF;
    $this->registerJs($StarsJS);

?>