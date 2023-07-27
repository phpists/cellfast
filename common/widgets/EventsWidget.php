<?php
namespace common\widgets;

use cellfast\models\Event;
use Yii;

class EventsWidget extends \yii\bootstrap\Widget
{
	public $limit = 3;

	public $title;

	public $bgColor;

	private $urlSelector;

	public function init()
	{
		parent::init();
		$this->getUrlSelector();
	}

	public function run()
	{
		parent::run();

		if(!$this->title) {
			$this->title = Yii::t('app', 'Last events');
		}

		if(!$this->bgColor) {
			$this->bgColor = '';
		}

		$items = Event::find()
		              ->where([
			              'status' => Event::STATUS_ACTIVE,
			              'project_id' => Yii::$app->projects->current->alias,
		              ])
					->andWhere(['<>', 'slug', $this->urlSelector])
		              ->orderBy([
			              'published_at' => SORT_DESC
		              ])
		              ->limit($this->limit)
		              ->all();

		if ($items) {
			return $this->render('last-events', [
				'bgColor' => $this->bgColor,
				'title' => $this->title,
				'items' => $items,
			]);
		}
	}

	protected function getUrlSelector()
	{
		$url = Yii::$app->request->pathInfo;

		if(strpos($url, '/')) {
			$url = explode('/', $url);
			$url = $url[1];

			if(strpos($url, '.html')) {
				$url = substr($url, 0, strlen($url) - 5);
			}

		}

		$this->urlSelector = $url;
	}

}
