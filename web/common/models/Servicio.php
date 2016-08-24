<?php

namespace common\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "servicio".
 *
 * @property integer $id
 * @property string $nombre
 * @property string $descripcion
 * @property integer $duracion
 * @property string $horario_inicio
 * @property string $horario_fin
 * @property double $precio
 * @property string $estado
 * @property integer $salonid
 *
 * @property Salon $salon
 * @property SillonServicio[] $sillonServicios
 */
class Servicio extends \yii\db\ActiveRecord
{
    const ESTADO_ACTIVO = 'ACTIVO';
    const ESTADO_INACTIVO = 'INACTIVO';

    const ESTADO_SISTEMA_ACTIVO = 'ACTIVO';
    const ESTADO_SISTEMA_INACTIVO = 'INACTIVO';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'servicio';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre', 'duracion', 'precio', 'estado', 'salonid','categoriaid'], 'required'],
            [['duracion', 'salonid', 'categoriaid'], 'integer'],
            [['horario_inicio', 'horario_fin'], 'safe'],
            [['precio'], 'number'],
            [['nombre', 'descripcion'], 'string', 'max' => 255],
            [['estado', 'imagen'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'descripcion' => 'Descripción',
            'duracion' => 'Duración',
            'horario_inicio' => 'Horario Inicio',
            'horario_fin' => 'Horario Fin',
            'precio' => 'Precio',
            'estado' => 'Estado',
            'salonid' => 'Salonid',
            'categoriaid' => 'Categoria'
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
    public function getSillonServicios()
    {
        return $this->hasMany(SillonServicio::className(), ['servicioid' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSillonServiciosActivos()
    {
        return $this->hasMany(SillonServicio::className(), ['servicioid' => 'id'])->where(['estado' => '1']);
    }



    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoria()
    {
        return $this->hasOne(Categoria::className(), ['id' => 'categoriaid']);
    }

    public function fields() {
        $fields = parent::fields();
        //unset($fields['estado']);
        return $fields;
    }

    public function getImageurl()
    {
        return Url::to('@web/'.$this->imagen, true);
    }

    public function beforeSave($insert)
    {
        $return = parent::beforeSave($insert);

        $salon = $this->salon;
        $this->horario_inicio = $salon->hora_inicio;
        $this->horario_fin = $salon->hora_fin;

        $completado = false;
        if($this->estado == $this::ESTADO_ACTIVO){
            foreach($this->sillonServicios as $sillonServ){
                if($sillonServ->estado == 1 && $sillonServ->sillon->estado == Sillon::ESTADO_ACTIVO){
                    $completado = true;
                    break;
                }
            }
        }
        if($completado){
            $this->estado_sistema = $this::ESTADO_SISTEMA_ACTIVO;
        }else{
            $this->estado_sistema = $this::ESTADO_SISTEMA_INACTIVO;
        }
        return $return;
    }

    public function afterSave($insert,$changedAttributes)
    {
        $return = parent::afterSave($insert, $changedAttributes);
        \common\helper\SalonHelper::actualizarEstadoSalon($this->salonid);
        return $return;
    }
}
