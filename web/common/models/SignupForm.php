<?php
namespace common\models;

use common\models\Categoria;
use common\models\User;
use yii\base\Exception;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $auth_type;
    public $user_type;
    public $last_red_social;
    public $first_name;
    public $last_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            [['username', 'email'], 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Ya existe un usuario con ese nombre.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'email'],
            ['email', function($attribute, $params){
                    if(User::find()->where(['user_type' => $this->user_type, 'email' => $this->$attribute])->one()){
                        $this->addError($attribute, 'Esa dirección de correo ya existe.');
                    }
            }],
            /*['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Esa dirección de correo ya existe.'],*/

            ['password', 'required'],
            //['password', 'string', 'min' => 6],

            [['auth_type', 'user_type', 'first_name', 'last_name'], 'safe'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup($app = 'otro')
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->user_type = $this->user_type;
            $user->auth_type = $this->auth_type;
            $user->first_name =$this->first_name;
            $user->last_name =$this->last_name;
            /*
            if (strtoupper($this->auth_type) == User::AUTH_FACEBOOK)
                $user->auth_type = User::AUTH_FACEBOOK;
            elseif (strtoupper($this->auth_type) == User::AUTH_GOOGLE)
                $user->auth_type = User::AUTH_GOOGLE;
            elseif (strtoupper($this->auth_type) == User::AUTH_LOCAL)
                $user->auth_type = User::AUTH_LOCAL;
            */
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save()) {
                return $user;
            } elseif ($app == 'api') {
                throw new Exception(json_encode($this->getErrors()));
            }
        }

        if ($app == 'api')
            throw new Exception(json_encode($this->getErrors()));
		return null;
    }

    public function updateProfile($id){
        $user = User::find()->where(['id'=>$id])->one();
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->email = $this->email;
        $user->username = $this->username;
        return $user->save(false);
    }
}
