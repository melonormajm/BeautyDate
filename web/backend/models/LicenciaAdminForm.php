<?php
namespace backend\models;

use common\models\Categoria;
use common\models\User;
use yii\base\Exception;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class LicenciaAdminForm extends Model
{
    public $detalles;
    public $estado;
    public $licencia_specid;
    public $usuario_id;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           /* ['usuarioid', 'required', 'El usuario es requerido'],
            ['licencia_specid', 'required', 'La licencia es requerido'],
            ['detalles', 'required', 'Es necesario poner los detalles'],*/
            [['usuario_id', 'licencia_specid', 'detalles','estado'], 'safe']
        ];
    }


}
