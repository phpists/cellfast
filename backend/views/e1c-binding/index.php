<?php

/** @var $this yii\web\View */
/** @var $active string */
/** @var $searchModel \backend\models\E1cGroupOfGoodSearch */
/** @var $dataProvider \yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'E1c binding'), 'url' => 'e1c-binding/index'];

?>
<div class="custom-form-section">
    <div class="custom-form-section-box">
        <div class="e1c-binding-index">
            <div class="e1c-binding-tabs">
				<?= $this->render('_tabs', [
					'active' => $active,
				])?>
            </div>

            <div class="e1c-binding-content">
				<?= $this->render("{$active}/index", [
					'active' => $active,
					'searchModel' => $searchModel,
					'dataProvider' => $dataProvider,
				])?>
            </div>
        </div>
    </div>
</div>
