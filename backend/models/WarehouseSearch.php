<?php

namespace backend\models;

use common\models\Warehouse;
use common\helpers\AdminHelper;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * WarehouseSearch represents the model behind the search form about `backend\models\Warehouse`.
 */
class WarehouseSearch extends Warehouse
{
	public $location_region_id;
	public $location_country_id;
	public $name;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'location_place_id', 'location_region_id', 'location_country_id'], 'integer'],
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
        $query = Warehouse::find();

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
            'place_id' => $this->location_place_id,
            'region_id' => $this->location_region_id,
            'country_id' => $this->location_country_id,
        ]);

        $query->andFilterWhere(['like', 'native_name', $this->name]);

        foreach (Yii::$app->languages->languages as $language) {
            $nameField = AdminHelper::getLangField('name', $language);
            $query->andFilterWhere(['like', $nameField, $this->name]);
        }

        return $dataProvider;
    }
}
