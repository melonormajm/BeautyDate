<?php

namespace frontend\controllers;

use app\models\ServicioSearch;
use common\models\Salon;
use common\models\Servicio;
use common\models\SillonServicio;
//use common\models\Sillon;
use yii\base\Exception;


use Yii;
use common\models\Reservacion;
use app\models\ReservacionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use common\models\User;

/**
 * ReservacionController implements the CRUD actions for Reservacion model.
 */
class ReservacionController extends Controller
{

    private $salonid;

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'actualizarestado' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Reservacion models.
     * @return mixed
     */

    public function actionIndex()
    {
        //$searchModel = new ReservacionSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $salon = Salon::find()->where(['usuarioid'=>Yii::$app->user->id])->with('servicios')->one();
        if($salon == null){
            $message = $this->actionMessage('error','Debe configurar su salón antes de poder recibir reservaciones.');
            return $this->render('error',[
                'message'=>$message,
            ]);
        }
        $servicios = $salon->servicios;

        if($servicios == NULL){
            $message = $this->actionMessage('error','Debe crear servicios antes de poder manejar sus reservaciones.');
            return $this->render('error',[
                'message'=>$message,
            ]);
        }
        $users = User::find()->with('userRedsocials')->where(['user_type'=> User::TIPO_CLIENTE])->all();
        if($users == NULL){
            $message = $this->actionMessage('error','No hay usuarios registrados como clientes.');
            return $this->render('error',[
                'message'=>$message,
            ]);
        }

        $users_array = [];
        foreach($users as $u){
            if(isset($u->userRedsocials) and count($u->userRedsocials) > 0){
                $users_array[$u->id] =  $u->userRedsocials[0]->nombre;
            }elseif($u->first_name){
                $users_array[$u->id] =  $u->first_name;
            }else{
                $users_array[$u->id] = $u->email;
            }

        }
        //$users = ArrayHelper::map($users, 'id', 'userRedsocials[0].nombre');
        $dataList=ArrayHelper::map($servicios, 'id', 'nombre');
        $model = new Reservacion();

        return $this->render('index', [
            //'searchModel' => $searchModel,
            //'dataProvider' => $dataProvider,
            'model' => $model,
            'datalist' => $dataList,
            'users' => $users_array,
        ]);
    }

    public function actionResumen(){

        $salon = Salon::find()->where(['usuarioid'=>Yii::$app->user->id])->with('servicios')->one();
        if($salon == null){
            $message = $this->actionMessage('error','Debe configurar su salón antes de poder recibir reservaciones.');
            return $this->render('error',[
                'message'=>$message,
            ]);
        }
        $servicios = $salon->servicios;

        if($servicios == NULL){
            $message = $this->actionMessage('error','Debe crear servicios antes de poder manejar sus reservaciones.');
            return $this->render('error',[
                'message'=>$message,
            ]);
        }
        $users = User::find()->with('userRedsocials')->where(['user_type'=> User::TIPO_CLIENTE])->all();
        if($users == NULL){
            $message = $this->actionMessage('error','No hay usuarios registrados como clientes.');
            return $this->render('error',[
                'message'=>$message,
            ]);
        }

        $searchModel = new ReservacionSearch();
        $searchModel->cliente = Yii::$app->request->get('cliente');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //echo "<pre>"; print_r($dataProvider->getModels());
        return $this->render('resumen', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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

    public function actionJsoncalendar($start=NULL,$end=NULL,$_=NULL){
        $events = array();

        $this->salonid = Salon::findOne(['usuarioid' => Yii::$app->user->id])->id;

        $reservaciones = \common\models\Reservacion::find()
            ->with('usuario.userRedsocials')
            ->with('sillonServicio.servicio')
            ->with('sillonServicio.sillon')
            ->innerJoinWith(['sillonServicio.servicio.salon' => function ($query) {
                $query->andWhere(['salon.id' => $this->salonid]);
            }])
            //->orderBy('fecha DESC,hora_inicio DESC')
            ->all();
        //echo '<pre>';
        //print_r($reservaciones);die;

        foreach ($reservaciones as $r) {

            $Event = new \yii2fullcalendar\models\Event();
            $Event->id = $r->id;
            $usuario = $r->usuario;

            if($r->nombre_cliente){
                $Event->title = 'Cliente: '.$r->nombre_cliente;
                $Event->cliente = $r->nombre_cliente;
            }elseif(isset($usuario->userRedsocials) and count($usuario->userRedsocials) > 0){
                $redsocial = $usuario->userRedsocials[0];
                if($redsocial->nombre){
                    $Event->title = 'Cliente: '.$redsocial->nombre.' '.$redsocial->apellido;
                    $Event->cliente = $redsocial->nombre.' '.$redsocial->apellido;
                }
            }
            else{
                $Event->title = 'Cliente: '.$usuario->email;
                $Event->cliente = $usuario->email;
            }
            $Event->title .= ' Servicio: ' . $r->sillonServicio->servicio->nombre .
            ' Sillón: ' . $r->sillonServicio->sillon->nombre;
            $Event->servicio = $r->sillonServicio->servicio->nombre;
            $Event->sillon = $r->sillonServicio->sillon->nombre;

            //print_r($r->fecha . ' ' . $r->hora_inicio);
            $fecha = date_create_from_format('Y-m-d Hi', $r->fecha . ' ' . $r->hora_inicio);
            //print_r(date_format($fecha,'Y-m-d Hi'));die;
            $Event->start = date_format($fecha,'Y-m-d\TH:i:s\Z');//date_format(date_create($r->fecha . ' ' . $r->hora_inicio), 'Y-m-d\TH:m:s\Z');
            //$Event->description = 'Cualquier cosa';
            if($r->estado == Reservacion::ESTADO_CANCELADA){
                $Event->backgroundColor = 'rgb(243, 86, 93)';
            }elseif($r->estado == Reservacion::ESTADO_EJECUTADA){
                $Event->backgroundColor = '#6E5C7F';
            }elseif($r->estado == Reservacion::ESTADO_PENDIENTE){
                $Event->backgroundColor = '#3d3d3d';
            }
            $events[] = $Event;
        }


        header('Content-type: application/json');
        echo Json::encode($events);

        Yii::$app->end();
    }


    /**
     * Displays a single Reservacion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Reservacion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Reservacion();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Reservacion model.
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
     * Deletes an existing Reservacion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Reservacion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Reservacion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Reservacion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionActualizarestado($id) {
        $model = $this->findModel($id);

        $model->load(Yii::$app->request->post());
        $model->save(false);
        return $this->redirect(['reservacion/index']);
    }

    public function actionCancelar(){
        $idreserva = Yii::$app->request->post("eventoid");
        $reserva = $this->findModel($idreserva);
        $reserva->estado = Reservacion::ESTADO_CANCELADA;
        $reserva->save();
        //$result[] = $reserva->id;
        //$result[] = $reserva->estado;
        //return Json::encode($result);
        Yii::$app->end();
    }

    public function actionConfirmar(){
        $idreserva = Yii::$app->request->post("eventoid");
        $reserva = $this->findModel($idreserva);
        $reserva->estado = Reservacion::ESTADO_EJECUTADA;
        $reserva->save();
        echo 'success';
        Yii::$app->end();
    }

	public function addUser($name,$mail){
        $user = new User();
        $user->email = $mail;
        $user->password_hash = "ninguno";
        $user->username = $name;
        $user->first_name = $name;
        $user->user_type = User::TIPO_CLIENTE;
        if($user->save())
            return $user->id;
        else
            return null;
    }

    public function actionReservar() {
		$userid = Yii::$app->user->getId();
        $salon = Salon::find()->where(['usuarioid'=>$userid])->one();

        $date = Yii::$app->request->post('fecha') . Yii::$app->request->post('hora_inicio');
        $servid = Yii::$app->request->post('servicios');

        $fecha = date_create($date);
        $hora_inicio = date_format($fecha, 'Hi');
        $fecha_inicio = date_format($fecha, 'Y-m-d');
        $duracion_servicio = 0;
        $r = \common\helper\ReservacionHelper::getAvailablehours($servid, $fecha_inicio);
        //echo '<pre>'; print_r($r); echo 'date: ' . $date; die;
        if (isset($r[$hora_inicio]))
            $sillones = $r[$hora_inicio];
        else
            throw new Exception('Ese horario no se encuentra disponible');

        $sillon_servicio = SillonServicio::find()->where(['servicioid' => $servid])
            ->andWhere(['sillonid' => $sillones,
                        'estado'   => '1'
            ])->one();
            //->filterWhere(['not in', 'sillonid', $sillones])->one();
        //echo '<pre>'; print_r($sillon_servicio->id); die;


        $reserv = new Reservacion();
        $reserv->estado = Reservacion::ESTADO_PENDIENTE;
        $reserv->aplicacion_cliente = Reservacion::APPCLIENT_APP;
        if(Yii::$app->request->post('nuevo_cliente')=="on"){
            $reserv->usuarioid = $this->addUser(Yii::$app->request->post('nombre_cliente'),
                Yii::$app->request->post('correo_cliente'));
        }else{
            $reserv->usuarioid = Yii::$app->request->post('users');
        }
        $reserv->sillon_servicioid = $sillon_servicio->id;
		$reserv->salonid = $salon->id;
        $reserv->fecha = date_format($fecha, 'Y-m-d');
        $reserv->hora_inicio = date_format($fecha, 'Hi');
        date_add($fecha, date_interval_create_from_date_string($sillon_servicio->servicio->duracion . ' minutes'));
        $reserv->hora_fin = date_format($fecha, 'Hi');

        if (!$reserv->save()){
            //throw new Exception(Messages::ERROR_OP_FAIL);
        }

        if($reserv->nombre_cliente){
            $user = $reserv->nombre_cliente;
        }else{
            $user = $reserv->usuario->username;
        }
        $ret = [
            'id' => $reserv->id,
            'user' => $user,
            'start' => date_format(date_create($reserv->fecha . ' ' . $reserv->hora_inicio), 'Y-m-d\TH:m:s\Z'),
            'end' => date_format(date_create($reserv->fecha . ' ' . $reserv->hora_fin), 'Y-m-d\TH:m:s\Z')
        ];

        header('Content-type: application/json');
        echo Json::encode($ret);
        Yii::$app->end();
    }


    function actionGetAvailableTime($date, $servid){
        $user = User::findOne(Yii::$app->user->getId());
        //$url = "http://api.beautydate.softok2.com/web/index/php";
        $url = Yii::$app->params['url_api_available_hours'];
        $url .= '&token='.$user->getAuthKey();
        $url .= '&date=' . $date;
        $url .= '&servid=' . $servid;
        //$url .= '&r=v1/reservacion/getavailablehours';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        //$result =  \common\helper\Reservacion::getAvailableTimes($date, $servid);
        echo $result;
        Yii::$app->end();
    }


}
