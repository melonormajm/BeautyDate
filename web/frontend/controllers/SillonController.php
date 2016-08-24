<?php

namespace frontend\controllers;

use common\helper\SalonHelper;
use common\models\SillonServicio;
use Yii;
use common\models\Sillon;
use common\models\Servicio;
use app\models\SillonSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Salon;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * SillonController implements the CRUD actions for Sillon model.
 */
class SillonController extends Controller
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

    public function actionMessage($type,$message, $array = []){
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
                foreach($array as $r){
                    $message .= '<br />'.$r;
                }
                $class = 'alert-danger';
                break;
        }
        return '<div class="alert '.$class.' alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
        </button><strong>'.'</strong>'.$message.'</div>';
    }

    public function salonReady(){
        $userid = Yii::$app->user->getId();
        $salon = Salon::find()->where(['usuarioid'=>$userid])->one();
        $sillones = Sillon::find()->with('sillonServicios')
            ->where(['sillon.salonid'=>$salon->id])->all();
        /*echo '<pre>';
        echo print_r($sillones);die();*/
        foreach($sillones as $sillon){
            if($sillon->estado == Sillon::ESTADO_ACTIVO && count($sillon->sillonServicios)>0){
                return true;
            }
        }
        if($salon->estado_sistema == Salon::ESTADO_SISTEMA_ACTIVADO){
            $salon->estado_sistema = Salon::ESTADO_SISTEMA_INACTIVO;
            $salon->save();
        }
        return false;
    }

    /**
     * Lists all Sillon models.
     * @return mixed
     */
    public function actionIndex()
    {
        $userid = Yii::$app->user->getId();
        $salon = Salon::find()->where(['usuarioid'=>$userid])->one();
        if($salon == NULL){
            $message = $this->actionMessage('error','Debe crear un salón antes de poder agregarle sillones.');
            return $this->render('error',[
                'message'=>$message,
            ]);
        }
        $servicios = $salon->getServicios()->all();
        if($servicios == NULL){
            $message = $this->actionMessage('error','Debe crear servicios antes de poder adicionar sillones.');
            return $this->render('error',[
                'message'=>$message,
            ]);
        }
        $this->salonReady();
        $dataProvider = Sillon::find()->where(['salonid'=>$salon->id]);
        $model = new Sillon();
        return $this->render('index',[
            'dataProvider'=>$dataProvider,
            'model' => $model,
            'servicios' => $servicios,
        ]);

        /*$searchModel = new SillonSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);*/
    }

    /**
     * Displays a single Sillon model.
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
     * Creates a new Sillon model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */


    public function actionCreate()
    {
        $model = new Sillon();
        $userid = Yii::$app->user->getId();
        $salon = Salon::find()->where(['usuarioid'=>$userid])->one();
        $servicios = $salon->getServicios()->all();
        $array = Yii::$app->request->post();

        if ($model->load(Yii::$app->request->post())) {
            $model->salonid = $salon->id;
            if($model->save()){
                if(count($array["servicios_list"])>0){
                    foreach($array["servicios_list"] as $servicio){
                        $siser = new SillonServicio();
                        $siser->sillonid = $model->id;
                        $siser->servicioid = $servicio;
                        $siser->save();
                        $serv = Servicio::find()->where(['id'=>$servicio])->one();
                        if($serv->estado_sistema == Servicio::ESTADO_SISTEMA_INACTIVO){
                            $serv->estado_sistema = Servicio::ESTADO_ACTIVO;
                            $serv->save();
                        }
                    }
                }
                $this->salonReady();
                echo $this->actionMessage('success','Sillón agregado exitosamente.');
            }else{
                echo $this->actionMessage('error','No se ha podido crear el sillón.');
            }

        }else{
            echo $this->actionMessage('error','No se ha podido crear el sillón.');
        }
        /* else {
           /* return $this->render('create', [
                'model' => $model,
            ]);
        }
        if($model->getSillonServicios()->count()>0){
            foreach($model->getSillonServicios()->all() as $model_serv){
                $selected[]=$model_serv->servicioid;
            }
        }
        return $this->render('create', [
            'model' => $model,
            'servicios' => $servicios,
            'selected' => $selected,
            'message' => $message,
        ]);*/
    }

    public function actionSavesillon(){
        $model = new Sillon();
        $userid = Yii::$app->user->getId();
        $salon = Salon::find()->where(['usuarioid'=>$userid])->one();

        if ($model->load(Yii::$app->request->post())) {
            $model->salonid = $salon->id;
            $model->save();
            echo 'success';
            //return $this->redirect(array('salon/manage'));
            //return $this->redirect(['view', 'id' => $model->id]);
        } else {
            echo 'error';
            //return $this->redirect(array('salon/manage'));
        }
    }

    /**
     * Updates an existing Sillon model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $array = Yii::$app->request->post();
        $userid = Yii::$app->user->getId();
        $salon = Salon::find()->where(['usuarioid'=>$userid])->one();
        $servicios = Servicio::find()->where(['salonid'=>$salon->id])->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if(count($array["servicios_list"])>0){

                foreach($array["servicios_list"] as $service){
                    $siser = new SillonServicio();
                    $siser->sillonid = $model->id;
                    $siser->servicioid = $service;
                    $siser->save();
                }
            }
            if($model->getSillonServicios()->count()>0){
                foreach($model->getSillonServicios()->all() as $model_serv){
                    $selected[]=$model_serv->servicioid;
                }
            }

            return $this->render('update', [
                'model' => $model,
                'selected' => $selected,
                'servicios' => $servicios,
            ]);

        } else {
            if($model->getSillonServicios()->count()>0){
                foreach($model->getSillonServicios()->all() as $model_serv){
                    $selected[]=$model_serv->servicioid;
                }
            }

            return $this->render('update', [
                'model' => $model,
                'selected' => $selected,
                'servicios' => $servicios,
            ]);
        }
    }

    public function actionAjaxupdateOld($id)
    {
        $model = $this->findModel($id);
        $array = Yii::$app->request->post();
        $userid = Yii::$app->user->getId();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $selected = isset($array["servicios_list"])? $array["servicios_list"] : null;
                /*$errors = $this->deleteServicios($model,$selected);
                if(count($errors)>0){
                    echo $this->actionMessage('error','Los siguientes servicios no se han podido eliminar por tener reservaciones.',$errors);
                }*/



                if($selected){
                    foreach($selected as $id){
                        $serv = Servicio::find()->where(['id'=>$id])->one();
                        if($serv->estado_sistema == Servicio::ESTADO_SISTEMA_INACTIVO){
                            $serv->estado_sistema = Servicio::ESTADO_SISTEMA_ACTIVO;
                            $serv->save();
                        }
                     }
                }
                echo $this->actionMessage('success','Sillón modificado exitosamente.');
        } else {
            echo $this->actionMessage('error','No se ha podido modificar el sillón.');
        }
        Yii::$app->end();
    }

    public function actionAjaxupdate($id)
    {
        $model = $this->findModel($id);
        $array = Yii::$app->request->post();
        $userid = Yii::$app->user->getId();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $selected = isset($array["servicios_list"])? $array["servicios_list"] : [];

            $serv_a_adicionar = $selected;
            foreach($model->sillonServicios as $ss){
                if(in_array($ss->servicioid, $selected)){
                    if(!$ss->estado){
                        $ss->estado = 1;
                        $ss->save();
                    }
                }else{
                    if($ss->estado){
                        $ss->estado = 0;
                        $ss->save();
                    }
                }
                if(array_search(strval($ss->servicioid), $serv_a_adicionar) >= 0){
                    unset($serv_a_adicionar[array_search($ss->servicioid, $serv_a_adicionar)]);
                };
            }

            foreach($serv_a_adicionar as $sa){
                $ssn = new SillonServicio();
                $ssn->servicioid = $sa;
                $ssn->sillonid = $model->id;
                $ssn->estado = 1;
                $ssn->save(false);
            }

            SalonHelper::checkServiciosEstados($model->salonid);
            /*if($selected){
                foreach($selected as $id){
                    $serv = Servicio::find()->where(['id'=>$id])->one();
                    if($serv->estado_sistema == Servicio::ESTADO_SISTEMA_INACTIVO){
                        $serv->estado_sistema = Servicio::ESTADO_SISTEMA_ACTIVO;
                        $serv->save();
                    }
                }
            }
            }*/
            echo $this->actionMessage('success','Sillón modificado exitosamente.');
        } else {
            echo $this->actionMessage('error','No se ha podido modificar el sillón.');
        }
        Yii::$app->end();
    }

    public function deleteServicios($model,$new){
        $servicios = $model->getSillonServicios()->all();
        $mine = [];
        $add = [];
        $delete = [];

        //si no hay nada
        if($new==null && $servicios==null){
            return null;
        }
        //si llega uno y no el otro
        if($new==null && count($servicios)>0){
            foreach($servicios as $serv){
                $mine[]=$serv->servicioid;
            }
            foreach($mine as $m){
                $delete[]=$m;
            }
        }
        //si llegan ambos
        if(count($new)>0 && count($servicios)>0){
            foreach($servicios as $serv){
                $mine[]=$serv->servicioid;
            }
            foreach($mine as $m){
                if(!in_array($m,$new)){
                    $delete[]=$m;
                }
            }
            foreach($new as $n){
                if(!in_array($n,$mine)){
                    $add[]=$n;
                }
            }
            foreach($add as $a){
                $siser = new SillonServicio();
                $siser->sillonid = $model->id;
                $siser->servicioid = $a;
                $siser->save();
            }
        }
        //si llegan nuevos pero no había ninguno
        if(count($new)>0 && $servicios==null){
            foreach($new as $a){
                $siser = new SillonServicio();
                $siser->sillonid = $model->id;
                $siser->servicioid = $a;
                $siser->save();
            }
            return null;
        }


        $sillonserv = SillonServicio::find()
            ->with('reservacions')
            ->with('servicio')
            ->where(['servicioid'=>$delete, 'sillonid'=>$model->id])->all();

        $error=[];
        foreach($sillonserv as $siser){

            if(!$siser->reservacions){
                $siser->delete();
            }else{
                $error[] = $siser->servicio->nombre;
            }
        }
        //SillonServicio::deleteAll(['id'=>$delete]);
        return $error;
    }

    public function actionEdit($id){
        $model = $this->findModel($id);
        $arr = ArrayHelper::toArray($model);
        foreach($model->sillonServicios as $serv){
            if($serv->estado == 1)
                $ids[] = (string)$serv->servicioid;
        }
        //$serv = ArrayHelper::toArray($model->sillonServicios);
        $arr['servicios']=isset($ids) ? $ids : [];
        echo Json::encode($arr);
    }

    /**
     * Deletes an existing Sillon model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    /*TODO: Que hacer si el sillon a eliminar tiene reservaciones*/
    public function actionAjaxdelete($id)
    {
        $model = $this->findModel($id);

        if(count($model->sillonServicios) > 0) {
            foreach ($model->sillonServicios as $sillon) {
                if (count($sillon->reservacions)) {
                    echo $this->actionMessage('error', 'El sillón no se ha podido eliminar por tener reservaciones asociadas.');
                    return;
                }
                $sillon->delete();
            }
        }
        if($model->delete()){
            $this->salonReady();
            echo $this->actionMessage('success','El sillón ha sido eliminado correctamente.');
        }else{
            echo $this->actionMessage('error','Ha ocurrido un erro y no se ha podido eliminar el sillón.');
        }
        Yii::$app->end();

    }


    public function actionDelete($id)
    {
        $userid = Yii::$app->user->getId();
        $salon = Salon::find()->where(['usuarioid'=>$userid])->one();
        $dataProvider = Sillon::find()->where(['salonid'=>$salon->id]);
        $userid = Yii::$app->user->getId();
        $salon = Salon::find()->where(['usuarioid'=>$userid])->one();
        $servicios = $salon->getServicios()->all();

        $model = $this->findModel($id);

        if(count($model->sillonServicios) > 0){
            foreach($model->sillonServicios as $sillon){
                if(count($sillon->reservacions)){
                    $message = $this->actionMessage('error','El sillón no se puede eliminar por tener reservaciones asignadas.');
                    $model = new Sillon();
                    return $this->render('index',[
                        'dataProvider'=>$dataProvider,
                        'model' => $model,
                        'servicios' => $servicios,
                        'message' => $message,
                    ]);
                }
                $sillon->delete();
            }
            $model->delete();
            $message = $this->actionMessage('success','El sillón ha sido eliminado correctamente.');
        }else{
            $model->delete();
            $message = $this->actionMessage('success','El sillón ha sido eliminado correctamente.');
        }


        $model = new Sillon();

        return $this->render('index',[
            'dataProvider'=>$dataProvider,
            'model' => $model,
            'servicios' => $servicios,
            'message' => $message,
        ]);
        //return $this->redirect(['index']);
    }

    public function actionDeleteservicio($id){
        $salonservicio = SillonServicio::find()->where(['id'=>$id])->one();
        $model = $this->findModel($salonservicio->sillonid);
        if($salonservicio)
            $salonservicio->delete();
        return $this->render('update',['model'=>$model]);
    }

    public function actionAddserviciosillon($id){
        $userid = Yii::$app->user->getId();
        $salon = Salon::find()->where(['usuarioid'=>$userid])->one();
        $model = new SillonServicio();

        if ($model->load(Yii::$app->request->post())) {
            if(!SillonServicio::find()->where(['sillonid'=>$model->sillonid,'servicioid'=>$model->servicioid])->count()>=1) {
                $model->save();
                return $this->redirect(['sillon/update', 'id' => $model->sillonid]);
            }
            $message = "Este servicio ya fue asignado a este sillón.";
        }
        $model->sillonid = $id;
        $dataList=ArrayHelper::map(Servicio::find()->where(['salonid'=>$salon->id])->asArray()->all(), 'id', 'nombre');

        return $this->render('addServicio', [
            'model' => $model,
            'dataList' => $dataList,
            'message'=>$message,
        ]);

    }

    /**
     * Finds the Sillon model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sillon the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sillon::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
