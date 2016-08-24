<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\LicenciaSpec;

/**
 * LicenciaSpecSearch represents the model behind the search form about `app\models\LicenciaSpec`.
 */
class LicenciaSpecSearch extends LicenciaSpec
{
    public $precio_moneda;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'],'integer'],
           // [['id', 'usuarioid', 'licenciaid'], 'integer'],
            [['nombre', 'estado', 'tipo'], 'safe'],
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
        $query = LicenciaSpec::find();


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
            ],/*
            'sort' => [
                'defaultOrder' => ['orden' => SORT_ASC],
            ],*/
        ]);


        if (!($this->load($params) && $this->validate())) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['estado' => $this->estado])
            ->andFilterWhere(['tipo' => $this->tipo]);

        return $dataProvider;
    }
}
