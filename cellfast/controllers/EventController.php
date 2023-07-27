<?php
namespace cellfast\controllers;

use common\models\Event;
use common\models\EventSearch;
use noIT\content\controllers\FrontendController;
use Yii;

/**
 * Event controller
 */
class EventController extends FrontendController
{
	protected $modelClass = 'cellfast\models\Event';
	protected $modelSearchClass = 'cellfast\models\EventSearch';

	/**
	 * Lists all ContentEvent models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		/** @var EventSearch $searchModel */
		$searchModel = new $this->modelSearchClass();

		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		$dataProvider->query->where([
			'status' => Event::STATUS_ACTIVE,
			'project_id' => Yii::$app->projects->current->alias,
		]);

		$dataProvider->pagination->pageSize = Event::PAGE_SIZE;

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'params' => Yii::$app->request->queryParams,
		]);
	}
}
?>