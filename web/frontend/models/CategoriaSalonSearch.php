<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CategoriaSalon;

/**
 * CategoriaSalonSearch represents the model behind the search form about `app\models\CategoriaSalon`.
 */
class CategoriaSalonSearch extends CategoriaSalon
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['categoriaid', 'salonid'], 'integer'],
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
        $query = CategoriaSalon::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'categoriaid' => $this->categoriaid,
            'salonid' => $this->salonid,
        ]);

        return $dataProvider;
    }
}
