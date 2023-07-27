<?php

namespace noIT\tips\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use noIT\tips\models\Tip;

/**
 * TipSearch represents the model behind the search form of `noIT\tips\models\Tip`.
 */
class TipSearch extends Tip
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['model', 'attribute', 'body'], 'safe'],
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
        $query = Tip::find();

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
        ]);

        $query->andFilterWhere(['like', 'model', $this->model])
            ->andFilterWhere(['like', 'attribute', $this->attribute])
            ->andFilterWhere(['like', 'body', $this->body]);

        return $dataProvider;
    }
}
