<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ProductFeatureSearch represents the model behind the search form about `backend\models\ProductFeature`.
 */
class ProductFeatureSearch extends ProductFeature
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'overall', 'status', 'created_at', 'updated_at'], 'integer'],
            [['native_name', 'slug', 'image', 'name_ru_ru', 'name_uk_ua', 'caption_ru_ru', 'caption_uk_ua', 'value_type', 'admin_widget', 'widget'], 'safe'],
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
        $query = ProductFeature::find();

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
            'overall' => $this->overall,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'native_name', $this->native_name])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'name_ru_ru', $this->name_ru_ru])
            ->andFilterWhere(['like', 'name_uk_ua', $this->name_uk_ua])
            ->andFilterWhere(['like', 'caption_ru_ru', $this->caption_ru_ru])
            ->andFilterWhere(['like', 'caption_uk_ua', $this->caption_uk_ua])
            ->andFilterWhere(['like', 'value_type', $this->value_type])
            ->andFilterWhere(['like', 'admin_widget', $this->admin_widget])
            ->andFilterWhere(['like', 'widget', $this->widget]);

        return $dataProvider;
    }
}
