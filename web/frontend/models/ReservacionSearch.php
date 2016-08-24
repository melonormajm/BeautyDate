<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Reservacion;
use common\models\Salon;

/**
 * ReservacionSearch represents the model behind the search form about `app\models\Reservacion`.
 */
class ReservacionSearch extends Reservacion
{

    var $cliente;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'usuarioid', 'sillon_servicioid'], 'integer'],
            [['estado', 'fecha', 'aplicacion_cliente', 'cliente'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Reservacion::find();
        //$query->innerJoinWith('usuario');
        $query->joinWith('usuario.userRedsocials');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        /*$query->andFilterWhere([
            'id' => $this->id,
            'fecha' => $this->fecha,
            'usuarioid' => $this->usuarioid,
            'sillon_servicioid' => $this->sillon_servicioid,
        ]);*/

        $query->andFilterWhere([
            'like', 'user_redsocial.nombre', $this->cliente
           // ['like' , 'user.first_name', $this->cliente],
           // ['OR','like' , 'user.last_name', $this->cliente]

        ]);

        $query->andFilterWhere(['like', 'reservacion.estado', $this->estado]);
        if($this->fecha){
            $query->andFilterWhere([ 'reservacion.fecha' =>  date('Y-m-d', strtotime($this->fecha))]);
        }
        $salon = Salon::findOne(['usuarioid' => Yii::$app->user->id]);
        $query->andFilterWhere(['salonid'=>$salon->id]);

        /*    ->andFilterWhere(['like', 'aplicacion_cliente', $this->aplicacion_cliente])
            ->innerJoinWith(['sillonServicio.servicio.salon' => function ($query) {
                $query->andWhere(['salon.id' => Salon::findOne(['usuarioid' => Yii::$app->user->id])->id]);
            }]);
       */

        return $dataProvider;
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'estado' => 'Estado',
            'fecha' => 'Fecha',
            'aplicacion_cliente' => 'Aplicacion Cliente',
            'usuarioid' => 'Usuarioid',
            'sillon_servicioid' => 'Sillon Servicioid',
            'cliente' => 'Cliente'
        ];
    }
}
