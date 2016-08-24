<?php

namespace frontend\controllers;

use Yii;
use common\models\Categoria;
use app\models\CategoriaSalon;
use app\models\CategoriaSalonSearch;
use common\models\Salon;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * CategoriaSalonController implements the CRUD actions for CategoriaSalon model.
 */
class CategoriasalonController extends Controller
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
     * Lists all CategoriaSalon models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategoriaSalonSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CategoriaSalon model.
     * @param integer $categoriaid
     * @param integer $salonid
     * @return mixed
     */
    public function actionView($categoriaid, $salonid)
    {
        return $this->render('view', [
            'model' => $this->findModel($categoriaid, $salonid),
        ]);
    }

    /**
     * Creates a new CategoriaSalon model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CategoriaSalon();
        $dataList=ArrayHelper::map(Categoria::find()->asArray()->all(), 'id', 'nombre');
        $userid = Yii::$app->user->getId();
        $salon = Salon::find()->where(['usuarioid'=>$userid])->one();
        $array = Yii::$app->request->post("categorias_list");

        if ($model->load(Yii::$app->request->post())) {
            $model->salonid = $salon->id;
            if(CategoriaSalon::find()->where(['categoriaid'=>$model->categoriaid,'salonid'=>$salon->id])->count() >= 1){
                echo $this->actionMessage('error','Esa categoría ya existe en este salón');
                /*return $this->render('create', [
                    'model' => $model,
                    'dataList' => $dataList,
                    'message' => $message,
                ]);*/
            }else{
                $model->save();
                echo $this->actionMessage('success','Categoria agregada exitosamente');
                //return $this->redirect(array('salon/manage'));
            }
        }/* else {
            return $this->render('create', [
                'model' => $model,
                'dataList' => $dataList,
            ]);
        }*/

    }

    public function deleteCategorias($salon){
        foreach($salon->categoriaSalons as $cat){
            $cat->delete();
        }
    }

    public function hasSalon($salones,$id){
        foreach($salones as $sal){
            if($sal->salonid == $id){
                return true;
            }
        }
        return false;
    }

    public function actionCategorysalon($message=""){
        $userid = Yii::$app->user->getId();
        $model = Salon::find()->where(['usuarioid'=>$userid])->one();

        if($model == null){
            return $this->render('error',[
                'message'=> $this->actionMessage('error','Antes de seleccionar las categorías debe configurar su salón.')
            ]);
        }
        //$categorysalon = new CategoriaSalon();
        //$categoria = new Categoria();
        //$dataList=ArrayHelper::map(Categoria::find()->asArray()->all(), 'id', 'nombre');
        $categorias = Categoria::find()->all();

        if(Yii::$app->request->post()){
            if(count($model->categoriaSalons)>0){
                $this->deleteCategorias($model);
            }
            $array = Yii::$app->request->post("categorias_list");
            if(count($array)>0){
                foreach($array as $id){
                    $catsill = new CategoriaSalon();
                    $catsill->salonid = $model->id;
                    $catsill->categoriaid = $id;
                    $catsill->save();
                }
            }
            $message = $this->actionMessage('success','Categorías agregadas a su salón.');
        }

        //$all = Categoria::find()->with('categoriaSalons')->asArray()->all();


        foreach($categorias as $cat){
            if(!$this->hasSalon($cat->categoriaSalons,$model->id)){
                $all[] = $cat;
            }
        }
        /*echo '<pre>';
        echo print_r($categorias);die;*/


        return $this->render('category_salon', [
            'model' => $model,
            //'categorysalon' => $categorysalon,
            //'categoria' => $categoria,
            //'dataList' => $dataList,
            'message' => $message,
            'categorias' => $all,
            'selected' => $model->categorias,
        ]);
    }

    public function actionDeletecat($id){
        $userid = Yii::$app->user->getId();
        $salon = Salon::find()->where(['usuarioid'=>$userid])->one();
        $cat_sal = CategoriaSalon::find()->where(['categoriaid'=>$id,'salonid'=>$salon->id])->one();
        if($cat_sal){
            $cat_sal->delete();
            $message = $this->actionMessage('success','Categoria agregada exitosamente');
        }
        return $this->actionCategorysalon($message);
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

    public function actionSave()
    {
        $model = new CategoriaSalon();
        $userid = Yii::$app->user->getId();
        $salon = Salon::find()->where(['usuarioid'=>$userid])->one();

        if ($model->load(Yii::$app->request->post())) {
            $model->salonid = $salon->id;
            if(CategoriaSalon::find()->where(['categoriaid'=>$model->categoriaid,'salonid'=>$salon->id])->count() >= 1){
                $message = "Esa categoría ya existe en este salón";
                return $this->redirect(array('salon/manage'));
            }else{
                $model->save();
                return $this->redirect(array('salon/manage'));
            }
        } else {
            return $this->redirect(array('salon/manage'));
        }
    }

    /**
     * Updates an existing CategoriaSalon model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $categoriaid
     * @param integer $salonid
     * @return mixed
     */
    public function actionUpdate($categoriaid, $salonid)
    {
        $model = $this->findModel($categoriaid, $salonid);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'categoriaid' => $model->categoriaid, 'salonid' => $model->salonid]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CategoriaSalon model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $categoriaid
     * @param integer $salonid
     * @return mixed
     */
    public function actionDelete($categoriaid, $salonid)
    {
        $this->findModel($categoriaid, $salonid)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CategoriaSalon model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $categoriaid
     * @param integer $salonid
     * @return CategoriaSalon the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($categoriaid, $salonid)
    {
        if (($model = CategoriaSalon::findOne(['categoriaid' => $categoriaid, 'salonid' => $salonid])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
