<?php

namespace backend\models;

use common\helpers\AdminHelper;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PriceType;

/**
 * PriceTypeSearch represents the model behind the search form about `backend\models\PriceType`.
 */
class PriceTypeSearch extends PriceType
{
	public $name;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'includeVAT'], 'integer'],
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
        $query = PriceType::find();

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
            'includeVAT' => $this->includeVAT,
        ]);

        $query->andFilterWhere(['like', 'native_name', $this->name]);
	    foreach (\Yii::$app->languages->languages as $language) {
		    $query->orFilterWhere(['like', AdminHelper::getLangField('name', $language), $this->name]);
	    }

        return $dataProvider;
    }
}
