<?php
namespace frontend\models;

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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required', 'message' => 'El usuario no puede ser vacío'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Ya existe un usuario con ese nombre'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required', 'message' => 'El correo no puede ser vacío'],
            ['email', 'email', 'message' => 'Especifique una dirección de correo válida'],
            ['email', function($attribute, $params){
                if(User::find()->where(['user_type' => $this->user_type, 'email' => $this->$attribute])->one()){
                    $this->addError($attribute, 'Esa dirección de correo ya existe');
                }
            }],
            //['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Esa dirección de correo ya existe.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            [['auth_type', 'user_type'], 'safe'],
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
}
