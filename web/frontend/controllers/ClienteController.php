<?php

namespace frontend\controllers;


use common\models\Sillon;
use Yii;
use frontend\models\ClienteSearch;
use common\models\Salon;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


/**
 * SalonController implements the CRUD actions for Salon model.
 */
class ClienteController extends Controller
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
        $userid = Yii::$app->user->getId();
        $salon = Salon::find()->where(['usuarioid'=>$userid])->one();
        $message = '';

        if($salon == null){
            $message = $this->actionMessage('error','Debe configurar su salón antes de poder atender clientes.');
            return $this->render('error',[
                'message'=>$message,
            ]);
        }

        $searchModel = new ClienteSearch();
        $searchModel->first_name = Yii::$app->request->get('first_name');
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

    protected function findModel($id)
    {
        if (($model = Salon::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
