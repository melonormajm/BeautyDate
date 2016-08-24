<?php

namespace frontend\controllers;

use Yii;
use common\models\Servicio;
use common\models\Categoria;
use app\models\ServicioSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Salon;
use common\models\SillonServicio;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use yii\data\ActiveDataProvider;
/**
 * ServicioController implements the CRUD actions for Servicio model.
 */
class ServicioController extends Controller
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
     * Lists all Servicio models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ServicioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Servicio model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionServices(){

        $userid = Yii::$app->user->getId();
        $model = Salon::find()->where(['usuarioid'=>$userid])->one();

        if($model == null){
            $message = $this->actionMessage('error','Debe configurar su salón antes de poder agregarle servicios.');
            return $this->render('error',[
                'message'=>$message,
            ]);
        }
        //$query = Servicio::find()->with('sillonServicios')->where(['servicio.salonid'=>$model->id]);
        $query = Servicio::find()->with(['sillonServicios'=>
            function ($query) {
                $query->andWhere(['estado' => '1']);
            }
        ])->where(['servicio.salonid'=>$model->id]);
        $dataprovider = new ActiveDataProvider([
            'query' => $query,//$model->getServicios(),
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        $servicio = new Servicio();
        $categorias = Categoria::find()->all();
        $categorias = \yii\helpers\ArrayHelper::map($categorias,'id','nombre');

        return $this->render('services', [
            'model' => $model,
            'servicio' => $servicio,
            'dataprovider' => $dataprovider,
            'categorias' => $categorias,
        ]);
    }

    /**
     * Creates a new Servicio model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionExisteservicio($nombre,$salonid){
        if(Servicio::find()->where(['nombre'=>$nombre,'salonid'=>$salonid])->count()>=1)
            return true;
        return false;
    }

    public function actionCreate()
    {
        $model = new Servicio();
        $userid = Yii::$app->user->getId();
        $salon = Salon::find()->where(['usuarioid'=>$userid])->one();
        $sillones = $salon->getSillons()->all();
        $array = Yii::$app->request->post();

        if ($model->load(Yii::$app->request->post())) {
            if($this->actionExisteservicio($model->nombre,$salon->id)){
                $message = $this->actionMessage('error','Ya existe un servicio de igual nombre.');
            }
            else{
                $model->salonid = $salon->id;
                $model->save();
                if(count($array["sillones_list"])>0){
                    foreach($array["sillones_list"] as $sillon){
                        $siser = new SillonServicio();
                        $siser->sillonid = $sillon;
                        $siser->servicioid = $model->id;
                        $siser->save();
                    }
                }
                $message = $this->actionMessage('success','Servicio agregado exitosamente.');
            }
        }
        if($model->getSillonServicios()->count()>0){
            foreach($model->getSillonServicios()->all() as $model_serv){
                $selected[]=$model_serv->sillonid;
            }
        }
        return $this->render('create', [
            'model' => $model,
            'sillones' => $sillones,
            'selected' => $selected,
            'message' => $message,
        ]);
    }

    public function actionAjaxcreate()
    {
        $model = new Servicio();
        $userid = Yii::$app->user->getId();
        $salon = Salon::find()->where(['usuarioid'=>$userid])->one();

        if ($model->load(Yii::$app->request->post())) {

            if($this->actionExisteservicio($model->nombre,$salon->id)){
                echo $this->actionMessage('error','Ya existe un servicio de igual nombre.');
                Yii::$app->end();
            }
            else{
                $model->salonid = $salon->id;
                //$model->imagen = $this->uploadImage($model,NULL);
                if($model->save()){
                    $result['result'] = 'success';
                    $result['data'] = $this->actionMessage('success','Servicio agregado exitosamente.');
                    echo json_encode($result);

                }else{
                    $result['result'] = 'error';
                    $result['data'] = $this->actionMessage('error',$model->errors);
                    echo json_encode($result);
                }
                Yii::$app->end();
            }
            //return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
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

    /**
     * Updates an existing Servicio model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionAjaxupdate($id){
        $model = $this->findModel($id);
        $userid = Yii::$app->user->getId();
        $salon = Salon::find()->where(['usuarioid'=>$userid])->one();
        $imagen = $model->imagen;
        if ($model->load(Yii::$app->request->post())) {
            $model->imagen = $this->uploadImage($model,$imagen);

            if($model->save()){
                $result['result'] = 'success';
                $result['data'] = $this->actionMessage('success','Servicio agregado exitosamente.');
                echo json_encode($result);

            }else{
                $result['result'] = 'error';
                $result['data'] = $this->actionMessage('error',$model->errors);
                echo json_encode($result);
            }
        }else{
            $result['result'] = 'error';
            $result['data'] = $this->actionMessage('error','El Servicio no se ha podido modificar.');
            echo json_encode($result);
        }
        Yii::$app->end();
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $userid = Yii::$app->user->getId();
        $salon = Salon::find()->where(['usuarioid'=>$userid])->one();
        $sillones = $salon->getSillons()->all();
        $array = Yii::$app->request->post();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if(count($array["sillones_list"])>0){
                foreach($array["sillones_list"] as $sillon){
                    $siser = new SillonServicio();
                    $siser->sillonid = $sillon;
                    $siser->servicioid = $model->id;
                    $siser->save();
                }
            }
            $message = $this->actionMessage('success','Servicio modificado exitosamente.');
            //return $this->redirect(['view', 'id' => $model->id]);
        } else {
            //$message = $this->actionMessage('error','No se ha podido modificar el servicio.');
        }

        if($model->getSillonServicios()->count()>0){
            foreach($model->getSillonServicios()->all() as $model_serv){
                $selected[]=$model_serv->sillonid;
            }
        }

        return $this->render('update', [
            'model' => $model,
            'sillones' => $sillones,
            'selected' => $selected,
            'message'=>$message,
        ]);
    }

    public function actionEdit($id){
        $model = $this->findModel($id);
        $model->horario_inicio = $this->formatTime($model->horario_inicio,'g:i:s A');
        $model->horario_fin = $this->formatTime($model->horario_fin,'g:i:s A');
        $arr = ArrayHelper::toArray($model);
        echo Json::encode($arr);
        Yii::$app->end();
    }

    public function formatTime($hora,$format){
        $hora = date_create('2000-01-01 ' . $hora);
        $hora = date_format($hora, $format);
        return $hora;
    }

    public function uploadImage($model,$url){

        $model->imagen = UploadedFile::getInstance($model, 'imagen');
        if($model->imagen != NULL) {
            $file_uploaded = 'uploads/'. $model->imagen->baseName . '.' . $model->imagen->extension;
            $model->imagen->saveAs($file_uploaded);
            $model->imagen = $file_uploaded;
            return $model->imagen;
        }
        return $model->imagen = $url;
    }

    /**
     * Deletes an existing Servicio model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['services']);
    }

    public function actionAjaxdelete($id)
    {
        $servicio = $this->findModel($id);

        if($servicio != NULL && !count($servicio->sillonServicios) > 0){
            $servicio->delete();
            echo $this->actionMessage('success','Servicio eliminado exitosamente.');
        }else{
            echo $this->actionMessage('error','El servicio no se pudo eliminar por que tiene sillones asociados.');
        }
        Yii::$app->end();
    }

    /**
     * Finds the Servicio model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Servicio the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Servicio::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
