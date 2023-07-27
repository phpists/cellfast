<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Category;

/**
 * CategorySearch represents the model behind the search form about `common\models\Category`.
 */
class CategorySearch extends Category
{
    protected $_project_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'project_id', 'depth', 'status', 'created_at', 'updated_at'], 'integer'],
            [['path', 'cover', 'seo_h1_ru_ru', 'seo_h1_uk_ua', 'seo_title_ru_ru', 'seo_title_uk_ua', 'seo_description_ru_ru', 'seo_description_uk_ua', 'seo_keywords_ru_ru', 'seo_keywords_uk_ua', 'name_ru_ru', 'name_uk_ua'], 'safe'],
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
        $query = Category::find();

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

        if ($this->_project_id) {
            $this->project_id = $this->_project_id;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'project_id' => $this->project_id,
            'depth' => $this->depth,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'path', $this->path])
            ->andFilterWhere(['like', 'image', $this->cover])
            ->andFilterWhere(['like', 'seo_h1_ru_ru', $this->seo_h1_ru_ru])
            ->andFilterWhere(['like', 'seo_h1_uk_ua', $this->seo_h1_uk_ua])
            ->andFilterWhere(['like', 'seo_title_ru_ru', $this->seo_title_ru_ru])
            ->andFilterWhere(['like', 'seo_title_uk_ua', $this->seo_title_uk_ua])
            ->andFilterWhere(['like', 'seo_description_ru_ru', $this->seo_description_ru_ru])
            ->andFilterWhere(['like', 'seo_description_uk_ua', $this->seo_description_uk_ua])
            ->andFilterWhere(['like', 'seo_keywords_ru_ru', $this->seo_keywords_ru_ru])
            ->andFilterWhere(['like', 'seo_keywords_uk_ua', $this->seo_keywords_uk_ua])
            ->andFilterWhere(['like', 'name_ru_ru', $this->name_ru_ru])
            ->andFilterWhere(['like', 'name_uk_ua', $this->name_uk_ua]);

        return $dataProvider;
    }
}
