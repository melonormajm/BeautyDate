<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;
use common\models\Reservacion;
use common\models\Salon;

/**
 * SalonSearch represents the model behind the search form about `app\models\Salon`.
 */
class ClienteSearch extends User
{
    /**
     * @inheritdoc
     */

    var $salonid;

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['first_name', 'last_name', 'email'], 'safe'],
            //[['nombre', 'thumbnail', 'ubicacion', 'estado', 'hora_inicio', 'hora_fin', 'descripcion', 'descripcion_corta'], 'safe'],
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
        $this->salonid = Salon::find()->where(['usuarioid'=>Yii::$app->user->getId()])->one()->id;

        $query = self::find();
        $query->innerJoinWith(['reservaciones' => function ($query) {
            $query->andWhere(['reservacion.salonid' => $this->salonid]);
        }])->joinWith('userRedsocials');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([ 'user_type' => ClienteSearch::TIPO_CLIENTE])
              ->andFilterWhere(['like', 'email', $this->email])
              ->andFilterWhere(['like', 'first_name', $this->first_name])
              ->andFilterWhere([ 'reservacion.salonid' => $this->salonid ]);
        /*$query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'thumbnail', $this->thumbnail])
            ->andFilterWhere(['like', 'ubicacion', $this->ubicacion])
            ->andFilterWhere(['like', 'estado', $this->estado])
            ->andFilterWhere(['like', 'hora_inicio', $this->hora_inicio])
            ->andFilterWhere(['like', 'hora_fin', $this->hora_fin])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'descripcion_corta', $this->descripcion_corta]);*/


        $query->groupBy('user.id');


        return $dataProvider;
    }

    public function getReservaciones()
    {
        return $this->hasMany(Reservacion::className(), ['usuarioid' => 'id']);
    }

}
