<?php

namespace common\models;

use common\helpers\AdminHelper;
use noIT\content\controllers\AdminController;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ArticleSearch represents the model behind the search form about `common\models\Article`.
 *
 * @property integer $timestamp_to
 * @property integer $timestamp_from
 */

class ArticleSearch extends Article
{
	use BaseContentSearchTrait;
	public $modelTableName = 'common\models\Article';
}
