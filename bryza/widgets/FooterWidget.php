<?php
namespace bryza\widgets;

use Yii;
use yii\base\Widget;
use common\models\Category;
use common\models\Document;
use common\models\Event;
use common\models\SubscribeFeedback;

class FooterWidget extends Widget
{
    public function run()
    {
        $categories = Category::find()
            ->where([
                'status' => Category::STATUS_ACTIVE,
                'parent_id' => 0,
                'project_id' => Yii::$app->projects->current->alias,
            ])
            ->orderBy(['depth' => SORT_ASC])
            ->limit(5)
            ->all();

        $documents = Document::find()
            ->where([
                'status' => Document::ENABLE,
                'project_id' => Yii::$app->projects->current->alias,
            ])
            ->limit(5)
            ->all();

        $events = Event::find()
            ->where([
                'status' => Event::STATUS_ACTIVE,
                'project_id' => Yii::$app->projects->current->alias,
            ])
            ->limit(3)
            ->orderBy([
                'published_at' => SORT_DESC,
            ])
            ->all();

        $model = new SubscribeFeedback();

        return $this->render('_footer', [
            'categories' => $categories,
            'documents' => $documents,
            'events' => $events,
            'model' => $model,
        ]);
    }
}
