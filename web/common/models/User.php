<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $auth_type
 * @property string $user_type
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const TIPO_SISTEMA = 'SISTEMA';
    const TIPO_PROPIETARIO = 'PROPIETARIO';
    const TIPO_CLIENTE = 'CLIENTE';

    const AUTH_LOCAL = 'LOCAL';
    const AUTH_FACEBOOK = 'FACEBOOK';
    const AUTH_GOOGLE = 'GOOGLE';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username, $source)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE, 'user_type' => $source]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getLicencia(){
        $id = Yii::$app->user->id;
        $salon = Salon::find()->where(['usuarioid'=>$id])->one();
        if($salon!=NULL){
            $licencia = $salon->getLicencia()->one();
            if($licencia==NULL){
                return true;
            }
            elseif($licencia->fecha_fin != NULL and strtotime("now") > strtotime($licencia->fecha_fin)){
                return true;
            }
        }
        return false;
    }

    public function getEstado(){
        $id = Yii::$app->user->id;
        $salon = Salon::find()->where(['usuarioid'=>$id])->one();

        if($salon != NULL){
            return $salon->estado_sistema;
        }
        return false;
    }
    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function getFullName() {
        return $this->first_name . ' ' . $this->last_name;
    }



    public function getUserRedsocials()
    {
        return $this->hasMany(UserRedsocial::className(), ['usuarioid' => 'id']);
    }

    public static function findByEmail($email, $source = self::TIPO_CLIENTE)
    {

        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE, 'user_type' => $source]);
    }

    /**
     * Finds user by username app user
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsernameApp($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE, 'user_type' => self::TIPO_CLIENTE ]);
    }
}
