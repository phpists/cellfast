<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use zxbodya\yii2\galleryManager\GalleryManager;

$this->title = 'Баннеры';

?>

<?php $form = ActiveForm::begin(); ?>

    <div class="custom-form-section">

        <div class="m-portlet m-portlet--tab">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
						<span class="m-portlet__head-icon m--hide">
						<i class="la la-gear"></i>
						</span>
                        <h3 class="m-portlet__head-text">Баннеры</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="custom-form-section-box">

			<?php foreach (Yii::$app->componentHelper->getProjects() as $project) : ?>

                <div class="row justify-content-between">
                    <div class="col like-box">
                        <label class="control-label" for="document-type">Баннеры проекта: (<?= $project['name'] ?>)</label>
						<?= GalleryManager::widget(
							[
								'model' => $model,
								'behaviorName' => "gallery__{$project['alias']}",
								'apiRoute' => 'banner/galleryApi'
							]
						);?>
                    </div>
                </div>

                <br>

			<?php endforeach ?>

        </div>
    </div>

    <br>
<?php ActiveForm::end(); ?>