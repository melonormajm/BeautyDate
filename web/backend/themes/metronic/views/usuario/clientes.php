<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ClienteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Clientes';
//$this->params['breadcrumbs'][] = $this->title;
?>


<h3 class="page-title">
    Clientes
</h3>

<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <?= Html::a('Inicio',  Url::toRoute('site/home')) ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            Clientes
        </li>

    </ul>
</div>




<div class="salon-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function ($model, $key, $index, $grid) {
            return ['id' => $model->id, 'onclick' => 'loadUser(this.id);'];
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'header' => 'Nombre',
                'content'=> function($model, $key, $index, $column){

                    if(isset($model->userRedsocials) and count($model->userRedsocials) > 0){
                        $redsocial = $model->userRedsocials[0];
                        return $redsocial->nombre . ' ' . $redsocial->apellido;
                    }
                    else if($model->first_name){
                        return $model->first_name . ' ' . $model->last_name;
                    }else {
                        return $model->email;
                    }
                },
                'filter' => Html::input('text', 'first_name', $searchModel->first_name, ['class'=>'form-control'])
            ],
            'email',
            'user_type',
            'last_red_social'

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>

<?php Modal::begin(['header' => 'Detalles','id' => 'modal_user']);?>
    <div class="row static-info">
        <div class="col-md-5 name">
            Nombre de usuario
        </div>
        <div class="col-md-5 value" id="user_name">

        </div>
    </div>
    <div class="row static-info">
        <div class="col-md-5 name">
            Correo
        </div>
        <div class="col-md-5 value" id="user_email">
        </div>
    </div>
    <div class="row static-info">
        <div class="col-md-5 name">
            Tipo de usuario
        </div>
        <div class="col-md-5 value" id="user_tipo">
        </div>
    </div>
    <div class="row static-info">
        <div class="col-md-5 name">
            Fb nombre
        </div>
        <div class="col-md-5 value" id="user_fb_nombre">
        </div>
    </div>
    <div class="row static-info">
        <div class="col-md-5 name">
            Fb mail
        </div>
        <div class="col-md-5 value" id="user_fb_email">
        </div>
    </div>
    <div class="row static-info">
        <div class="col-md-5 name">
            Fb id
        </div>
        <div class="col-md-5 value" id="user_fb_id">
        </div>
    </div>
<?php Modal::end();?>

<style>
    .modal-dialog{
        z-index: 10500;
    }
    </style>
<script>
    function loadUser(id){


        $.getJSON(
            '<?php echo Url::to(['usuario/clienteview'])?>',{
                id:id
            }).done(

                function(response){
                    //console.log(response);
                    $("#user_name").text(response.username);
                    $("#user_email").text(response.email);
                    $("#user_tipo").text(response.user_type);
                    $("#user_fb_email").text('');
                    $("#user_fb_nombre").text('');
                    if(response.userRedsocials.length > 0){
                        $("#user_fb_email").text(response.userRedsocials[0].email);
                        $("#user_fb_nombre").text(response.userRedsocials[0].nombre);
                        //$("#user_tipo").text(response[0].user_type);

                    }

                    $("#modal_user").modal("show");
                }
        );
    }

</script>