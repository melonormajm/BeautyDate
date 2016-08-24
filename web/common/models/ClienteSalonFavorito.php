<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cliente_salon_favorito".
 *
 * @property integer $usuarioid
 * @property integer $salonid
 *
 * @property User $usuario
 * @property Salon $salon
 */
class ClienteSalonFavorito extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cliente_salon_favorito';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['usuarioid', 'salonid'], 'required'],
            [['usuarioid', 'salonid'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'usuarioid' => 'Usuarioid',
            'salonid' => 'Salonid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(User::className(), ['id' => 'usuarioid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalon()
    {
        return $this->hasOne(Salon::className(), ['id' => 'salonid']);
    }
}
