<?php

namespace backend\models;

use common\helpers\AdminHelper;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Event;

/**
 * EventSearch represents the model behind the search form about `common\models\Event`.
 */
class EventSearch extends Event
{
	public $word;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['project_id', 'slug', 'word'], 'string'],
        ];
    }

    public function attributeLabels() {
	    return array_merge(
	    	[
	    		'word' => Yii::t('app', 'Word'),
		    ],
		    parent::attributeLabels()
	    );
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
        $query = Event::find();

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
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'slug', $this->slug])
	        ->andFilterWhere(['like', 'project_id', $this->project_id]);

	    foreach (Yii::$app->languages->languages as $language) {
	        $query->orFilterWhere(['like', AdminHelper::getLangField('name', $language), $this->word]);
	        $query->orFilterWhere(['like', AdminHelper::getLangField('teaser', $language), $this->word]);
	        $query->orFilterWhere(['like', AdminHelper::getLangField('body', $language), $this->word]);
        }

        return $dataProvider;
    }
}
