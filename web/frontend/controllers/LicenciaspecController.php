<?php

namespace frontend\controllers;

use common\models\Enum;
use common\models\User;
use common\models\Transferencia;
use common\models\Salon;
use backend\models\TransaccionSearch;
use backend\models\IpnNotificationSearch;
use Faker\Provider\cs_CZ\DateTime;
use Yii;
use common\models\LicenciaSpec;
use common\models\Licencia;
use app\models\LicenciaSpecSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LicenciaspecController implements the CRUD actions for LicenciaSpec model.
 */
class LicenciaspecController extends Controller
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
     * Lists all LicenciaSpec models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LicenciaSpecSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
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

    public function actionEspecificaciones(){
        $userid = Yii::$app->user->getId();
        $salon = Salon::find()->where(['usuarioid'=>$userid])->one();

        //Chequear si el usuario ha utilizado un licencia de prueba en algun momento
        $has_used_trial = Yii::$app->db->createCommand("Select * from licencia l,licencia_spec ls WHERE l.licencia_specid = ls.id AND l.estado <> :estadoaban AND ls.tipo = :tipolic AND l.usuario = :uid")
            ->bindValue(':uid', $userid)
            ->bindValue(':tipolic', Enum::TIPO_LICENCIA_TRAIL)
            ->bindValue(':estadoaban', Licencia::ESTADO_OLVIDADA)
            ->query()
            ->count();

        if($salon == null){
            return $this->render('error',[
                'message'=> $this->actionMessage('error','Antes de adquirir una licencia debe configurar su salón.')
            ]);
        }
        //$model = LicenciaSpec::find()->where(['estado'=> Enum::ESTADO_ACTIVO])->all();
        $aModel = LicenciaSpec::find()->where(['estado'=> Enum::ESTADO_ACTIVO]);
        if($has_used_trial){
            $aModel->andWhere(['<>', 'tipo', Enum::TIPO_LICENCIA_TRAIL]);
        }

        $aModel->orderBy('precio ASC');
        $model = $aModel->all();

        if($model == null){
            return $this->render('error',[
                'message'=> $this->actionMessage('error','No existen especificaciones de licencia.')
            ]);
        }
        $licencia = $salon->licencia;
        //$especificacion = LicenciaSpec::find()->where(['id'=>$licencia->id])->one();
        if($salon->licencia != null and $salon->licencia->estado == Licencia::ESTADO_ACTIVO) {
            //$detallesPagoDp = null;
            //$detallesPagoSearch = new TransaccionSearch();
            $ipnNotificacionesSearch = new IpnNotificationSearch();
            $ipnNotificacionesDp = null;


            $ipnNotificacionesSearch->licencia_id = $salon->licenciaid;
            $ipnNotificacionesDp = $ipnNotificacionesSearch->search(Yii::$app->request->queryParams);

            $licencia_vencida = false;

            if($salon->licencia->licenciaSpec->tipo == Enum::TIPO_LICENCIA_ONEPAY){
                $today = date("Y-m-d");
                $expires = strtotime($salon->licencia->fecha_fin);
                $today = strtotime($today);
                if ($expires < $today) {
                    $licencia_vencida = true;
                    $salon->licencia->estado = Licencia::ESTADO_INACTIVO;
                    $salon->save();
                }
            }

            if( $salon->licencia->estado == Licencia::ESTADO_INACTIVO){
                $licencia_vencida = true;
            }

            return $this->render('details', [
                //'especificacion' => $especificacion,
                'model' => $salon,
                //'detallesPagoSearch' =>$detallesPagoSearch,
                //'detallesPagoDp' => $detallesPagoDp,
                'ipnNotificacionesSearch' => $ipnNotificacionesSearch,
                'ipnNotificacionesDp' => $ipnNotificacionesDp,
                'tipoLicencia' => $salon->licencia ? $salon->licencia->licenciaSpec->tipo : null,
                'licencia' => $licencia,
                'licencia_vencida' => $licencia_vencida,
            ]);
        }

        if($salon->licencia != null and $salon->licencia->estado == Licencia::ESTADO_PROCESANDO){
            /*
            return $this->render('payment',[
                'licencia' => $licencia,
                'hidenote' => true
            ]);*/
            return $this->redirect(['licenciaspec/addlic', 'id' => $licencia->licencia_specid]);
        }

        $userid = Yii::$app->user->getId();
        $salon = Salon::find()->where(['usuarioid'=>$userid])->one();
        //echo '<pre>';
        //echo var_dump($model);die;
        /*return $this->render('especificaciones',[
            'model'    =>    $model,
            'userid'  =>    $userid,
            'salon'   =>    $salon
        ]);*/
        return $this->render('listespec',[
            'model'    =>    $model,
            'userid'  =>    $userid,
            'salon'   =>    $salon
        ]);

    }

    public function actionNuevalicencia(){
        $model = LicenciaSpec::find()->where(['estado'=> Enum::ESTADO_ACTIVO])->all();
        $userid = Yii::$app->user->getId();
        $salon = Salon::find()->where(['usuarioid'=>$userid])->one();

        return $this->render('especificaciones',[
            'model'    =>    $model,
            'userid'  =>    $userid,
            'salon'   =>    $salon
        ]);
    }

    public function actionPagarlicencia($id){
        $licencia = $this->findModel($id);
        $transferencia = Transferencia::find()->one();

        if($transferencia == NULL){

        }

        return $this->render('pagar',[
            'licencia'=>$licencia,
            'transferencia'=>$transferencia,
        ]);
    }


    public function actionComprarlicencia($id){
        $licencia = new Licencia();
        $model = $this->findModel($id);
        $inidate = new \DateTime();
        $licencia->fecha_inicio = $inidate->format('Y-m-d');
        $licencia->licencia_specid = $model->id;
        $licencia->estado = Licencia::ESTADO_INACTIVO;
        $userid = Yii::$app->user->getId();
        $salon = Salon::find()->where(['usuarioid'=>$userid])->one();

        $date = new \DateTime();
        switch ($model->tipo_duracion) {
            case 'anno':
                $interval = new \DateInterval('P'.$model->duracion.'M');
                $date->add($interval);
                break;
            case 'mes':
                $interval = new \DateInterval('P'.$model->duracion.'M');
                $date->add($interval);
                break;
            case 'dia':
                $interval = new \DateInterval('P'.$model->duracion.'M');
                $date->add($interval);
                break;
        }
        $licencia->fecha_fin = $date->format('Y-m-d');
        $licencia->save();
        $salon->licenciaid = $licencia->id;
        $salon->save();
        return $this->render('comprarlicencia',['licencia'=>$licencia]);
    }

    /**
     * Displays a single LicenciaSpec model.
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
     * Creates a new LicenciaSpec model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LicenciaSpec();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Updates an existing LicenciaSpec model.
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
 * Deletes an existing LicenciaSpec model.
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
 * Finds the LicenciaSpec model based on its primary key value.
 * If the model is not found, a 404 HTTP exception will be thrown.
 * @param integer $id
 * @return LicenciaSpec the loaded model
 * @throws NotFoundHttpException if the model cannot be found
 */
protected function findModel($id)
{
    if (($model = LicenciaSpec::findOne($id)) !== null) {
        return $model;
    } else {
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}



    public function actionAddlic ( $id )  {

        $licSpec = LicenciaSpec::find()->where(['id' => $id] )->one();

       /* if(!$licSpec){
            return '';

        }*/

        $userid = Yii::$app->user->getId();
        $salon = Salon::find()->where(['usuarioid'=>$userid])->one();
        $licencia = null;

        if($salon->licencia and $salon->licencia->estado == Licencia::ESTADO_PROCESANDO and $salon->licencia->licencia_specid == $id){
            $licencia = $salon->licencia;
        }else{
            $licencia = new Licencia();
            $duracion = $licSpec->duracion;
            $tipo_duracion = $licSpec->tipo_duracion;

            $licencia->estado = $licSpec->tipo == Enum::TIPO_LICENCIA_TRAIL ? Licencia::ESTADO_ACTIVO : Licencia::ESTADO_PROCESANDO;
            //$licencia->estado = Licencia::ESTADO_PROCESANDO;

            $h_inicio = date_create();
            $licencia->fecha_inicio = date_format($h_inicio, 'Y-m-d H:i');

            if ($tipo_duracion == LicenciaSpec::TIPO_DURACION_DIA)
                $tipo_duracion_str = 'days';
            elseif ($tipo_duracion == LicenciaSpec::TIPO_DURACION_MES)
                $tipo_duracion_str = 'months';
            elseif ($tipo_duracion == LicenciaSpec::TIPO_DURACION_ANNO)
                $tipo_duracion_str = 'years';

            date_add($h_inicio, date_interval_create_from_date_string($duracion . ' ' . $tipo_duracion_str));
            if ($licSpec->tipo == Enum::TIPO_LICENCIA_ONEPAY or $licSpec->tipo == Enum::TIPO_LICENCIA_TRAIL )
                $licencia->fecha_fin = date_format($h_inicio, 'Y-m-d H:i');

            $licencia->licencia_specid = $licSpec->id;
            $licencia->usuario = $userid;

            $licencia->save();
            $salon->licenciaid = $licencia->id;
            $salon->save();
        }
        return $this->render('payment',[
            'licencia' => $licencia,
            'salonid' => $salon->id,
        ]);

    }

}
