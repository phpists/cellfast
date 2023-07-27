<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\WriteToUsFeedback */
/* @var $contactsData array[] */

\bryza\assets\ContactsAsset::register($this);

$this->title = Yii::t('app', 'Contacts');
$this->params['breadcrumbs'][] = $this->title;

$modelIndex = 1;

?>
<section class="contacts">
    <div class="container">

		<?= \bryza\widgets\Breadcrumbs::widget([
			'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
		]) ?>

        <div class="contacts__title"><span><?= Yii::t('app', 'Contacts') ?></span></div>

		<?php foreach($contactsData as $singleContactsData) : ?>

			<?= $this->render( '_item', [
				'model' => $model,
				'modelID' => "write-to-us-form-{$modelIndex}",
				'contactsData' => $singleContactsData,
			]) ?>

			<?php $modelIndex++ ?>

		<?php endforeach ?>

    </div>
</section>
