<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;
    public $user_type;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email','message' => 'La dirección de correo no es válida'],
            /*
            ['email', 'exist',
                'targetClass' => '\common\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'No existe un usuario con la dirección de correo especificada.'
            ],*/
            ['email', function($attribute, $params){
                if(!User::find()->where(['user_type' => $this->user_type, 'email' => $this->$attribute])->one()){
                    $this->addError($attribute, 'Esta dirección de correo no está asociada a ningún propietario.');
                }
            }],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
            'user_type' => $this->user_type,
        ]);

        if ($user) {
            if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
            }

            if ($user->save()) {
                return \Yii::$app->mailer->compose(['html' => 'passwordResetToken-html'], ['user' => $user])
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
                    ->setTo($this->email)
                    ->setSubject('Cambio de contraseña para ' . \Yii::$app->name)
                    ->send();
            }
        }

        return false;
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Correo',
        ];
    }
}
