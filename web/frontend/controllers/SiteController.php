<?php
namespace frontend\controllers;


use common\helper\LicenciaHelper;
use common\helper\PayPal_Ipn;
use common\helper\SalonHelper;
use common\models\DetallesPago;
use common\models\IpnNotification;
use common\models\Licencia;
use common\models\LicenciaSpec;
use common\models\Salon;
use common\models\User;
use common\helper\ipnlistener;

use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\Exception;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */

    function init()
    {

        if (isset($_POST)) {
            // Turn off CSRF from PayPal
            Yii::$app->request->enableCsrfValidation = FALSE;
        }
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionInicio()
    {
        $this->layout = 'login';
        if(Yii::$app->user->isGuest)
            return $this->render('home');
        return $this->redirect(array('salon/home'));
    }

    public function actionIndex()
    {
        $this->layout = 'login';
        if(Yii::$app->user->isGuest)
            return $this->render('home');
        return $this->redirect(array('salon/home'));
    }

    public function actionLogin()
    {
        $this->layout = 'login';

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        $signup = new SignupForm();

        if(isset($_POST['LoginForm'])){
            $model->load(Yii::$app->request->post());
            if ( $model->login(User::TIPO_PROPIETARIO)) {
                return $this->goBack();
            }
        }

        return $this->render('login', [
            'model' => $model,
            'signup' => $signup,
        ]);

    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionSignup()
    {
        $this->layout = 'login';

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            $model->auth_type = 'LOCAL';
            $model->user_type = User::TIPO_PROPIETARIO;
            if ($model->signup()) {
                $user = $model->signup();
                return $this->render('post-signup');
                /*if (Yii::$app->user->login($user)) {
                    return $this->goHome();
                }*/
            }
        }
        //print_r($model->errors);die;
        return $this->render('signup', [
            'model' => $model,
        ]);
    }


    public function actionRequestPasswordReset()
    {
        $this->layout = 'login';
        $model = new PasswordResetRequestForm();
        $model->user_type = User::TIPO_PROPIETARIO;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                return $this->redirect(['site/check-your-mail']);
            } else {
                Yii::$app->getSession()->setFlash('error', 'Lo sentimos, no se ha podido cambiar la contraseña' .
                    ' para el correo especificado.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token, $type = '')
    {
        $this->layout = ($type == 'app') ? 'app' : 'login';
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            return $this->redirect(['site/chg-pass-success', 'type' => $type]);
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionPaymentDone($tx ='') {
        $result_code = true;
        //echo '<pre>'; print_r($_REQUEST);die;
        //Yii::info($_POST, 'paypal');

      /*  $params = array (
            'cmd' => '_notify-synch',
            'tx' => $tx,
            'at' => Yii::$app->params['pdt_token'],
        );
        $content = http_build_query ($params);

        $context = stream_context_create (array (
            'http' => array (
                'method' => 'POST',
                'content' => $content,
            )
        ));
        //'https://www.paypal.com/cgi-bin/webscr'
        $result = file_get_contents('https://www.sandbox.paypal.com/cgi-bin/webscr', null, $context);
        //$result = file_get_contents('https://www.paypal.com/cgi-bin/webscr', null, $context);
        $result = urldecode($result);
        Yii::info($result, 'paypal');
        $resultArr = explode(PHP_EOL, $result);
        $result = [];
        foreach ($resultArr as $v) {
            $tmp = explode('=', $v, 2);
            if ($tmp[0] == 'SUCCESS' || $tmp[0] == 'FAIL')
                $result['result'] = $tmp[0];
            else
                $result[$tmp[0]] = $tmp[1];
        }*/
/*
        $result = [
            'result' => 'SUCCESS',
            'mc_gross' => '5.00',
            'payment_status' => 'Completed',
            'mc_currency' => 'USD',
            'payer_email' => 'aleruiz-buyer@softok2.com',
            'receiver_email' => 'aleruiz-facilitator@softok2.com',
            'txn_id' => '19U25696PJ956294N',
            'custom' => '4',
        ];*/
        //echo '<pre>'; print_r($result);
        /*$transaction = \Yii::$app->db->beginTransaction();
        try {
            $model = new DetallesPago();
            $model->resultado = $result['result'];
            $model->costo = $result['mc_gross'];
            $model->estado_pago = $result['payment_status'];
            $model->moneda = $result['mc_currency'];
            $model->cliente = $result['payer_email'];
            $model->vendedor = $result['receiver_email'];
            $model->transaccion = $result['txn_id'];

            $salon = Salon::find()
                ->innerJoinWith(['usuario' => function ($query) {
                    $query->andWhere(['user.id' => Yii::$app->user->id]);
                }])->one();

            $licencia = !$salon->licencia ? new Licencia() : $salon->licencia;

            $licSpec = LicenciaSpec::findOne($result['custom']);
            $duracion = $licSpec->duracion;
            $tipo_duracion = $licSpec->tipo_duracion;

            if ($model->resultado == 'SUCCESS') {
                $licencia->estado = Licencia::ESTADO_ACTIVO;
                $salon->estado_sistema = Salon::ESTADO_SISTEMA_ACTIVADO;
            }else {
                $licencia->estado = Licencia::ESTADO_INACTIVO;
                $salon->estado_sistema = Salon::ESTADO_SISTEMA_INACTIVO;
            }

            $h_inicio = date_create();
            $licencia->fecha_inicio = date_format($h_inicio, 'Y-m-d H:i');
            if ($tipo_duracion == LicenciaSpec::TIPO_DURACION_DIA)
                $tipo_duracion_str = 'days';
            elseif ($tipo_duracion == LicenciaSpec::TIPO_DURACION_MES)
                $tipo_duracion_str = 'months';
            elseif ($tipo_duracion == LicenciaSpec::TIPO_DURACION_ANNO)
                $tipo_duracion_str = 'years';

            date_add($h_inicio, date_interval_create_from_date_string($duracion . ' ' . $tipo_duracion_str));
            $licencia->fecha_fin = date_format($h_inicio, 'Y-m-d H:i');
            $licencia->licencia_specid = $licSpec->id;

            $nueva_licencia = $licencia->isNewRecord;

            if (!$licencia->save(false))
                throw new Exception('No se pudo guardar/actualizar la licencia');

            if ($nueva_licencia)
                $salon->link('licencia', $licencia);

            $licencia->link('detallesPago', $model);

            $transaction->commit();
            $result_code = true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error($e->getTraceAsString(), 'paypal');
            throw $e;
        }
        */
        return $this->render('payment-done', [
            'success' => $result_code,
        ]);
    }


    public function actionPaymentCancel(){
        $userid = Yii::$app->user->getId();
        $model = Salon::find()->where(['usuarioid'=>$userid])->one();
        if(isset($model) and isset($model->licencia)){
            $licencia = $model->licencia;
            $licencia->delete();
        }

        return $this->render('payment-cancel', [

        ]);


    }

    public function actionCheckYourMail() {
        $this->layout = 'login';
        return $this->render('checkYourMail');
    }

    public function actionChgPassSuccess($type) {
        $this->layout = ($type == 'app') ? 'app' : 'login';
        return $this->render(($type == 'app') ? 'chg-pass-success_app' : 'chg-pass-success');
    }

    public function actionPrivacy(){
        $this->layout = 'login';
        return $this->render('privacy');
    }

    public function actionUseterms(){
        $this->layout = 'login';
        return $this->render('use-terms');
    }

    public function actionTestrecurrent(){
        echo '<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="P7D3N3Y8KRMKS">
<input type="hidden" name="custom" value="toma">
<input type="hidden" name="rm" value="2">
<!--<input type="hidden" name="return" value="http://beautydate.softok2.com/index.php?r=site/rresponse">-->
<input type="hidden" name="return" value="http://beautydate.softok2.com/payresponse.php">
<input type="image" src="https://www.sandbox.paypal.com/es_XC/MX/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal, la forma más segura y rápida de pagar en línea.">
<img alt="" border="0" src="https://www.sandbox.paypal.com/es_XC/i/scr/pixel.gif" width="1" height="1">
</form>';

//        echo '<A HREF="https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_subscr-find&alias=PZDE3YGBUG6M8">
//<IMG SRC="https://www.sandbox.paypal.com/es_XC/i/btn/btn_unsubscribe_LG.gif" BORDER="0">
//</A>';

        die;
    }

    function actionRresponse($user = '', $lic = '', $salonid = '', $token = '',$payment_status = '', $mc_currency = '', $payer_email = '', $receiver_email= ''){

        //Yii::info($_REQUEST, 'paypal');
        //print_r($_REQUEST);
        //die;
        /*
        $transaction = \Yii::$app->db->beginTransaction();
        try {



            $salon = Salon::find()->where( ['id' => $salonid] ) ->one();

            $licencia =  new Licencia();
            $licencia->estado = Licencia::ESTADO_PROCESANDO;
            $licencia->token = $token;
            //$licencia->es

            $licSpec = LicenciaSpec::findOne($lic);
            $duracion = $licSpec->duracion;
            $tipo_duracion = $licSpec->tipo_duracion;


            $h_inicio = date_create();
            $licencia->fecha_inicio = date_format($h_inicio, 'Y-m-d H:i');
            if ($tipo_duracion == LicenciaSpec::TIPO_DURACION_DIA)
                $tipo_duracion_str = 'days';
            elseif ($tipo_duracion == LicenciaSpec::TIPO_DURACION_MES)
                $tipo_duracion_str = 'months';
            elseif ($tipo_duracion == LicenciaSpec::TIPO_DURACION_ANNO)
                $tipo_duracion_str = 'years';

            date_add($h_inicio, date_interval_create_from_date_string($duracion . ' ' . $tipo_duracion_str));
            $licencia->fecha_fin = date_format($h_inicio, 'Y-m-d H:i');
            $licencia->licencia_specid = $licSpec->id;

            $nueva_licencia = $licencia->isNewRecord;

            if (!$licencia->save(false))
                throw new Exception('No se pudo guardar/actualizar la licencia');

            if ($nueva_licencia)
                $salon->link('licencia', $licencia);

            $transaction->commit();
            $result_code = true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error($e->getTraceAsString(), 'paypal');
            throw $e;
        }*/


        $result_code = true;
        return $this->render('payment-done', [
            'success' => $result_code,
        ]);

    }

    function actionRpcancel(){


    }


    /*Ipn listener for pypal*/
    function actionIpn(){
        //LicenciaHelper::updatePaymentIfExist(1, 'I-V8CPTPJ0J3YE', 18);die;

        /*
        By default the IpnListener object is going to post the data back to PayPal
        using cURL over a secure SSL connection. This is the recommended way to post
        the data back, however, some people may have connections problems using this
        method.

        To post over standard HTTP connection, use:
        $listener->use_ssl = false;

        To post using the fsockopen() function rather than cURL, use:
        $listener->use_curl = false;
        */

        /*
        The processIpn() method will encode the POST variables sent by PayPal and then
        POST them back to the PayPal server. An exception will be thrown if there is
        a fatal error (cannot connect, your server is not configured properly, etc.).
        Use a try/catch block to catch these fatal errors and log to the ipn_errors.log
        file we setup at the top of this file.

        The processIpn() method will send the raw data on 'php://input' to PayPal. You
        can optionally pass the data to processIpn() yourself:
        $verified = $listener->processIpn($my_post_data);
        */

        $ipnListener = new IpnListener();
        //$ipnListener->use_sandbox = true;
        $ipnListener->use_sandbox = false;
        //$verified = true;
        Yii::info($_REQUEST, 'paypal');

        try {
            $ipnListener->requirePostMethod();
            $verified = $ipnListener->processIpn();
        } catch (Exception $e) {
            Yii::error($e->getMessage(), 'paypal');
            throw $e;
            //exit(0);
        }

        /*
        The processIpn() method returned true if the IPN was "VERIFIED" and false if it
        was "INVALID".
        */
        if ($verified) {
            /*
            Once you have a verified IPN you need to do a few more checks on the POST
            fields--typically against data you stored in your database during when the
            end user made a purchase (such as in the "success" page on a web payments
            standard button). The fields PayPal recommends checking are:

                1. Check the $_POST['payment_status'] is "Completed"
                2. Check that $_POST['txn_id'] has not been previously processed
                3. Check that $_POST['receiver_email'] is your Primary PayPal email
                4. Check that $_POST['payment_amount'] and $_POST['payment_currency']
                   are correct

            Since implementations on this varies, I will leave these checks out of this
            example and just send an email using the getTextReport() method to get all
            of the details about the IPN.
            */

            $data = $_POST;
            //$data = $ipnListener->getPostData();


            //$licInstance = Licencia::find()->where(['token' => $user_id_lic_id[3]])->one();
            //if(!$licInstance){
            //    Yii::info('No se ha creado la intancia licencia todavia puede ser que la repuesta demoro mas que el IPN en el proximo se creara');
            //    return;
            //}


            $ipn_record = IpnNotification::find()->where([
                    'subscr_id' =>  isset($data['subscr_id']) ? $data['subscr_id'] : '',
                    'txn_type' => $data['txn_type']]
            )->one();

            if ($ipn_record != null) {
                Yii::warning('MENSAJE DUPLICADO ** ' . $ipnListener->getPostData(), 'paypal');
                return;
            }


            $licencia = Licencia::find()->where(['id' => $data['custom']])->one();
            if(!$licencia or count($licencia->salons) != 1){
                Yii::warning('Licencia no encontrada');
                return;
            }

            $salon = $licencia->salons[0];


            $update_salon_state = false;

            if ($data['txn_type'] == 'subscr_signup') {


                $ipn_record = new IpnNotification();
                $ipn_record->txn_type = IpnNotification::TNX_TYPE_SIGNUP;
                $ipn_record->subscr_id = $data['subscr_id'];
                $ipn_record->payer_email = $data['payer_email'];
                $ipn_record->receiver_email = $data['receiver_email'];
                $ipn_record->msg_date = isset($data['subscr_date']) ? date('Y-m-d', strtotime($data['subscr_date'])) : '';
                $ipn_record->item_name = $data['item_name'];
                $ipn_record->mc_currency = $data['mc_currency'];
                //$user_id_lic_id = explode('_', $data['custom']);
                $ipn_record->user_id = $salon->usuarioid;
                $ipn_record->licencia_id = $data['custom'];
                $licencia->estado = Licencia::ESTADO_ACTIVO;
                $licencia->save();
                $ipn_record->save();

                $update_salon_state = true;

                //LicenciaHelper::saveLicencia($data, $ipn_record, $user_id_lic_id[1]);
            //} elseif ($data['txn_type'] == 'subscr_payment' and isset($data['txn_id'] ) and $data['payment_status']== 'Completed' ) {
            } elseif ($data['txn_type'] == 'subscr_payment' and isset($data['txn_id'] ) ) {

                $transaction = Yii::$app->db->beginTransaction();
                $ipn_record = new IpnNotification();
                $ipn_record->txn_type = IpnNotification::TNX_TYPE_PAYMENT;
                $ipn_record->txn_id = $data['txn_id'];
                $ipn_record->subscr_id = $data['subscr_id'];
                $ipn_record->payer_email = $data['payer_email'];
                $ipn_record->receiver_email = $data['receiver_email'];
                $ipn_record->msg_date = isset($data['subscr_date']) ? date('Y-m-d', strtotime($data['subscr_date'])): '';
                $ipn_record->item_name = $data['item_name'];
                $ipn_record->mc_currency = $data['mc_currency'];
                //$ipn_record->user_id = $user_id_lic_id[0];
                $ipn_record->payment_status = $data['payment_status'];
                $ipn_record->payment_type = $data['payment_type'];
                $ipn_record->mc_gross = $data['mc_gross'];
                //$user_id_lic_id = explode('_', $data['custom']);
                //$ipn_record->user_id = $user_id_lic_id[0];

                $ipn_record->user_id = $salon->usuarioid;
                $ipn_record->licencia_id = $data['custom'];
                $licencia->estado = Licencia::ESTADO_ACTIVO;
                $licencia->save();
                $ipn_record->save();

                $update_salon_state = true;

                /*if(!$salon = Salon::find()->where(['id' => $user_id_lic_id[2]])->one()){
                    Yii::error('El salon que adquirió esa licencia no existe');
                    return;
                }*/
                //$ipn_record->licencia_id = $salon->licenciaid;
                //$ipn_record->save();
                //$ipn_record
                //LicenciaHelper::saveLicencia($data, $ipn_record, $user_id_lic_id[1]);
                $transaction->commit();

            }elseif($data['txn_type'] == IpnNotification::TNX_TYPE_EOT or $data['txn_type'] == IpnNotification::TNX_TYPE_CANCELLED){
                $ipn_record = new IpnNotification();
                $ipn_record->txn_type = $data['txn_type'];
                $ipn_record->subscr_id = $data['subscr_id'];

                if(!count($licencia->salons)){
                    Yii::error('El salon que ha adquirido esa licencia no existe');
                    return;
                }else{
                    $ipn_record->licencia_id = $data['custom'];
                    $ipn_record->user_id = $salon->usuarioid;
                    $ipn_record->save();

                    $licencia->detalles = 'El servicio de paypal fue cancelado o expiró';

                    //obtener fecha de ultimo pago, adicionarle la duracion del tipo de licencia y setearlo
                    // como fecha de fin para que el cliente luego de cancelar el servicio todavia pueda disfrutar del
                    //periodo correspondiente de licencia, y no se le inhabilite inmediantamente la licencia

                    $last_payment = IpnNotification::find()->where([
                        'licencia_id' => $licencia->id,
                        'txn_type' => IpnNotification::TNX_TYPE_PAYMENT
                    ])->orderBy('msg_date DESC')->one();

                    if ($last_payment) {
                        $last_payment_date = date_create($last_payment->msg_date);
                        $duracion = $licencia->licenciaSpec->duracion;
                        $tipo_duracion = $licencia->licenciaSpec->tipo_duracion;

                        if ($tipo_duracion == LicenciaSpec::TIPO_DURACION_DIA)
                            $tipo_duracion_str = 'days';
                        elseif ($tipo_duracion == LicenciaSpec::TIPO_DURACION_MES)
                            $tipo_duracion_str = 'months';
                        elseif ($tipo_duracion == LicenciaSpec::TIPO_DURACION_ANNO)
                            $tipo_duracion_str = 'years';

                        date_add($last_payment_date, date_interval_create_from_date_string($duracion . ' ' . $tipo_duracion_str));
                        $licencia->fecha_fin = date_format($last_payment_date, 'Y-m-d H:i');
                    }else {
                        $licencia->estado = Licencia::ESTADO_INACTIVO;
                        $salon->estado_sisitema = Salon::ESTADO_SISTEMA_INACTIVO;
                    }

                    $licencia->save();
                    $salon->save();

                    $update_salon_state = true;
                }

            } elseif($data['txn_type'] == IpnNotification::TNX_TYPE_WEB_ACEPT){

                    $ipn_record = new IpnNotification();
                    $ipn_record->txn_type = IpnNotification::TNX_TYPE_WEB_ACEPT;
                    $ipn_record->txn_id = $data['txn_id'];
                   // $ipn_record->subscr_id = $data['subscr_id'];
                    $ipn_record->payer_email = $data['payer_email'];
                    $ipn_record->receiver_email = $data['receiver_email'];
                    //$ipn_record->msg_date = isset($data['subscr_date']) ? date('Y-m-d', strtotime($data['subscr_date'])): '';
                    $ipn_record->item_name = $data['item_name'];
                    $ipn_record->mc_currency = $data['mc_currency'];
                   // $ipn_record->user_id = $user_id_lic_id[0];
                    $ipn_record->payment_status = $data['payment_status'];
                    $ipn_record->payment_type = $data['payment_type'];
                    $ipn_record->mc_gross = $data['mc_gross'];
                   // $ipn_record->user_id = $user_id_lic_id[0];
                    $ipn_record->user_id = $salon->usuarioid;
                    $ipn_record->licencia_id = $data['custom'];
                    $licencia->estado = Licencia::ESTADO_ACTIVO;
                    $licencia->save();
                    $ipn_record->save();

                    $update_salon_state = true;

                    //LicenciaHelper::saveLicencia($data, $ipn_record, $user_id_lic_id[1]);

            }

            if($update_salon_state)
                SalonHelper::actualizarEstadoSalon($salon->id);

            //DetallesPago::find()->where([]);


            mail('melonormajm@gmail.com', 'Verified IPN', $ipnListener->getTextReport());
            /*Yii::$app->mailer->compose()
                ->setFrom('ipn@domain.com')
                ->setTo('melonormajm@gmail.com')
                ->setCc('melonorma@infomed.sld.cu')
                ->setSubject('Reporte')
                ->setTextBody($ipnListener->getTextReport())
                //->setHtmlBody($h)
                //->setHtmlBody('<p>Html</p>')
                ->send();
            */
           // Yii::info($ipnListener->getPostData(), 'paypal');


        } else {
            /*
            An Invalid IPN *may* be caused by a fraudulent transaction attempt. It's
            a good idea to have a developer or sys admin manually investigate any
            invalid IPN.
            */
            mail('melonormajm@gmail.com', 'Invalid IPN', $ipnListener->getTextReport());
        }

        Yii::$app->request->enableCsrfValidation = TRUE;
    }


    /*Ipn listener for pypal*/
    function actionIpnOLD(){
        //LicenciaHelper::updatePaymentIfExist(1, 'I-V8CPTPJ0J3YE', 18);die;

        /*
        By default the IpnListener object is going to post the data back to PayPal
        using cURL over a secure SSL connection. This is the recommended way to post
        the data back, however, some people may have connections problems using this
        method.

        To post over standard HTTP connection, use:
        $listener->use_ssl = false;

        To post using the fsockopen() function rather than cURL, use:
        $listener->use_curl = false;
        */

        /*
        The processIpn() method will encode the POST variables sent by PayPal and then
        POST them back to the PayPal server. An exception will be thrown if there is
        a fatal error (cannot connect, your server is not configured properly, etc.).
        Use a try/catch block to catch these fatal errors and log to the ipn_errors.log
        file we setup at the top of this file.

        The processIpn() method will send the raw data on 'php://input' to PayPal. You
        can optionally pass the data to processIpn() yourself:
        $verified = $listener->processIpn($my_post_data);
        */

        $ipnListener = new IpnListener();
        $ipnListener->use_sandbox = true;
        //$verified = true;
        try {
            $ipnListener->requirePostMethod();
            $verified = $ipnListener->processIpn();
        } catch (Exception $e) {
            Yii::error($e->getMessage(), 'paypal');
            throw $e;
            //exit(0);
        }

        /*
        The processIpn() method returned true if the IPN was "VERIFIED" and false if it
        was "INVALID".
        */
        if ($verified) {
            /*
            Once you have a verified IPN you need to do a few more checks on the POST
            fields--typically against data you stored in your database during when the
            end user made a purchase (such as in the "success" page on a web payments
            standard button). The fields PayPal recommends checking are:

                1. Check the $_POST['payment_status'] is "Completed"
                2. Check that $_POST['txn_id'] has not been previously processed
                3. Check that $_POST['receiver_email'] is your Primary PayPal email
                4. Check that $_POST['payment_amount'] and $_POST['payment_currency']
                   are correct

            Since implementations on this varies, I will leave these checks out of this
            example and just send an email using the getTextReport() method to get all
            of the details about the IPN.
            */

            $data = $_POST;
            //$data = $ipnListener->getPostData();
            $user_id_lic_id = explode('_', $data['custom']);

            //$licInstance = Licencia::find()->where(['token' => $user_id_lic_id[3]])->one();
            //if(!$licInstance){
            //    Yii::info('No se ha creado la intancia licencia todavia puede ser que la repuesta demoro mas que el IPN en el proximo se creara');
            //    return;
            //}


            $ipn_record = IpnNotification::find()->where([
                    'subscr_id' =>  isset($data['subscr_id']) ? $data['subscr_id'] : '',
                    'txn_type' => $data['txn_type']]
            )->one();

            if ($ipn_record != null) {
                Yii::warning('MENSAJE DUPLICADO ** ' . $ipnListener->getPostData(), 'paypal');
                return;
            }


            if ($data['txn_type'] == 'subscr_signup') {


                $ipn_record = new IpnNotification();
                $ipn_record->txn_type = IpnNotification::TNX_TYPE_SIGNUP;
                $ipn_record->subscr_id = $data['subscr_id'];
                $ipn_record->payer_email = $data['payer_email'];
                $ipn_record->receiver_email = $data['receiver_email'];
                $ipn_record->msg_date = isset($data['subscr_date']) ? date('Y-m-d', strtotime($data['subscr_date'])) : '';
                $ipn_record->item_name = $data['item_name'];
                $ipn_record->mc_currency = $data['mc_currency'];
                //$user_id_lic_id = explode('_', $data['custom']);
                $ipn_record->user_id = $user_id_lic_id[0];


                LicenciaHelper::saveLicencia($data, $ipn_record, $user_id_lic_id[1]);
                //} elseif ($data['txn_type'] == 'subscr_payment' and isset($data['txn_id'] ) and $data['payment_status']== 'Completed' ) {
            } elseif ($data['txn_type'] == 'subscr_payment' and isset($data['txn_id'] ) ) {

                $transaction = Yii::$app->db->beginTransaction();
                $ipn_record = new IpnNotification();
                $ipn_record->txn_type = IpnNotification::TNX_TYPE_PAYMENT;
                $ipn_record->txn_id = $data['txn_id'];
                $ipn_record->subscr_id = $data['subscr_id'];
                $ipn_record->payer_email = $data['payer_email'];
                $ipn_record->receiver_email = $data['receiver_email'];
                $ipn_record->msg_date = isset($data['subscr_date']) ? date('Y-m-d', strtotime($data['subscr_date'])): '';
                $ipn_record->item_name = $data['item_name'];
                $ipn_record->mc_currency = $data['mc_currency'];
                $ipn_record->user_id = $user_id_lic_id[0];
                $ipn_record->payment_status = $data['payment_status'];
                $ipn_record->payment_type = $data['payment_type'];
                $ipn_record->mc_gross = $data['mc_gross'];
                $user_id_lic_id = explode('_', $data['custom']);
                $ipn_record->user_id = $user_id_lic_id[0];

                /*if(!$salon = Salon::find()->where(['id' => $user_id_lic_id[2]])->one()){
                    Yii::error('El salon que adquirió esa licencia no existe');
                    return;
                }*/
                //$ipn_record->licencia_id = $salon->licenciaid;
                //$ipn_record->save();
                //$ipn_record
                LicenciaHelper::saveLicencia($data, $ipn_record, $user_id_lic_id[1]);
                $transaction->commit();

            }elseif($data['txn_type'] == IpnNotification::TNX_TYPE_EOT or $data['txn_type'] == IpnNotification::TNX_TYPE_CANCELLED){
                $ipn_record = new IpnNotification();
                $ipn_record->txn_type = $data['txn_type'];
                $ipn_record->subscr_id = $data['subscr_id'];

                if(!$salon = Salon::find()->where(['id' => $user_id_lic_id[2]])->one()){
                    Yii::error('El salon que adquirido esa licencia no existe');
                    return;
                }else{
                    $ipn_record->licencia_id = $salon->licenciaid;
                    $ipn_record->user_id = $user_id_lic_id[0];
                    $ipn_record->save();
                    $salon->licencia->estado = Licencia::ESTADO_INACTIVO;
                    $salon->licencia->detalles = 'El servicio de paypal fue cancelado o expiró';
                    $salon->licencia->save();
                    $salon->estado = Salon::ESTADO_INACTIVO;
                    $salon->save();
                }

            } elseif($data['txn_type'] == IpnNotification::TNX_TYPE_WEB_ACEPT){

                $ipn_record = new IpnNotification();
                $ipn_record->txn_type = IpnNotification::TNX_TYPE_WEB_ACEPT;
                $ipn_record->txn_id = $data['txn_id'];
                // $ipn_record->subscr_id = $data['subscr_id'];
                $ipn_record->payer_email = $data['payer_email'];
                $ipn_record->receiver_email = $data['receiver_email'];
                //$ipn_record->msg_date = isset($data['subscr_date']) ? date('Y-m-d', strtotime($data['subscr_date'])): '';
                $ipn_record->item_name = $data['item_name'];
                $ipn_record->mc_currency = $data['mc_currency'];
                $ipn_record->user_id = $user_id_lic_id[0];
                $ipn_record->payment_status = $data['payment_status'];
                $ipn_record->payment_type = $data['payment_type'];
                $ipn_record->mc_gross = $data['mc_gross'];
                $ipn_record->user_id = $user_id_lic_id[0];

                LicenciaHelper::saveLicencia($data, $ipn_record, $user_id_lic_id[1]);

            }

            //DetallesPago::find()->where([]);


            mail('melonormajm@gmail.com', 'Verified IPN', $ipnListener->getTextReport());
            mail('melonorma@infomed.sld.cu', 'Verified IPN', $ipnListener->getTextReport());
            /*Yii::$app->mailer->compose()
                ->setFrom('ipn@domain.com')
                ->setTo('melonormajm@gmail.com')
                ->setCc('melonorma@infomed.sld.cu')
                ->setSubject('Reporte')
                ->setTextBody($ipnListener->getTextReport())
                //->setHtmlBody($h)
                //->setHtmlBody('<p>Html</p>')
                ->send();
            */
            // Yii::info($ipnListener->getPostData(), 'paypal');


        } else {
            /*
            An Invalid IPN *may* be caused by a fraudulent transaction attempt. It's
            a good idea to have a developer or sys admin manually investigate any
            invalid IPN.
            */
            mail('melonormajm@gmail.com', 'Invalid IPN', $ipnListener->getTextReport());
            mail('melonorma@infomed.sld.cu', 'Invalid IPN', $ipnListener->getTextReport());
        }

        Yii::$app->request->enableCsrfValidation = TRUE;
    }




    public function actionTe(){

        $licencia = Licencia::find()->where(['id' => 3])->one();
        if(!$licencia or count($licencia->salons) != 1){
            die('Licencia no encontrada');
            Yii::warning('Licencia no encontrada');
            return;
        }

        $salon = $licencia->salons[0];

        $ipn_record = new IpnNotification();
        $ipn_record->txn_type = IpnNotification::TNX_TYPE_CANCELLED;
        $ipn_record->subscr_id = 'sdfdsf';

        if(!count($licencia->salons)){
            die('El salon que ha adquirido esa licencia no existe');
            Yii::error('El salon que ha adquirido esa licencia no existe');
            return;
        }else{
            $ipn_record->licencia_id = 3;
            $ipn_record->user_id = $salon->usuarioid;
            $ipn_record->save();

            $licencia->detalles = 'El servicio de paypal fue cancelado o expiró';

            //obtener fecha de ultimo pago, adicionarle la duracion del tipo de licencia y setearlo
            // como fecha de fin para que el cliente luego de cancelar el servicio todavia pueda disfrutar del
            //periodo correspondiente de licencia, y no se le inhabilite inmediantamente la licencia

            $last_payment = IpnNotification::find()->where([
                'licencia_id' => $licencia->id,
                'txn_type' => IpnNotification::TNX_TYPE_PAYMENT
            ])->orderBy('msg_date DESC')->one();

            if ($last_payment) {
                $last_payment_date = date_create($last_payment->msg_date);
                $duracion = $licencia->licenciaSpec->duracion;
                $tipo_duracion = $licencia->licenciaSpec->tipo_duracion;

                if ($tipo_duracion == LicenciaSpec::TIPO_DURACION_DIA)
                    $tipo_duracion_str = 'days';
                elseif ($tipo_duracion == LicenciaSpec::TIPO_DURACION_MES)
                    $tipo_duracion_str = 'months';
                elseif ($tipo_duracion == LicenciaSpec::TIPO_DURACION_ANNO)
                    $tipo_duracion_str = 'years';

                date_add($last_payment_date, date_interval_create_from_date_string($duracion . ' ' . $tipo_duracion_str));
                $licencia->fecha_fin = date_format($last_payment_date, 'Y-m-d H:i');
            }else {
                $licencia->estado = Licencia::ESTADO_INACTIVO;
                $salon->estado = Salon::ESTADO_INACTIVO;
            }

            $licencia->save();
            $salon->save();
        }
    }
}
