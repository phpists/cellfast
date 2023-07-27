<?php

namespace backend\models;

use common\helpers\AdminHelper;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Category;

/**
 * CategorySearch represents the model behind the search form about `backend\models\Category`.
 */
class CategorySearch extends Category
{
    public $name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'depth', 'status', 'created_at', 'updated_at'], 'integer'],
            [['project_id', 'path', 'name', 'slug', 'name'], 'safe'],
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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'project_id' => $this->project_id,
            'parent_id' => $this->parent_id,
            'depth' => $this->depth,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'slug', $this->slug]);
        foreach (Yii::$app->languages->languages as $language) {
            $nameField = AdminHelper::getLangField('name', $language);
            $captionField = AdminHelper::getLangField('caption', $language);
            $query->andFilterWhere(['like', $nameField, $this->name])
                ->andFilterWhere(['like', $captionField, $this->name]);
        }

        return $dataProvider;
    }
}
