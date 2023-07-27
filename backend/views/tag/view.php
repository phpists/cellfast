<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Article */

$__params = require __DIR__ .'/__params.php';

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => $__params['items'], 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="custom-form-section">
    <div class="custom-form-section-box">

        <p>
			<?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
			<?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
				'class' => 'btn btn-danger',
				'data' => [
					'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
					'method' => 'post',
				],
			]) ?>
        </p>

		<?= DetailView::widget([
			'model' => $model,
			'attributes' => [
				'id',
				'slug',
				'image',
				'name_ru_ru',
				'name_uk_ua',
				'teaser_ru_ru:ntext',
				'teaser_uk_ua:ntext',
				'body_ru_ru:ntext',
				'body_uk_ua:ntext',
				'seo_h1_ru_ru',
				'seo_h1_uk_ua',
				'seo_title_ru_ru',
				'seo_title_uk_ua',
				'seo_description_ru_ru:ntext',
				'seo_description_uk_ua:ntext',
				'seo_keywords_ru_ru:ntext',
				'seo_keywords_uk_ua:ntext',
				'status',
				'created_at',
				'updated_at',
			],
		]) ?>

    </div>
</div>
