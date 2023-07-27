<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Partner;

/**
 * PartnerSearch represents the model behind the search form of `backend\models\Partner`.
 */
class PartnerSearch extends Partner
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'location_region_id', 'status'], 'integer'],
            [['type', 'name_ru_ru', 'caption_ru_ru', 'address_ru_ru', 'coordinate', 'phones', 'website', 'logotype'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Partner::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'location_region_id' => $this->location_region_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'name_ru_ru', $this->name_ru_ru])
            ->andFilterWhere(['like', 'caption_ru_ru', $this->caption_ru_ru])
            ->andFilterWhere(['like', 'address_ru_ru', $this->address_ru_ru])
            ->andFilterWhere(['like', 'coordinate', $this->coordinate])
            ->andFilterWhere(['like', 'phones', $this->phones])
            ->andFilterWhere(['like', 'website', $this->website])
            ->andFilterWhere(['like', 'logotype', $this->logotype]);

        return $dataProvider;
    }
}
