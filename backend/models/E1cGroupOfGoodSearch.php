<?php

namespace backend\models;

use common\helpers\AdminHelper;
use common\models\soap\E1cGroupOfGood;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * E1cGroupOfGoodSearch represents the model behind the search form about `backend\models\Order`.
 */
class E1cGroupOfGoodSearch extends E1cGroupOfGood
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
	        [['guid'], 'string', 'max' => 16],
            [['name'], 'safe'],
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
        $query = E1cGroupOfGood::find();

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
            'guid' => $this->guid,
            'name' => $this->name,
        ]);

        return $dataProvider;
    }
}
