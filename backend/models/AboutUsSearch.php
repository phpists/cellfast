<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\AboutUs;

/**
 * AboutUsSearch represents the model behind the search form of `backend\models\AboutUs`.
 */
class AboutUsSearch extends AboutUs
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['project_id', 'name_ru_ru', 'name_uk_ua', 'slug', 'cover', 'teaser_ru_ru', 'teaser_uk_ua', 'body_ru_ru', 'body_uk_ua', 'video', 'body_2_ru_ru', 'body_2_uk_ua'], 'safe'],
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
        $query = AboutUs::find();

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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'project_id', $this->project_id])
            ->andFilterWhere(['like', 'name_ru_ru', $this->name_ru_ru])
            ->andFilterWhere(['like', 'name_uk_ua', $this->name_uk_ua])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'cover', $this->cover])
            ->andFilterWhere(['like', 'teaser_ru_ru', $this->teaser_ru_ru])
            ->andFilterWhere(['like', 'teaser_uk_ua', $this->teaser_uk_ua])
            ->andFilterWhere(['like', 'body_ru_ru', $this->body_ru_ru])
            ->andFilterWhere(['like', 'body_uk_ua', $this->body_uk_ua])
            ->andFilterWhere(['like', 'video', $this->video])
            ->andFilterWhere(['like', 'body_2_ru_ru', $this->body_2_ru_ru])
            ->andFilterWhere(['like', 'body_2_uk_ua', $this->body_2_uk_ua]);

        return $dataProvider;
    }
}
