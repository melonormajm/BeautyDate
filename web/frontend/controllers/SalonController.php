<?php

namespace frontend\controllers;

use common\helper\SalonHelper;
use common\models\Categoria;
use app\models\CategoriaSalon;
use common\models\Imagenes;
use common\models\Licencia;
use common\models\Moneda;
use common\models\Reservacion;
use common\models\Sillon;
use common\models\User;
use Yii;
use common\models\Salon;
use frontend\models\SalonSearch;
use common\models\Servicio;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;

/**
 * SalonController implements the CRUD actions for Salon model.
 */
class SalonController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Salon models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SalonSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionHome()
    {
        /*TODO: validar los restantes if, si el primero no se cumple los demás no tienen sentido.*/
        $salon = Salon::find()->where(['usuarioid'=>Yii::$app->user->getId()])->one();

        if(!$salon)
            return $this->redirect(['configuraciones']);

        if($salon && $salon->estado_sistema == Salon::ESTADO_SISTEMA_INACTIVO){
            return $this->redirect(['configuraciones']);
        }

        $time = new \DateTime('now');
        $today = $time->format('Y-m-d');
        $new_reserv = Reservacion::find()
            ->innerJoinWith(['sillonServicio.servicio.salon' => function ($query) {
                $query->andWhere(['salon.id' => Salon::findOne(['usuarioid' => Yii::$app->user->id])->id]);
            }])
            ->where(['>=', 'fecha', $today])
            ->andWhere(['reservacion.estado'=>Reservacion::ESTADO_PENDIENTE])
            ->count();
        $earn_query = Yii::$app->db->createCommand("SELECT SUM( serv.precio )
                FROM reservacion r
                INNER JOIN sillon_servicio siserv ON siserv.id = r.sillon_servicioid
                INNER JOIN servicio serv ON serv.id = siserv.servicioid
                WHERE r.estado =  'EJECUTADA'
                AND serv.salonid =:salon_id",["salon_id"=>Salon::findOne(['usuarioid' => Yii::$app->user->id])->id]);
        $ganancias = $earn_query->queryScalar();
        $future_earn_query = Yii::$app->db->createCommand("SELECT SUM( serv.precio )
                FROM reservacion r
                INNER JOIN sillon_servicio siserv ON siserv.id = r.sillon_servicioid
                INNER JOIN servicio serv ON serv.id = siserv.servicioid
                WHERE r.estado =  'PENDIENTE'
                AND serv.salonid =:salon_id",["salon_id"=>Salon::findOne(['usuarioid' => Yii::$app->user->id])->id]);
        $ganancias_futuras = $future_earn_query->queryScalar();

        return $this->render('home',[
            'new_reserv' => $new_reserv,
            'evaluacion' => $salon->evaluacion,
            'ganancias' => number_format((float)$ganancias, 2, '.', ''),
            'ganancias_futuras' => number_format((float)$ganancias_futuras, 2, '.', '')
        ]);
    }



    public function actionGeneral(){
        $userid = Yii::$app->user->getId();
        $model = Salon::find()->where(['usuarioid'=>$userid])->one();
        //echo '<pre>';
        //echo print_r($model);die;
        $moneda = Moneda::find()->all();
        $monedas = \yii\helpers\ArrayHelper::map($moneda,'id','nombre');
        //echo '<pre>';
        //print_r(Yii::$app->request->post());
        //die;

        if($model == NULL)
        {
            $message = $this->actionMessage('info','Necesita insertar los datos de su salón para que pueda ser visualizado por los clientes.');
            return $this->actionCreate();
        }
        //echo '<pre>';
        //print_r(Yii::$app->request->post());die;
        if($model->load(Yii::$app->request->post())) {
            $days = isset(Yii::$app->request->post()['dias_no_laborables']) ?
                Yii::$app->request->post()['dias_no_laborables'] : [];
            $model->hora_inicio = $this->formatTime($model->hora_inicio,'Hi');
            $model->hora_fin = $this->formatTime($model->hora_fin,'Hi');
            $string_day = "";
            if(count($days)>0){
                foreach($days as $day){
                    $string_day .= $day.',';
                }
                $model->dias_no_laborables = rtrim($string_day, ",");
            }else{
                $model->dias_no_laborables = "";
            }
            $model->save();
            $message = $this->actionMessage('info','La información de su salón ha sido actualizada.');
        }
        $model->hora_inicio = $this->formatTime($model->hora_inicio,'g:i:s A');
        $model->hora_fin = $this->formatTime($model->hora_fin,'g:i:s A');
        $selected = ($model->dias_no_laborables != '') ? explode(',',$model->dias_no_laborables) : [];

        return $this->render('general',[
        'model'=>$model,
        'monedas'=>$monedas,
        'selected'=>$selected,
        ]);

    }

    public function actionMessage($type,$message){
        switch($type){
            case 'success':
                $class = 'alert-success';
                break;
            case 'info':
                $class = 'alert-info';
                break;
            case 'warning':
                $class = 'alert-warning';
                break;
            case 'error':
                $class = 'alert-danger';
                break;
        }
        return '<div class="alert '.$class.' alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
        </button><strong>'.'</strong>'.$message.'</div>';
    }

    public function actionManage()
    {
        $userid = Yii::$app->user->getId();
        $model = Salon::find()->where(['usuarioid'=>$userid])->one();
        $categorysalon = new CategoriaSalon();
        $imagenes = new Imagenes();
        $servicio = new Servicio();
        $categoria = new Categoria();
        $sillon = new Sillon();
        $dataList=ArrayHelper::map(Categoria::find()->asArray()->all(), 'id', 'nombre');

        if($model == NULL)
        {
            return $this->actionCreate();
        }
        else{
            $categorias = $model->getCategorias();
            $model->hora_inicio = $this->formatTime($model->hora_inicio,'g:i:s A');
            $model->hora_fin = $this->formatTime($model->hora_fin,'g:i:s A');
        }
        if($model->load(Yii::$app->request->post())) {
            $model->hora_inicio = $this->formatTime($model->hora_inicio,'Hi');
            $model->hora_fin = $this->formatTime($model->hora_fin,'Hi');
            $model->save();
            $model->hora_inicio = $this->formatTime($model->hora_inicio,'g:i:s A');
            $model->hora_fin = $this->formatTime($model->hora_fin,'g:i:s A');
            }
            return $this->render('manage', [
                'model' => $model,
                'categorysalon' => $categorysalon,
                'categorias' => $categorias,
                'imagenes' => $imagenes,
                'servicio' => $servicio,
                'categoria' => $categoria,
                'dataList' => $dataList,
                'sillon' => $sillon,
            ]);
    }

    public function actionPreview(){
        $userid = Yii::$app->user->getId();
        $model = Salon::find()->where(['usuarioid'=>$userid])->one();

        if($model == NULL)
        {
            return $this->actionCreate();
        }
        else{
            $thumbnail = Imagenes::find()->where(['salonid'=>$model->id,'principal'=>1])->one();
            $imagenes = Imagenes::find()->where(['salonid'=>$model->id])->all();
            $categorias = $model->getCategorias()->all();
            $model->hora_inicio = $this->formatTime($model->hora_inicio,'g:i:s A');
            $model->hora_fin = $this->formatTime($model->hora_fin,'g:i:s A');

            return $this->render('preview',[
                'model'=>$model,
                'thumbnail'=>$thumbnail,
                'imagenes'=>$imagenes,
                'categorias'=>$categorias,
                'menu'=>'salonMenu',
            ]);
        }
    }

    public function actionConfiguraciones(){
        $servicios = false;
        $sill_servs = false;

        $salon = Salon::find()
            ->with('servicios')
            ->with('sillons')
            ->with('licencia')
            ->where(['usuarioid'=>Yii::$app->user->getId()])
            ->one();
        if($salon){
            SalonHelper::actualizarEstadoSalon(null, Yii::$app->user->getId());

            $cant_serv = count($salon->servicios);
            if($cant_serv > 0){
                foreach($salon->servicios as $serv){
                    if($serv->estado == Servicio::ESTADO_ACTIVO && $serv->estado_sistema == Servicio::ESTADO_SISTEMA_ACTIVO){
                        $servicios = true;
                        break;
                    }
                }
            }

            $cant_sillones = count($salon->sillons);
            if($cant_sillones>0){
                foreach($salon->sillons as $sillon){
                    $sillon_servicios = $sillon->sillonServicios;
                    if(count($sillon_servicios)>0 && $sillon->estado == Sillon::ESTADO_ACTIVO){
                        $sill_servs = true;
                        break;
                    }
                }
            }

            $salon_licencia = $salon->licencia;

            
            return $this->render('configuraciones',[
                'servicios' => $servicios,
                'cantidad_servicios' => $cant_serv,
                'cantidad_sillones' => $cant_sillones,
                'sillones_servicios' => $sill_servs,
                'licencia' => $salon_licencia,
            ]);
        }
       return $this->actionCreate();
    }

    public function actionEvaluacion(){
        $userid = Yii::$app->user->getId();
        $model = Salon::find()->where(['usuarioid'=>$userid])->one();
        if($model == null){
            $message = $this->actionMessage('error','Debe configurar su salón antes de poder recibir evaluaciones.');
            return $this->render('error',[
                'message'=>$message,
            ]);
        }
        return $this->render('evaluacion',['model'=>$model]);
    }

    public function actionPerfil(){

        $userid = Yii::$app->user->getId();
        $model = User::find()->where(['id'=>$userid])->one();


        if(Yii::$app->request->post()) {
            if(isset(Yii::$app->request->post()['User']['password'])){
                $model->setPassword(Yii::$app->request->post()['User']['password']);
            }else{
                $model->first_name = Yii::$app->request->post()['User']['first_name'];
                $model->last_name = Yii::$app->request->post()['User']['last_name'];
                $model->email = Yii::$app->request->post()['User']['email'];
            }
            if($model->save()){
                return $this->render('profile',[
                    'model' => $model,
                    'message_data' => 'success'
                ]);
            }
        }
        return $this->render('profile',[
            'model' => $model
        ]);
    }

    /**
     * Displays a single Salon model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function formatTime($hora,$format){
        $hora = date_create('2000-01-01 ' . $hora);
        $hora = date_format($hora, $format);
        return $hora;
    }

    /**
     * Creates a new Salon model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Salon();
        $moneda = Moneda::find()->all();
        $monedas = \yii\helpers\ArrayHelper::map($moneda,'id','nombre');

        if ($model->load(Yii::$app->request->post())) {
            $model->estado = Salon::ESTADO_ACTIVADO;
            $model->estado_sistema = Salon::ESTADO_SISTEMA_INACTIVO;
            $model->hora_inicio = $this->formatTime($model->hora_inicio,'Hi');
            $model->hora_fin = $this->formatTime($model->hora_fin, 'Hi');

            $model->usuarioid = Yii::$app->user->getId();
            if($model->save())
                return $this->actionGeneral();

        }
        return $this->render('create', [
            'model' => $model,
            'monedas' => $monedas,
            'selected'=> $model->dias_no_laborables ? explode(',',$model->dias_no_laborables) : []
        ]);
    }

    /**
     * Updates an existing Salon model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Salon model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    public function actionLicencia(){
        return $this->render('licencia');
    }

    /**
     * Finds the Salon model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Salon the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Salon::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionEliminarLicencia($salonid) {
        $salon = $this->findModel($salonid);
        //$salon->licencia->delete();
        $lic = $salon->licencia;
        //$lic->estado = Licencia::ESTADO_INACTIVO;
        $lic->estado = Licencia::ESTADO_OLVIDADA;
        $lic->detalles = 'Cancelado por el propietario';
        $lic->save();
        $salon->licenciaid = null;
        $salon->save();
        SalonHelper::actualizarEstadoSalon($salonid = $salonid);
        return $this->redirect(['licenciaspec/especificaciones']);
    }
}
