<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\Feedback;
use yii\data\ActiveDataProvider;

/**
 * FeedbackSearch represents the model behind the search form about `common\models\Feedback`.
 */
class FeedbackSearch extends Feedback
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'date_from', 'date_to'], 'integer'],
            [['ip', 'name', 'email', 'phone', 'message', 'modelName'], 'safe'],
        ];
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), ['date_from', 'date_to', 'modelName']);
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
        $query = Feedback::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSizeLimit' => [0, 500]
            ],
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
            'status' => $this->status,
        ]);

        if ($this->date_from) {
            $query->andWhere(['>=', 'created_at', $this->date_from]);
        }
        if ($this->date_to) {
            $query->andWhere(['<=', 'created_at', $this->date_to]);
        }

        $query->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'message', $this->message])
            ->andFilterWhere(['like', 'model', $this->modelName]);

        $query->orderBy(['created_at' => SORT_DESC]);

        return $dataProvider;
    }
}
