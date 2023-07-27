<?php

namespace common\models;

use Yii;
use yii\base\Model;
use common\helpers\AdminHelper;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * BaseContentSearchTrait
 *
 * @property integer $timestamp_to
 * @property integer $timestamp_from
 *
 */
trait BaseContentSearchTrait
{
	public $timestamp_from;
	public $timestamp_to;
	public $tag;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'project_id', 'timestamp_from', 'timestamp_to'], 'integer'],
			[['slug', 'tag'], 'safe'],
			[AdminHelper::getLangsField('name'), 'safe'],
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
		/** @var ActiveQuery $query */
		$query = call_user_func([$this->modelTableName, 'find']);

		// add conditions that should always apply here

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort'=> [
				'defaultOrder' => ['published_at' => SORT_DESC]
			],
			'pagination' => [
				'pageSize' => AdminHelper::getPageSize('content'),
			],
		]);
		$this->load($params, '');

		if (!$this->validate()) {

			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		if ($this->_project_id) {
			$this->project_id = $this->_project_id;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			'id' => $this->id,
			'project_id' => $this->project_id,
			'status' => $this->status,
		]);

		if ($this->timestamp_from) {
			$query->andWhere(['>=', AdminHelper::FIELDNAME_PUBLISHED, $this->timestamp_from]);
		}
		if ($this->timestamp_to) {
			$query->andWhere(['<=', AdminHelper::FIELDNAME_PUBLISHED, $this->timestamp_to]);
		}

		foreach ( Yii::$app->languages->languages as $language ) {
			$query->andFilterWhere(['like', AdminHelper::getLangField('name', $language), $this->name]);
			$query->andFilterWhere(['like', AdminHelper::getLangField('body', $language), $this->name]);
		}

		if ($this->tag) {
			if (!is_array($this->tag)) {
				$this->tag = [$this->tag];
			}
			$tags = Tag::find()->where(['slug' => $this->tag, 'project_id' => Yii::$app->id])->select('id')->column();
			$query->innerJoin('article_has_tag aht', 'aht.article_id = '. self::tableName() .'.id')->where(['aht.tag_id' => $tags]);
		}

		return $dataProvider;
	}
}
