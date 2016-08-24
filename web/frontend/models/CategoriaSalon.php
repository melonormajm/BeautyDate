<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "categoria_salon".
 *
 * @property integer $categoriaid
 * @property integer $salonid
 *
 * @property Salon $salon
 * @property Categoria $categoria
 */
class CategoriaSalon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categoria_salon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['categoriaid', 'salonid'], 'required'],
            [['categoriaid', 'salonid'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'categoriaid' => 'Categoriaid',
            'salonid' => 'Salonid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalon()
    {
        return $this->hasOne(Salon::className(), ['id' => 'salonid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoria()
    {
        return $this->hasOne(Categoria::className(), ['id' => 'categoriaid']);
    }
}
