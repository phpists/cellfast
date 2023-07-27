<?php
namespace noIT\core\helpers;

use kartik\select2\Select2;
use kartik\widgets\SwitchInput;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Migration;
use noIT\language\LanguageModel;
use dosamigos\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

class AdminHelper {
    const FIELDNAME_PK = 'id';
    const FIELDNAME_SORT = 'sort_order';
    const FIELDNAME_STATUS = 'status';
    const FIELDNAME_IMAGE = 'image';
    const FIELDNAME_IMAGES = 'images';
    const FIELDNAME_IMAGES_UPLOAD = 'imagesUpload';

    const FIELDVALUE_FALSE = 0;
    const FIELDVALUE_TRUE = 1;
    const FIELDNAME_PUBLISHED = 'published_at';

    const PAGESIZE_CONTENT = 12;

    public static function migrateTableOptions($driverName, $engine = 'InnoDB') {
        $tableOptions = null;

        if ($driverName === 'mysql') {
            $tableOptions = "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=$engine";
        }

        return $tableOptions;
    }

    /**
     * @var Migration $migrate
     */
    public static function migrateTablePK($migrate, $fieldName = null) {
        if (null === $fieldName) {
            $fieldName = self::FIELDNAME_PK;
        }
        return [
            $fieldName => $migrate->primaryKey(),
        ];
    }

    /**
     * @var Migration $migrate
     */
    public static function migrateTableStatus($migrate, $defaultValue, $fieldName = null, $fieldLength = 2) {
        if (null === $fieldName) {
            $fieldName = self::FIELDNAME_STATUS;
        }
        return [
            $fieldName => $migrate->smallInteger($fieldLength)->notNull()->defaultValue($defaultValue),
        ];
    }

    /**
     * @var Migration $migrate
     */
    public static function migrateTableSort($migrate, $defaultValue = 0, $fieldName = null, $fieldLength = 11) {
        if (null === $fieldName) {
            $fieldName = self::FIELDNAME_SORT;
        }
        return [
            $fieldName => $migrate->integer($fieldLength)->notNull()->defaultValue($defaultValue),
        ];
    }

    /**
     * @var Migration $migrate
     */
    public static function migrateTableTS($migrate) {
        return [
            'created_at' => $migrate->integer()->notNull(),
            'updated_at' => $migrate->integer()->notNull(),
        ];
    }

    /**
     * @var Migration $migrate
     */
    public static function migrateTablePublished($migrate) {
        return [
            self::FIELDNAME_PUBLISHED => $migrate->integer(),
        ];
    }

    /**
     * @var Migration $migrate
     */
    public static function migrateTableLangs($fieldName, $type, $languages = null) {
        $languages = self::getLanguages($languages);
        $fields = [];
        foreach ($languages as $language) {
            $fields[self::getLangField($fieldName, $language)] = $type;
        }
        return $fields;
    }

    public static function getLangField($fieldName, $language = null) {
        if (null === $language) {
            $language = Yii::$app->languages->current;
        } elseif (is_string($language)) {
            if (!array_key_exists($language, Yii::$app->languages->languages)) {
                return;
            }
            $language = Yii::$app->languages->languages[$language];
        }
        return "{$fieldName}_{$language->suffix}";
    }

    public static function getDefaultLangField($fieldName) {
        return self::getLangField($fieldName, Yii::$app->languages->default);
    }

    public static function LangsFieldLabel($fieldName, $language) {
        return Yii::t('app', $fieldName) ." ". Yii::t('app', $language->code);
    }
    
    public static function LangsField($fieldName, $languages = null) {
        $languages = self::getLanguages($languages);
        $fieldName = is_array($fieldName) ? $fieldName : [$fieldName];

        $result = [];
        foreach ($fieldName as $_field) {
            foreach ($languages as $language) {
                $result[] = self::getLangField($_field, $language);
            }
        }

        return $result;
    }

    public static function LangsFieldNames($fieldKey, $languages = null) {
        $languages = self::getLanguages($languages);

        $result = [];
        foreach ($languages as $language) {
            $result[] = self::getLangField($fieldKey, $language);
        }

        return $result;
    }

    public static function LangsFieldLabels($fieldKey, $fieldName, $languages = null) {
        $languages = self::getLanguages($languages);

        $result = [];
        foreach ($languages as $language) {
            $result[self::getLangField($fieldKey, $language)] = self::LangsFieldLabel($fieldName, $language);
        }

        return $result;
    }

    /**
     * @param null $languages
     * @return LanguageModel[]
     */
    public static function getLanguages($languages = null) {
        if ($languages === null) {
            $languages = Yii::$app->languages->languages;
        } elseif (!is_array($languages)) {
            $languages = [$languages];
        }
        return $languages;
    }

    public static function getPageSize($section = 'content') {
    	$key = "{$section}PageSize";
    	return isset(Yii::$app->params[$key]) ? Yii::$app->params[$key] : self::PAGESIZE_CONTENT;
    }

	/**
	 * WIDGETS
	 */

    /**
     * @param ActiveForm $form
     * @param ActiveRecord $model
     * @param string $fieldName
     * @param array $params
     * @return string
     */
    public static function getRedactorWidget($form, $model, $fieldName, $params = []) {
        return $form->field($model, $fieldName)->widget(CKEditor::className(), [
            'clientOptions' => [
                Yii::$app->languages->current->shortcode,
                'maxHeight' => 400,
            ]
        ]);
    }

    public static function getSelectWidget($form, $model, $fieldName, $data, $params = []) {

        if (!isset($params['multiple'])) {
            $params['multiple'] = false;
        }

        return $form->field($model, $fieldName)->widget(Select2::className(), [
                'data' => $data,
                'language' => Yii::$app->language,
                'options' => $params,
            ]
        );
    }

    public static function getImageUploadWidget($form, $model, $fieldName = null, $params = []) {
        $fieldName = $fieldName ? : self::FIELDNAME_IMAGE;
        if ( (!isset($params['preview']) || !$params['preview']) && $model->$fieldName) {
            $op[] = Html::img($model->getThumbUploadUrl($fieldName));
        }
        $op[] = $form->field($model, $fieldName)->fileInput();

        return implode("\n", $op);
    }

	/**
	 * @param ActiveRecord $model
	 * @param null $fieldName
	 * @param null $attributeName
	 * @param array $params
	 *
	 * @return string
	 */
    public static function getImagesUploadWidget($model, $fieldName = null, $attributeName = null, $params = [])
    {
        $fieldName = $fieldName ? : self::FIELDNAME_IMAGES_UPLOAD;
        $attributeName = $attributeName ? : self::FIELDNAME_IMAGES;
        $id = empty($params['id']) ? "$fieldName-{$model->imagesUploadEntity}-{$model->id}" : $params['id'];

        return "<label>". $model->getAttributeLabel($fieldName) ."</label>" . \kartik\file\FileInput::widget([
			'model' => $model,
			'attribute' => $fieldName,
			'options' => [
				'id' => $id,
				'accept' => 'image/*',
				'multiple' => true,
			],
			'pluginOptions' => [
				'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png'],
				'initialPreview' => \yii\helpers\ArrayHelper::getColumn($model->$attributeName, 'url_admin_trumb'),
				'initialPreviewAsData' => true,
				'initialPreviewConfig' => \yii\helpers\ArrayHelper::getColumn($model->$attributeName, 'config'),
				'overwriteInitial' => false,
				'showRemove' => true,
				'showUpload' => false,
				'uploadUrl' => \yii\helpers\Url::to(['images-upload', 'id' => $model->id]),
				'uploadAsync' => true,
				'deleteUrl' => \yii\helpers\Url::to(['images-delete', 'id' => $model->id]),
				'previewFileType' => 'image',
				'previewFileIcon' => '<i class="fa fa-file-photo-o text-warning"></i>',
				'initialCaption' => $model->getAttributeLabel($attributeName),
				/*'allowedPreviewTypes' => null,
				'previewFileIconSettings' => [
					'docx' => '<i class="fa fa-file-word-o text-primary"></i>',
					'xlsx' => '<i class="fa fa-file-excel-o text-success"></i>',
					'pptx' => '<i class="fa fa-file-powerpoint-o text-danger"></i>',
					'jpg' => '<i class="fa fa-file-photo-o text-warning"></i>',
					'pdf' => '<i class="fa fa-file-pdf-o text-danger"></i>',
					'zip' => '<i class="fa fa-file-archive-o text-muted"></i>',
				],*/

			],
			'pluginEvents' => [
				'filebatchselected' => 'function(event, files) {$(\'#'. $id .'\').fileinput("upload");}'
			],
		]);
    }

    public static function getBooleanWidget($form, $model, $fieldName, $value = null, $params = []) {
        $value = $value ? : self::FIELDVALUE_TRUE;
        return $form->field($model, $fieldName)->checkbox([ // widget(\kartik\switchinput\SwitchInput::className(), [
            'value' => $value,
            'pluginOptions' => [
                'onText' => '<i class="glyphicon glyphicon-eye-open"></i>',
                'offText' => '<i class="glyphicon glyphicon-eye-close"></i>',
            ]
        ]);
    }

    public static function getLangsField($fieldName = 'code') {
        $langs = [];
        foreach (Yii::$app->languages->languages as $language) {
            $langs[] = Yii::t('app', $language->$fieldName);
        }
        return $langs;
    }

    public static function getStatusWidget($form, $model, $fieldName = null, $value = null, $params = []) {
        $fieldName = $fieldName ? : self::FIELDNAME_STATUS;
        $value = $value ? : get_class($model)::STATUS_ACTIVE;

        /*return $form->field($model, $fieldName)->widget(SwitchInput::classname(), [
            'type' => SwitchInput::CHECKBOX,
	        'value' => $value,
        ]);*/

        return self::getBooleanWidget($form, $model, $fieldName, $value, $params);
    }

	public static function getDateWidget($form, $model, $fieldName = null, $value = null, $params = []) {
    	return $form->field($model, $fieldName)->widget(DateControl::classname(), [
		    'type' => DateControl::FORMAT_DATE,
		    'ajaxConversion' => true,
		    'widgetOptions' => array_merge($params, [
			    'pluginOptions' => [
				    'autoclose' => true
			    ]
		    ]),
	    ]);
	}

	/**
	 * Default WYSIWYG widget
	 *
	 * @param ActiveForm $form
	 * @param ActiveRecord $model
	 * @param string $fieldName
	 * @param array $params
	 *
	 * @return string
	 */
	public static function getWysiwygWidget($form, $model, $fieldName, $params = []) {
		/** TODO - Imperavi не вошел, CKEditor мощнее и привычней. Сделать загрузку/упраление файлами. Например, https://github.com/MihailDev/yii2-elfinder */
		return $form->field($model, $fieldName)->widget(CKEditor::className(), ['preset' => 'full',]);

		/*return $form->field($model, $fieldName)->widget(
			Yii::$app->getModule('wysiwyg')->widgetClass,
			array_merge($params, Yii::$app->getModule('wysiwyg')->widgetParams())
		);*/
	}
}
