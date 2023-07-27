<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\AboutMainPage;

/**
 * AboutMainPageSearch represents the model behind the search form of `backend\models\AboutMainPage`.
 */
class AboutMainPageSearch extends AboutMainPage
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['project_id', 'name_ru_ru', 'name_uk_ua', 'cover', 'body_ru_ru', 'body_uk_ua', 'info_image_1', 'info_image_2', 'info_teaser_1_ru_ru', 'info_teaser_1_uk_ua', 'info_teaser_2_ru_ru', 'info_teaser_2_uk_ua'], 'safe'],
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
        $query = AboutMainPage::find();

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
            ->andFilterWhere(['like', 'cover', $this->cover])
            ->andFilterWhere(['like', 'body_ru_ru', $this->body_ru_ru])
            ->andFilterWhere(['like', 'body_uk_ua', $this->body_uk_ua])
            ->andFilterWhere(['like', 'info_image_1', $this->info_image_1])
            ->andFilterWhere(['like', 'info_image_2', $this->info_image_2])
            ->andFilterWhere(['like', 'info_teaser_1_ru_ru', $this->info_teaser_1_ru_ru])
            ->andFilterWhere(['like', 'info_teaser_1_uk_ua', $this->info_teaser_1_uk_ua])
            ->andFilterWhere(['like', 'info_teaser_2_ru_ru', $this->info_teaser_2_ru_ru])
            ->andFilterWhere(['like', 'info_teaser_2_uk_ua', $this->info_teaser_2_uk_ua]);

        return $dataProvider;
    }
}
