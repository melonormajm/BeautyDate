<?php
namespace backend\controllers;

use common\models\Reservacion;
use common\models\Salon;
use common\models\Servicio;
use common\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'test'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $cantSalones = Salon::find()
            ->select('count(*) as salonCount')
            ->scalar();

        $cantReservaciones = Reservacion::find()
            ->select('count(*) as reservCount')
            ->where(['estado' => Reservacion::ESTADO_EJECUTADA])
            ->scalar();

        $cantServicios = Servicio::find()
            ->select('count(*) as serviciosCount')
            ->where(['estado' => Servicio::ESTADO_ACTIVO, 'estado_sistema' => Servicio::ESTADO_ACTIVO])
            ->scalar();

        return $this->render('index',
            ['cantSalones' => $cantSalones,
            'cantReservaciones' => $cantReservaciones,
            'cantServicios' => $cantServicios]);
    }

    public function actionLogin()
    {
        $this->layout = 'login';
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $invalid_credentials = false;

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->login(User::TIPO_SISTEMA)) {
                return $this->goBack();
            }
            else
                $invalid_credentials = true;
        }

        return $this->render('login', [
            'model' => $model,
            'invalid_cred' => $invalid_credentials
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public  function actionTest() {

        $dt = date_create_from_format('d-m-Y H:i', '23-12-2015 13:30');
        $ds = date_create(strtotime('23-12-2015 13:30'));
        $r = Yii::$app->formatter->asDatetime('23-12-2015 13:30');
        echo $r;
        die("<br>todo bien");
    }


}
