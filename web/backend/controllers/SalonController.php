<?php

namespace backend\controllers;

use backend\models\LicenciaAdminForm;
use backend\models\SalonSearch;
use backend\models\TransaccionSearch;
use backend\models\IpnNotificationSearch;
use common\helper\SalonHelper;
use common\models\Enum;
use common\models\IpnNotification;
use common\models\Licencia;
use common\models\LicenciaSpec;
use Yii;
use common\models\Salon;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
        /*
        echo date_default_timezone_get();
        //date_default_timezone_set('America/Havana');
        //$date = date('m/d/Y h:i:s a', date_create());
        echo "<br>" . Yii::$app->formatter->asDateTime(date_create());
        echo "<br> date: " . date_format(date_create(), 'm/d/Y h:i:s a');
        //echo "<br>" . \Yii::t('app', 'Today is {0, date}', date_create());
        die("<br>tu madre");*/
        $searchModel = new SalonSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Salon model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $ipnNotificacionesSearch = new IpnNotificationSearch();
        $ipnNotificacionesDp = null;

        $ipnNotificacionesSearch->licencia_id = $model->licenciaid;
        $ipnNotificacionesDp = $ipnNotificacionesSearch->search(Yii::$app->request->queryParams);

        $lic = new LicenciaAdminForm();
        $lic->usuario_id = $model->usuarioid;
        $lic_especs = LicenciaSpec::find()->all();


        return $this->render('view', [
            'model' => $model,
            //'detallesPagoSearch' =>$detallesPagoSearch,
            //'detallesPagoDp' => $detallesPagoDp,
            'ipnNotificacionesSearch'  => $ipnNotificacionesSearch,
            'ipnNotificacionesDp'=> $ipnNotificacionesDp,
            'tipoLicencia' => $model->licencia ? $model->licencia->licenciaSpec->tipo: null,
            'lic' => $lic,
            'lic_especs'=>$lic_especs
        ]);
    }

    /**
     * Creates a new Salon model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Salon();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
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


    public function actionMigrate(){
        $db = Yii::$app->db;
        $db->createCommand('UPDATE salon SET thumbnail="1.jpg" WHERE id=4')->execute();
        $db->createCommand('UPDATE salon SET thumbnail="2.jpg" WHERE id=2')->execute();
        $db->createCommand('UPDATE salon SET thumbnail="3.jpg" WHERE id=3')->execute();

        $db->createCommand('UPDATE categoria SET thumbnail="facebook-32.png" WHERE id=1')->execute();
        $db->createCommand('UPDATE categoria SET thumbnail="twitter-32.png" WHERE id=2')->execute();
        echo "OK";
    }

    public function actionEliminarLicencia($salonid) {
        $salon = $this->findModel($salonid);
        //$salon->licencia->delete();
        $lic = $salon->licencia;
        $lic->estado = Licencia::ESTADO_INACTIVO;
        $lic->detalles = 'Cancelado por el administrador';
        $h_inicio = date_create();
        $lic->fecha_fin = date_format($h_inicio, 'Y-m-d H:i');
        $lic->save();
        $salon->licenciaid = null;
        $salon->save();
        SalonHelper::actualizarEstadoSalon($salonid = $salonid);
        return $this->redirect(['index']);
    }


    public function actionAddLicenciaAdmin(){

        $model = new LicenciaAdminForm();
        $salon = $this->findModel(Yii::$app->request->post('salonid'));
        if ($model->load(Yii::$app->request->post())){


            $model_spec = LicenciaSpec::findOne($model->licencia_specid);
            if (!$model_spec)
                throw new NotFoundHttpException('No existe el tipo de licencia especificado');


            $tipo_duracion = $model_spec->tipo_duracion;
            $lic = new Licencia();
            $h_inicio = date_create();
            $lic->fecha_inicio = date_format($h_inicio, 'Y-m-d H:i');
            if ($tipo_duracion == LicenciaSpec::TIPO_DURACION_DIA)
                $tipo_duracion_str = 'days';
            elseif ($tipo_duracion == LicenciaSpec::TIPO_DURACION_MES)
                $tipo_duracion_str = 'months';
            elseif ($tipo_duracion == LicenciaSpec::TIPO_DURACION_ANNO)
                $tipo_duracion_str = 'years';

            date_add($h_inicio, date_interval_create_from_date_string($model_spec->duracion . ' ' . $tipo_duracion_str));

            if ($model_spec->tipo == Enum::TIPO_LICENCIA_ONEPAY )
                $lic->fecha_fin = date_format($h_inicio, 'Y-m-d H:i');

            $lic->licencia_specid = $model->licencia_specid;
            $lic->detalles = $model->detalles;
            $lic->estado = $model->estado;
            $lic->usuario = $model->usuario_id;
            if($lic->save(false))
                $salon->link('licencia', $lic);

        }
        return $this->redirect(['view', 'id' => $salon->id]);

    }
}
