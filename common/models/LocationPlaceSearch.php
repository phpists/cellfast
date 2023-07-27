<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\LocationPlace;

/**
 * LocationPlaceSearch represents the model behind the search form about `common\models\LocationPlace`.
 */
class LocationPlaceSearch extends LocationPlace
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'region_id', 'country_id', 'is_default', 'sort_order', 'status', 'created_at', 'updated_at'], 'integer'],
            [['native_name', 'slug', 'image', 'flag', 'name_ru_ru', 'name_uk_ua', 'body_ru_ru', 'body_uk_ua'], 'safe'],
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
        $query = LocationPlace::find();

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
            'region_id' => $this->region_id,
            'country_id' => $this->country_id,
            'is_default' => $this->is_default,
            'sort_order' => $this->sort_order,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'native_name', $this->native_name])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'flag', $this->flag])
            ->andFilterWhere(['like', 'name_ru_ru', $this->name_ru_ru])
            ->andFilterWhere(['like', 'name_uk_ua', $this->name_uk_ua])
            ->andFilterWhere(['like', 'body_ru_ru', $this->body_ru_ru])
            ->andFilterWhere(['like', 'body_uk_ua', $this->body_uk_ua]);

        return $dataProvider;
    }
}
