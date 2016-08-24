<?php

namespace common\models;

use Yii;
use yii\web\UploadedFile;


/**
 * This is the model class for table "salon".
 *
 * @property integer $id
 * @property string $nombre
 * @property integer $cantidad_sillas
 * @property string $thumbnail
 * @property string $ubicacion
 * @property string $ubicacion_latitud
 * @property string $ubicacion_longitud
 * @property string $estado
 * @property string $hora_inicio
 * @property string $hora_fin
 * @property integer $usuarioid
 * @property string $descripcion
 * @property string $descripcion_corta
 * @property integer $licenciaid
 * @property integer $monedaid
 *
 * @property CategoriaSalon[] $categoriaSalons
 * @property Categoria[] $categorias
 * @property ClienteSalonFavorito[] $clienteSalonFavoritos
 * @property Usuario[] $usuarios
 * @property Licencia $licencia
 * @property Usuario $usuario
 * @property Servicio[] $servicios
 * @property Sillon[] $sillons
 */
class Salon extends \yii\db\ActiveRecord
{
    const ESTADO_ACTIVADO = 'ACTIVO';
    const ESTADO_INACTIVO = 'INACTIVO';

    const ESTADO_SISTEMA_ACTIVADO = 'ACTIVO';
    const ESTADO_SISTEMA_INACTIVO = 'INACTIVO';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'salon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre', 'hora_inicio', 'hora_fin'], 'required'],
            [['usuarioid', 'licenciaid', 'monedaid'], 'integer'],
            [['nombre', 'ubicacion', 'descripcion_corta'], 'string', 'max' => 255],
            [['ubicacion_latitud','ubicacion_longitud'],'string','max'=>50],
            [['estado','dias_no_laborables'], 'string', 'max' => 25],
            [['hora_inicio', 'hora_fin'], 'string', 'max' => 4],
            [['descripcion'], 'string', 'max' => 500],
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
            'ubicacion' => 'Ubicación',
            'estado' => 'Estado',
            'hora_inicio' => 'Hora Inicio',
            'hora_fin' => 'Hora Fin',
            'usuarioid' => 'Usuarioid',
            'descripcion' => 'Descripcion',
            'descripcion_corta' => 'Descripcion Corta',
            'licenciaid' => 'Licenciaid',
            'monedaid' => 'Moneda'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriaSalons()
    {
        return $this->hasMany(CategoriaSalon::className(), ['salonid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategorias()
    {
        return $this->hasMany(Categoria::className(), ['id' => 'categoriaid'])->viaTable('categoria_salon', ['salonid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClienteSalonFavoritos()
    {
        return $this->hasMany(ClienteSalonFavorito::className(), ['salonid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarios()
    {
        return $this->hasMany(User::className(), ['id' => 'usuarioid'])->viaTable('cliente_salon_favorito', ['salonid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLicencia()
    {
        return $this->hasOne(Licencia::className(), ['id' => 'licenciaid']);
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
    public function getServicios()
    {
        return $this->hasMany(Servicio::className(), ['salonid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSillons()
    {
        return $this->hasMany(Sillon::className(), ['salonid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImagenes()
    {
        return $this->hasMany(Imagenes::className(), ['salonid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMoneda()
    {
        return $this->hasOne(Moneda::className(), ['id' => 'monedaid']);
    }


    public function afterSave($insert,$changedAttributes)
    {
        $return = parent::afterSave($insert,$changedAttributes);
        $servicios = $this->servicios;
        if(count($servicios)>0){
            foreach($servicios as $serv){
                $serv->horario_inicio = $this->hora_inicio;
                $serv->horario_fin = $this->hora_fin;
                $serv->save();
            }
        }
        return $return;
    }

    /*public function extraFields()
    {
        return ['servicios', 'salonimagenes'];
    }*/



    /*    public function fields(){

            return [

                "thumbnail" => "thumbnail"
            ];


        }*/


}
