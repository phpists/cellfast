<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\ProductItem;
use common\helpers\AdminHelper;
use yii\data\ActiveDataProvider;

/**
 * ProductItemSearch represents the model behind the search form about `common\models\ProductItem`.
 */
class ProductItemSearch extends ProductItem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'product_id', 'type_id', 'status'], 'integer'],
            [['project_id'], 'string', 'max' => 20],
            [['sku', 'name'], 'string'],
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
		$query = ProductItem::find();

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
			'product_id' => $this->product_id,
			'type_id' => $this->type_id,
			'status' => $this->status,
		]);

		$query->andFilterWhere(['like', 'sku', $this->sku])
		      ->andFilterWhere(['like', 'project_id', $this->project_id]);
		$query->orFilterWhere(['like', 'native_name', $this->name]);
		foreach (\Yii::$app->languages->languages as $language) {
			$query->orFilterWhere(['like', AdminHelper::getLangField('name', $language), $this->name]);
		}

		return $dataProvider;
	}
}
