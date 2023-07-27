<?php
namespace cellfast\controllers;

use common\models\Article;
use noIT\content\controllers\FrontendController;
use noIT\content\models\ContentSearch;
use Yii;

/**
 * Article controller
 */
class ArticleController extends FrontendController
{
	protected $modelClass = 'cellfast\models\Article';
	protected $modelSearchClass = 'cellfast\models\ArticleSearch';

	/**
	 * Lists all ContentEvent models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		/** @var ContentSearch $searchModel */
		$searchModel = new $this->modelSearchClass();

		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		$dataProvider->query->where([
			'status' => Article::STATUS_ACTIVE,
			'project_id' => Yii::$app->projects->current->alias,
			]);

		$dataProvider->pagination->pageSize = Article::PAGE_SIZE;

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'params' => Yii::$app->request->queryParams,
		]);
	}
}
?>