<?php

namespace frontend\controllers;

use common\models\Servicio;
use common\models\Salon;
use Yii;
use common\models\Promocion;
use app\models\PromocionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * PromocionController implements the CRUD actions for Promocion model.
 */
class PromocionController extends Controller
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
     * Lists all Promocion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $userid = Yii::$app->user->getId();
        //Optimizar para no tener que hacer tantas consultas
        $salon = Salon::find()->where(['usuarioid'=>$userid])->one();

        if($salon == null){
            $message = $this->actionMessage('error','Debe configurar su salón antes de poder crear promociones.');
            return $this->render('error',[
                'message'=>$message,
            ]);
        }

        //$searchModel = new PromocionSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $query = Promocion::find()->innerJoinWith(['servicio'])
            ->innerJoinWith('servicio.salon')
            ->where(['salon.usuarioid'=>$userid]);
        //echo '<pre/>';
        //echo print_r($query);die;

        //echo '<pre />';
        //echo print_r($dataProvider);die;
        return $this->render('index', [
            //'searchModel' => $searchModel,
            'query' => $query,
        ]);
    }

    /**
     * Displays a single Promocion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
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

    public function formatDate($fecha){
        $date = new \DateTime($fecha);
        return $date->format('Y-m-d');
    }

    /**
     * Creates a new Promocion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $userid = Yii::$app->user->getId();
        $salon = Salon::find()->where(['usuarioid'=>$userid])->one();
        $servicios = Servicio::find()->where(['salonid'=>$salon->id])->all();
        $model = new Promocion();

        $imagen = $model->imagen;
        if ($model->load(Yii::$app->request->post())) {
            //$model->imagen = $this->uploadImage($model,$imagen);
            $model->fecha_inicio = $this->formatDate($model->fecha_inicio);
            $model->fecha_fin = $this->formatDate($model->fecha_fin);
            if($model->save()){
                $message = $this->actionMessage('success','Promoción creada exitosamente.');
            }else{
                //print_r($model->getErrors());die;
                $message = $this->actionMessage('error','No se ha podido crear la promoción.');
            }
            $query = Promocion::find()->innerJoinWith(['servicio'])
                ->innerJoinWith('servicio.salon')
                ->where(['salon.usuarioid'=>$userid]);

            return $this->render('index',[
                'message'=>$message,
                'query'=>$query
            ]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'servicios' => $servicios,
            ]);
        }
    }

    /**
     * Updates an existing Promocion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $userid = Yii::$app->user->getId();
        $salon = Salon::find()->where(['usuarioid'=>$userid])->one();
        $servicios = Servicio::find()->where(['salonid'=>$salon->id])->all();

        $imagen = $model->imagen;

        if ($model->load(Yii::$app->request->post())) {
            //$model->imagen = $this->uploadImage($model,$imagen);

            $model->fecha_inicio = $this->formatDate($model->fecha_inicio);
            $model->fecha_fin = $this->formatDate($model->fecha_fin);

            if($model->save()){
                $message = $this->actionMessage('success','Promoción modificada exitosamente.');
            }else{
                //print_r($model->getErrors());die;
                $message = $this->actionMessage('error','No se ha podido modificar la promoción.');
            }
            $query = Promocion::find()->innerJoinWith(['servicio'])
                ->innerJoinWith('servicio.salon')
                ->where(['salon.usuarioid'=>$userid]);

            return $this->render('index', [
                'model' => $model,
                'servicios'=> $servicios,
                'message'=>$message,
                'query'=>$query
            ]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'servicios'=> $servicios,
            ]);
        }
    }

    /**
     * Deletes an existing Promocion model.
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
     * Finds the Promocion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Promocion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Promocion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    private function uploadImage($model,$imagen){
        $model->imagen = UploadedFile::getInstance($model, 'imagen');
        if($model->imagen != NULL) {
            $file_uploaded = 'uploads/promocion/'. $model->imagen->baseName . '.' . $model->imagen->extension;
            $model->imagen->saveAs($file_uploaded);
            $model->imagen = $file_uploaded;
            return $model->imagen;
        }
        return $model->imagen = $imagen;
    }
}
