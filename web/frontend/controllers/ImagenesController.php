<?php

namespace frontend\controllers;

use common\helper\Util;
use Yii;
use common\models\Imagenes;
use app\models\ImagenesSearch;
use common\models\Salon;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * ImagenesController implements the CRUD actions for Imagenes model.
 */
class ImagenesController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Imagenes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ImagenesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Imagenes model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function uploadImage($model,$url){
        if($model->principal)
            $this->updatePrincipal();

        $model->url = UploadedFile::getInstance($model, 'url');
        if($model->url != NULL) {
            $file_uploaded = 'uploads/'. $model->url->baseName . '.' . $model->url->extension;
            $model->url->saveAs($file_uploaded);
            $model->url = $file_uploaded;
            return $model->url;
        }

        return $model->url = $url;
    }

    /**
     * Creates a new Imagenes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Imagenes();
        $userid = Yii::$app->user->getId();
        $salon = Salon::find()->where(['usuarioid'=>$userid])->one();

        if ($model->load(Yii::$app->request->post())) {
            $model->salonid = $salon->id;
            $model->url = $this->uploadImage($model,NULL);

            if($model->save())
                echo $this->actionMessage('success','Imagen agregada exitosamente.');
                //return $this->redirect(array('salon/manage'));//pasar mensaje a vista
                //return $this->redirect(['view', 'id' => $model->id]);
        } else {
            echo 'algo paso';
            //return $this->redirect(array('salon/manage'));
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionList(){
        $userid = Yii::$app->user->getId();
        $salon = Salon::find()->where(['usuarioid'=>$userid])->one();
        $message = '';

        if($salon == null){
            $message = $this->actionMessage('error','Debe configurar su salón antes de poder agregarle imágenes.');
            return $this->render('error',[
                'message'=>$message,
            ]);
        }

        $imagenes = new Imagenes();
        $list = Imagenes::find()->where(['salonid'=>$salon->id])->all();

        if ($imagenes->load(Yii::$app->request->post())) {
            $imagenes->salonid = $salon->id;
            //$imagenes->url = $this->uploadImage($imagenes,NULL);
            if($imagenes->save()){
                $message = $this->actionMessage('success','Imagen agregada exitosamente.');
            }else {
                $message = $this->actionMessage('error','No se ha podido subir la imagen.');
            }
        }
        $imagenes = new Imagenes();//TODO: quitar esto, es para no mandar la ultima imagen.

        return $this->render('list', [
            'model' => $salon,
            'imagen' => $imagenes,
            'list'=>$list,
            'message'=>$message,
        ]);
    }

    public function actionRenderlist($message){
        $userid = Yii::$app->user->getId();
        $salon = Salon::find()->where(['usuarioid'=>$userid])->one();
        $imagenes = new Imagenes();
        $list = Imagenes::find()->where(['salonid'=>$salon->id])->all();

        return $this->render('list', [
            'model' => $salon,
            'imagen' => $imagenes,
            'list'=>$list,
            'message'=>$message,
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

    public function actionEdit($id){
        $model = $this->findModel($id);
        $arr = ArrayHelper::toArray($model);
        echo Json::encode($arr);
    }

    /**
     * Updates an existing Imagenes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $url = $model->url;
        if ($model->load(Yii::$app->request->post())) {
            $model->url = $this->uploadImage($model,$url);

            $model->save();
                return $this->redirect(array('salon/manage'));
                //return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionSetprincipal($id){
        $this->updatePrincipal();
        $imagen = $this->findModel($id);
        $imagen->principal = 1;
        $imagen->save();
        return  $this->redirect(['list']);
    }

    public function updatePrincipal(){
        $userid = Yii::$app->user->getId();
        $salon = Salon::find()->where(['usuarioid'=>$userid])->one();
        $imagenes = Imagenes::find()->where(['salonid'=>$salon->id])->all();
        //echo var_dump($imagenes);die;
        foreach($imagenes as $imagen){
            if($imagen->principal){
                $imagen->principal = 0;
            }
            $imagen->save();
        }
    }

    /**
     * Deletes an existing Imagenes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if($this->findModel($id)->delete()){
            $message = $this->actionMessage('success','Imagen eliminada exitosamente.');
        }else{
            $message = $this->actionMessage('error','La imagen no pudo ser eliminada.');
        }
        return $this->redirect(['list']);
        //return $this->actionRenderlist($message);
    }
    /**
     * Finds the Imagenes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Imagenes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Imagenes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionCtmpthumb($imgid){

        $img = Imagenes::find()->where(['id' => $imgid])->one();
        $srcimg = Yii::getAlias('@webroot').Yii::$app->params['salones_img_path'] . '/' .$img->salonid . '/' . $img->nombre;
        $destimg = Yii::getAlias('@webroot').Yii::$app->params['salones_img_path'] . '/' . $img->salonid . '/thumb_500' . $img->nombre;

        //$src = '../test/1900_1200.jpg';
        //$porcentaje = .26;
        $ext = Util::gd_extension($srcimg);
        //echo $ext;die;

        list($ancho, $alto) = getimagesize($srcimg);
        $porcentaje = 500/$ancho;
        $nuevo_ancho = $ancho * $porcentaje;
        $nuevo_alto = $alto * $porcentaje;
        $thumb = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);
        $origen= '';

        if($ext == 'jpg' or $ext == 'jpeg'){
            $origen = imagecreatefromjpeg($srcimg);
        }elseif($ext == 'png'){
            $origen = imagecreatefrompng($srcimg);
        }elseif ($ext == 'gif'){
            $origen = imagecreatefromgif($srcimg);
        }


        imagecopyresized($thumb, $origen, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho, $alto);

        if($ext == 'jpg' or $ext == 'jpeg'){
            imagejpeg($thumb,$destimg, 90);
        }elseif($ext == 'png'){
            imagepng($thumb,$destimg, 0);
        } elseif ($ext == 'gif'){
            imagegif($thumb, $destimg);
        }



        echo json_encode(['respuesta'   =>true,
                          'url'         =>Yii::$app->params['salon_img_baseurl'] . '/' . $img->salonid . '/thumb_500' . $img->nombre,
                          'path'        =>$destimg,
                          'nombre'      =>$img->nombre,
                          'salonid'     =>$img->salonid,
                          'imgid' => $img->id]);
        die;
    }

    public function actionCropimg(){


        $destimg = Yii::getAlias('@webroot').Yii::$app->params['salones_img_path'] . '/' . $_POST['salonid'] . '/thumb_' . $_POST['nombre'];

        $src= Yii::getAlias('@webroot').Yii::$app->params['salones_img_path'] . '/' . $_POST['salonid'] . '/thumb_500' . $_POST['nombre'];

        $ext = Util::gd_extension($src);
        $srcimg = '';

        if($ext == 'jpg' or $ext == 'jpeg'){
            $srcimg = imagecreatefromjpeg($src);
        }elseif($ext == 'png'){
            $srcimg = imagecreatefrompng($src);
        }elseif ($ext == 'gif'){
            $srcimg = imagecreatefromgif($src);
        }




        //$targ_w = $targ_h = 150;
        $targ_w = $targ_h = $_POST['w'];
        $jpeg_quality = 100;

        //$img_r = $thumb;
        $dst_r = ImageCreateTrueColor( $_POST['w'], $_POST['h'] );
        //$dst_r = ImageCreateTrueColor( 500, 312 );

        //imagecopyresampled($dst_r,$img_r,0,0,0,0,
        //   500,312,500,312);


        imagecopyresampled($dst_r,$srcimg,0,0,$_POST['x'],$_POST['y'],
            $targ_w,$targ_h,$_POST['w'],$_POST['h']);

        //header('Content-type: image/jpeg');


        if($ext == 'jpg' or $ext == 'jpeg'){
            imagejpeg($dst_r,$destimg, $jpeg_quality);
        }elseif($ext == 'png'){
            imagepng($dst_r,$destimg, 0);
        }elseif($ext == 'gif'){
            imagegif($dst_r,$destimg);
        }

        //imagejpeg($dst_r,null,$jpeg_quality);
        //print_r($_POST);
    }
}
