<?php

namespace backend\models;

use common\helpers\AdminHelper;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ProductFeature;

/**
 * ProductFeatureSearch represents the model behind the search form about `backend\models\ProductFeature`.
 */
class ProductFeatureSearch extends ProductFeature
{
    public $name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'overall', 'status'], 'integer'],
            [['project_id', 'slug', 'name', 'caption', 'value_type', 'admin_widget', 'filter_widget'], 'safe'],
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
            'project_id' => $this->project_id,
            'overall' => $this->overall,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'value_type', $this->value_type])
            ->andFilterWhere(['like', 'admin_widget', $this->admin_widget])
            ->andFilterWhere(['like', 'filter_widget', $this->filter_widget]);

        foreach (Yii::$app->languages->languages as $language) {
            $nameField = AdminHelper::getLangField('name', $language);
            $captionField = AdminHelper::getLangField('caption', $language);
            $query->andFilterWhere(['like', $nameField, $this->name])
                ->andFilterWhere(['like', $captionField, $this->name]);
        }

        return $dataProvider;
    }
}
