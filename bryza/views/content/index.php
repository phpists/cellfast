<?php

\bryza\assets\ContentsAsset::register($this);

/**
 * Контейнер вывода списка элементов (используется в статья и новостях как общий шаблон).
 */

/* @var $this yii\web\View */
/* @var $dataProvider \yii\data\ActiveDataProvider */

use yii\helpers\Url;
use yii\web\View;

/** TODO - SEO title and meta-tags */
$this->title = $__params['items'];

$this->params['breadcrumbs'][] = $__params['items'];

?>
<section class="content <?= $__params['id']?>-index">
    <div class="container">

		<?= \bryza\widgets\Breadcrumbs::widget([
			'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			'options' => []
		]) ?>

		<?= \bryza\widgets\Alert::widget() ?>

		<?= \bryza\widgets\PaginationWidget::widget([
			'pagination' => $dataProvider->pagination,
			'itemClassName' => 'pagination__item',
			'prevClassName' => 'pagination__arr',
			'nextClassName' => 'pagination__arr',
		]) ?>

        <div class="content__title"><span><?= $this->title ?></span></div>

        <div id="<?= $__params['items-wrapper'] ?>" class="content__body <?= $__params['id'] ?>-items">
			<?= $this->render('_items', [
				'dataProvider' => $dataProvider,
				'__params' => $__params,
			]) ?>
        </div>

        <div class="content__footer">
			<?= \bryza\widgets\PaginationWidget::widget([
				'pagination' => $dataProvider->pagination,
			]) ?>
        </div>
    </div>
</section>
