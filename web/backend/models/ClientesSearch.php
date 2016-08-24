<?php

namespace backend\models;


/**
 * SalonSearch represents the model behind the search form about `app\models\Salon`.
 */



use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;
use common\models\Reservacion;
use common\models\Salon;

/**
 * SalonSearch represents the model behind the search form about `app\models\Salon`.
 */
class ClientesSearch extends User
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


        $query = self::find();
        $query->joinWith('userRedsocials');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'user.email', $this->email]);


        $query->groupBy('user.id');


        return $dataProvider;
    }

    public function getReservaciones()
    {
        return $this->hasMany(Reservacion::className(), ['usuarioid' => 'id']);
    }

}
