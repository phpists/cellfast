<?php

namespace common\models;

use common\helpers\AdminHelper;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * ProductSearch represents the model behind the search form about `backend\models\Product`.
 */
class ProductSearch extends Product
{
	public $word;
	public $features;
	public $prices;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type_id', 'status'], 'integer'],
	        [['project_id'], 'string'],
            [['slug', 'word', 'features', 'prices'], 'safe'],
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
    public function search($params = null)
    {
        $query = (new Query())
            ->select(['p.id'])
	        ->from(Product::tableName() .' p')
            ->leftJoin(['pi' => ProductItem::tableName()], 'pi.product_id = p.id')
            ->groupBy('p.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
	        /*'sort'=> [
				'defaultOrder' => [
					'created_at' => SORT_DESC,
				]
			],*/
            'pagination' => [
	            'pageSize' => AdminHelper::getPageSize('content'),
            ],
        ]);

        $this->load($params);

        if ($this->type_id) {
	        $query->andWhere(['p.type_id' => $this->type_id]);
        }

        if ($this->project_id) {
	        $query->andWhere(['p.project_id' => $this->project_id]);
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');

            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
	        'p.id' => $this->id,
	        'p.status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'p.slug', $this->slug]);

        // TODO - move to filter
	    /*$this->word = trim($this->word);

        if ($this->word) {
	        foreach ( Yii::$app->languages->languages as $language ) {
		        $nameField = AdminHelper::getLangField( 'name', $language );
		        $query->orFilterWhere( [ 'like', "p.{$nameField}", $this->word ] );
	        }
        }*/

	    $isDefineFeatures = false;

	    if ($this->features) {
	        $product_features_define = [];
	        if ($this->category && $this->category->product_type && $this->category->product_type->product_features_define) {
	            /** @var ProductTypeHasProductFeature $_product_feature_define */
                foreach ($this->category->product_type->product_features_define as $_product_feature_define) {
                    $product_features_define[] = $_product_feature_define->product_feature_id;
                }
            }

		    $featureValuesTableName = $this->_featureValuesTableName;
		    $featureValuesItemTableName = $this->_featureValuesItemTableName;

        	foreach ( $this->features as $feature_id => $feature_values ) {
        	    if (in_array($feature_id, $product_features_define)) {
        	        // This is defined feature
                    $value_ids = [];
                    foreach ($feature_values as $feature_value) {
                        if (!is_object($feature_value)) {
                            $_feature_value = new \stdClass();
                            $_feature_value->id = $feature_value;
                            $feature_value = $_feature_value;
                        }
                        $value_ids[] = $feature_value->id;
                    }
                    $query->innerJoin("$featureValuesItemTableName fv{$feature_id}", "fv{$feature_id}.product_item_id = pi.id");
                    $query->andWhere(["fv{$feature_id}.product_feature_value_id" => $value_ids]);
                    $isDefineFeatures = true;
                } else {
                    $value_ids = [];
                    foreach ($feature_values as $feature_value) {
                        if (!is_object($feature_value)) {
                            $_feature_value = new \stdClass();
                            $_feature_value->id = $feature_value;
                            $feature_value = $_feature_value;
                        }
                        $value_ids[] = $feature_value->id;
                    }
                    $query->innerJoin("$featureValuesTableName fv{$feature_id}", "fv{$feature_id}.product_id = p.id");
                    $query->andWhere(["fv{$feature_id}.product_feature_value_id" => $value_ids]);
                }
	        }
        }

	    if ($isDefineFeatures) {
//            $query->having('COUNT(pi.id) > 0');
        }

        return $dataProvider;
    }
}
