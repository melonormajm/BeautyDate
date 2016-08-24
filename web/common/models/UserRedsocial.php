<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_redsocial".
 *
 * @property integer $id
 * @property string $red_social_id
 * @property string $nombre
 * @property string $apellido
 * @property string $email
 * @property integer $usuarioid
 * @property string $link
 * @property string $user_red_social_id
 *
 * @property User $usuario
 */
class UserRedsocial extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_redsocial';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['usuarioid'], 'integer'],
            [['red_social_id'], 'string', 'max' => 25],
            [['nombre', 'apellido', 'email', 'linkk', 'user_red_social_id'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'red_social_id' => 'Red Social ID',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'email' => 'Email',
            'usuarioid' => 'Usuarioid',
            'linkk' => 'Link',
            'user_red_social_id' => 'User Red Social ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(User::className(), ['id' => 'usuarioid']);
    }
}
