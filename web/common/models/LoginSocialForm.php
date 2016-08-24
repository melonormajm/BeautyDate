<?php
/**
 * Created by PhpStorm.
 * User: abel
 * Date: 4/12/2015
 * Time: 9:03 PM
 */

namespace common\models;

use common\helper\Util;
use common\models\SignupForm;
use yii\base\Exception;
use yii\base\Model;
use Yii;

class LoginSocialForm extends Model{

    public $beauty_user_id; // id del perfil local
    public $beauty_social_type; // tipo de red social
    public $id; // id de usuario en la red social
    public $first_name;
    public $last_name;
    public $name;
    public $gender;
    public $link;
    public $email;
    public $perfil_local;

    public function rules()
    {
        return [
            // username and password are both required
            [['beauty_social_type', 'id'], 'required'],
            [['beauty_social_type', 'first_name', 'last_name', 'name', 'gender', 'link', 'email'], 'safe'],
            // rememberMe must be a boolean value
            ['beauty_user_id', 'integer'],
            ['beauty_social_type', 'in', 'range' => [User::AUTH_FACEBOOK, User::AUTH_GOOGLE] ],
        ];
    }

    public function login($source = User::TIPO_CLIENTE)
    {
        if(!$this->email)
            $this->email = $this->id . '@mail.com';
        if ($this->validate()) {
            $userSocial = UserRedsocial::findOne([
                'red_social_id' => $this->beauty_social_type,
                'user_red_social_id' => $this->id]);

            if (!$userSocial)
                $userSocial = new UserRedsocial();

            $userSocial->red_social_id = $this->beauty_social_type;
            $userSocial->nombre = $this->first_name;
            $userSocial->apellido = $this->last_name;
            $userSocial->email = $this->email;
            $userSocial->linkk = $this->link;
            $userSocial->user_red_social_id = $this->id;

            if (!$userSocial->save(false))
                throw new Exception($userSocial->getErrors());

            //asociar el perfil social al perfil local

            $this->perfil_local = $userSocial->usuario;
            //si el perfil social no tiene asociado un perfil local y me pasaron id de perfil local, cargarlo
            if (!$this->perfil_local) {
                /*if ($this->beauty_user_id) {
                    $this->perfil_local = User::findOne(['id' => $this->beauty_user_id, 'user_type' => $source]);
                    $this->perfil_local->last_red_social = $this->beauty_social_type;
                    if ($this->perfil_local->save(false))
                        throw new Exception(json_encode($this->perfil_local->getErrors()));
                } else {*/
                $sForm = new SignupForm();
                $sForm->user_type = $source;
                //$sForm->username = strtolower($this->first_name . $userSocial->id);
                $sForm->username = $this->email;
                $sForm->password = Yii::$app->security->generateRandomString(\Yii::$app->params['randomPasswordLenght']);
                $sForm->email = $this->email;
                //$sForm->first_name = $this->first_name;
                //$sForm->first_name = $this->last_name;
                $sForm->last_red_social = $this->beauty_social_type;
                $this->perfil_local = $sForm->signup('api');
                //Actualizamos los el first name del ussuario
                $this->perfil_local->first_name = $this->first_name;
                $this->perfil_local->last_name = $this->last_name;
                $this->perfil_local->save(false);
                $userSocial->link('usuario', $this->perfil_local);

                //TODO ENVIAR CORREO DE NUEVO REGISTRO
                //$this->sendMail($sForm->password);
                //}
            } else {
                $this->perfil_local->last_red_social = $this->beauty_social_type;
                if (!$this->perfil_local->save(false))
                    throw new Exception('El perfil local no pudo ser guardado');
            }

            return Yii::$app->user->login($this->perfil_local, 0) ?
                ['user_id' => $this->perfil_local->id,
                    'token' => $this->perfil_local->auth_key] :false;

        } else {
            Yii:error($this->getErrors());
            throw new Exception(Util::modelError2String($this->getErrors()) );
            //throw new Exception('Ocurrio un error en la validacion de los datos');
        }
    }

    /*public function loginWithProfile(){
        if ($this->validate()) {


        }
    }*/

    public function sendMail($password) {
        return \Yii::$app->mailer->compose(['html' => 'newUser-html'],
            ['user' => $this->perfil_local, 'pass' => $password])
            ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Registro ' . \Yii::$app->name)
            ->send();
    }
}