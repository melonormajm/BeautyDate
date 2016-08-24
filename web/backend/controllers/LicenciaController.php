<?php

namespace backend\controllers;

use backend\models\LicenciaSearch;
use common\helper\SalonHelper;
use common\models\Enum;
use common\models\LicenciaSpec;
use common\models\Salon;
use Yii;
use common\models\Licencia;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LicenciaController implements the CRUD actions for Licencia model.
 */
class LicenciaController extends Controller
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
     * Lists all Licencia models.
     * @return mixed
     */
    public function actionIndex()
    {
        /*$dataProvider = new ActiveDataProvider([
            'query' => Licencia::find()->where(['estado' => Licencia::ESTADO_INACTIVO]),
            'pagination' => [
                'pageSize' => 5,
            ],
            'sort' => ['attributes' => ['estado', 'licenciaSpec.nombre', 'Duracion', 'Salon', 'Propietario']]
        ]);*/
        $licenciaSearch = new LicenciaSearch();
        $dataProvider = $licenciaSearch->search(Yii::$app->request->queryParams);



        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'=> $licenciaSearch,
        ]);
    }

    /**
     * Displays a single Licencia model.
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
     * Creates a new Licencia model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Licencia();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Licencia model.
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
     * Deletes an existing Licencia model.
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
     * Finds the Licencia model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Licencia the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Licencia::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionConfirm($id) {
        $model = $this->findModel($id);

        $model->estado = Licencia::ESTADO_ACTIVO;
        $url = ['licencia/index'];
        if (!$model->save())
            $url['error'] = 1;

        return $this->redirect($url);
    }

    public function actionEspecificaciones(){
        $model = LicenciaSpec::find()
            ->where(['estado' => Enum::ESTADO_ACTIVO, 'tipo' => Enum::TIPO_LICENCIA_ONEPAY])
            ->all();
        if($model == null){
            $this->render('error',[
                'message'=> $this->actionMessage('error','No existen especificaciones de licencia de tipo "UN PAGO".')
            ]);return;
        }

        $s = Salon::find()->where(['is', 'licenciaid', null])->all();

        return $this->render('especificaciones',[
            'model' => $model,
            'salones' => $s,
        ]);
    }

    public function actionCrear()
    {

        $model_spec = LicenciaSpec::findOne($_POST['lic-spec']);
        if (!$model_spec)
            throw new NotFoundHttpException('No existe el tipo de licencia especificado');

        $model_salon = Salon::findOne($_POST['salon']);
        if (!$model_salon)
            throw new NotFoundHttpException('No existe el salon especificado');

        $tipo_duracion = $model_spec->tipo_duracion;

        $model = new Licencia();
        $model->estado = Licencia::ESTADO_ACTIVO;
        $model->licencia_specid = $model_spec->id;

        $h_inicio = date_create();
        $model->fecha_inicio = date_format($h_inicio, 'Y-m-d H:i');
        if ($tipo_duracion == LicenciaSpec::TIPO_DURACION_DIA)
            $tipo_duracion_str = 'days';
        elseif ($tipo_duracion == LicenciaSpec::TIPO_DURACION_MES)
            $tipo_duracion_str = 'months';
        elseif ($tipo_duracion == LicenciaSpec::TIPO_DURACION_ANNO)
            $tipo_duracion_str = 'years';

        date_add($h_inicio, date_interval_create_from_date_string($model_spec->duracion . ' ' . $tipo_duracion_str));
        $model->fecha_fin = date_format($h_inicio, 'Y-m-d H:i');

        if ($model->save(false)) {
            $model_salon->link('licencia', $model);            
			//YA NO HACE FALTA. SE HACE EN LOS AFTER SAVE
            //SalonHelper::actualizarEstadoSalon($model_salon->id);
			//SalonHelper::actualizarEstadoSalon($salonid = $model_salon->id);
            return $this->redirect(['index']);
        } else {
            return $this->redirect(['otro']);
        }
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

}
