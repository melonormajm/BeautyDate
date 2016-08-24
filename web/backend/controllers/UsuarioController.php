<?php

namespace backend\controllers;

use common\models\User;
use Yii;
use backend\models\Usuario;
use backend\models\ClientesSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UsuarioController implements the CRUD actions for Usuario model.
 */
class UsuarioController extends Controller
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
     * Lists all Usuario models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Usuario::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Usuario model.
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
     * Creates a new Usuario model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Usuario();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Usuario model.
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
     * Deletes an existing Usuario model.
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
     * Finds the Usuario model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Usuario the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Usuario::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionClientes(){
        $searchModel = new ClientesSearch();
        //$searchModel->first_name = Yii::$app->request->get('first_name');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('clientes', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionClienteview($id){
        $user = User::find()->with('userRedsocials')->where(['id'=>$id])->asArray()->one();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $user;

    }

    public function actionEditar($id){

        $userid = $id;
        $model = User::find()->where(['id'=>$userid])
            ->andWhere(['user_type' => User::TIPO_SISTEMA])
            ->one();

        if (!$model)
            throw new NotFoundHttpException('The requested page does not exist.');

        if(Yii::$app->request->post()) {


            if(isset(Yii::$app->request->post()['User']['password'])){
                $model->setPassword(Yii::$app->request->post()['User']['password']);
            }else{
                $model->first_name = Yii::$app->request->post()['User']['first_name'];
                $model->last_name = Yii::$app->request->post()['User']['last_name'];
                $model->email = Yii::$app->request->post()['User']['email'];
            }
            $model->save();
        }
        return $this->render('edit',[
            'model' => $model
        ]);
    }
}
