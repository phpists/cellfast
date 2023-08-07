<?php

/**
 * Шаблон вывода списка элементов. Может использоваться для ajax подгрузки
 */

use yii\web\View;

/* @var $this yii\web\View */
/* @var $model \ines\models\Article */
/* @var $__params string[] */

?>
<?php if (!$dataProvider->count) : ?>
    <div class="empty-result">
		<?= $__params['empty']?>
    </div>
<?php else : ?>
	<?php foreach (array_values($dataProvider->models) as $i => $model) : ?>
		<?= $this->render('_item', [
			'model' => $model,
			'row_index' => $i,
			'__params' => $__params,
		])?>
	<?php endforeach ?>
<?php endif ?>
