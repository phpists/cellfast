<?php

namespace backend\models;

use common\helpers\AdminHelper;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ProductSearch represents the model behind the search form about `backend\models\Product`.
 */
class ProductSearch extends Product
{
	public $name;

	public $sku;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type_id', 'status'], 'integer'],
            [['slug', 'project_id', 'name', 'sku'], 'safe'],
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
        $query = Product::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
	        'sort' => [
		        'defaultOrder' => [
			        'created_at' => SORT_DESC,
		        ]
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
            'project_id' => $this->project_id,
            'type_id' => $this->type_id,
            'status' => $this->status,
        ]);

        if ($this->sku) {
            $query->innerJoinWith('items');
            $query->andWhere(['like', 'product_item.sku', $this->sku]);
        }

//        $query->andFilterWhere(['like', 'sku', $this->slug]);

        foreach (Yii::$app->languages->languages as $language) {
            $nameField = AdminHelper::getLangField('name', $language);
            $query->orFilterWhere(['like', $nameField, $this->name]);
        }

        return $dataProvider;
    }
}
