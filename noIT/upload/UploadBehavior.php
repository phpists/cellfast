<?php

namespace noIT\upload;

use Closure;
use Yii;
use yii\base\Behavior;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * UploadBehavior automatically uploads file and fills the specified attribute
 * with a value of the name of the uploaded file.
 *
 * To use UploadBehavior, insert the following code to your ActiveRecord class:
 *
 * ```php
 * use noIT\upload\file\UploadBehavior;
 *
 * function behaviors()
 * {
 *     return [
 *         [
 *             'class' => UploadBehavior::class,
 *             'attribute' => 'file',
 *             'scenarios' => ['insert', 'update'],
 *             'path' => '@webroot/upload/{id}',
 *             'url' => '@web/upload/{id}',
 *         ],
 *     ];
 * }
 * ```
 *
 */
class UploadBehavior extends Behavior
{
	/**
	 * @event Event an event that is triggered after a file is uploaded.
	 */
	const EVENT_AFTER_UPLOAD = 'afterUpload';

    /**
     * @var string the attribute which holds the attachment.
     */
    public $attribute;
    /**
     * @var array the scenarios in which the behavior will be triggered
     */
    public $scenarios = [];
    /**
     * @var string the base path or path alias to the directory in which to save files.
     */
    public $path;
    /**
     * @var string the base URL or path alias for this file
     */
    public $url;
    /**
     * @var bool Getting file instance by name
     */
    public $instanceByName = false;
    /**
     * @var boolean|callable generate a new unique name for the file
     * set true or anonymous function takes the old filename and returns a new name.
     * @see self::generateFileName()
     */
    public $generateNewName = true;
    /**
     * @var boolean If `true` current attribute file will be deleted
     */
    public $unlinkOnSave = true;
    /**
     * @var boolean If `true` current attribute file will be deleted after model deletion.
     */
    public $unlinkOnDelete = true;
    /**
     * @var boolean $deleteTempFile whether to delete the temporary file after saving.
     */
    public $deleteTempFile = true;

    /**
     * @var UploadedFile[] the uploaded file instance.
     */
    public $_files;

    public $multiple = false;

    public $serialized = false;

    public $_value;


    /**
     * @var string replace|prepend|append
     */
    public $addType = 'prepend';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->attribute === null) {
            throw new InvalidConfigException('The "attribute" property must be set.');
        }
        if ($this->path === null) {
            throw new InvalidConfigException('The "path" property must be set.');
        }
        if ($this->url === null) {
            throw new InvalidConfigException('The "url" property must be set.');
        }
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            BaseActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            BaseActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            BaseActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
            BaseActiveRecord::EVENT_AFTER_FIND => 'afterFind',
        ];
    }

    public function afterFind() {
        /** @var BaseActiveRecord $model */
        $model = $this->owner;

        $model->setAttribute($this->attribute, $this->setValue($model->getAttribute($this->attribute)));
    }

    /**
     * This method is invoked before validation starts.
     */
    public function beforeValidate()
    {
        /** @var BaseActiveRecord $model */
        $model = $this->owner;

        if (in_array($model->scenario, $this->scenarios)) {
            if (($file = $model->getAttribute($this->attribute)) instanceof UploadedFile) {
                $this->_files = $file;
            } else {
                if ($this->instanceByName === true) {
                    $this->_files = $this->multiple ? UploadedFile::getInstancesByName($this->attribute) : UploadedFile::getInstanceByName($this->attribute);
                } else {
                    $this->_files = $this->multiple ? UploadedFile::getInstances($model, $this->attribute) : UploadedFile::getInstance($model, $this->attribute);
                }
            }

            if (!is_array($this->_files)) {
                $this->_files = [$this->_files];
            }

            $attributeValue = [];
            foreach ($this->_files as $_file) {
                if ($_file instanceof UploadedFile) {
                    $_file->name = $this->getFileName($_file);
                }

                $attributeValue[] = $_file;
            }

            if ($attributeValue) {
                $model->setAttribute($this->attribute, ($this->multiple ? $attributeValue : $attributeValue[0]));
            }
        }
    }

    /**
     * This method is called at the beginning of inserting or updating a record.
     */
    public function beforeSave()
    {
        /** @var BaseActiveRecord $model */
        $model = $this->owner;

        if (in_array($model->scenario, $this->scenarios)) {
            $attributeValue = [];

            foreach ($this->_files as $_file) {
                if ($_file instanceof UploadedFile) {
                    if (!$model->getIsNewRecord() && $model->isAttributeChanged($this->attribute)) {
                        if ($this->unlinkOnSave === true) {
                            /** TODO Удаление картинок при изменении */
//                            $this->delete($this->attribute, true);
                        }
                    }

                    $attributeValue[] = $_file->name;
                } else {
                    // Protect attribute
                    unset($model->{$this->attribute});
                    return;
                }
            }

            // Если не множественное - один файл
            if ($this->multiple) {
                if ($this->addType == 'prepend') {
                    $attributeValue = array_merge($attributeValue, $this->setValue($model->getOldAttribute($this->attribute)));
                } elseif ($this->addType == 'append') {
                    $attributeValue = array_merge($this->setValue($model->getOldAttribute($this->attribute)), $attributeValue);
                }
            } else {
                $attributeValue = isset($attributeValue[0]) ? $attributeValue[0] : null;
            }

            // Сериализовать ли запись в БД
            if ($this->serialized) {
                $attributeValue = !empty($attributeValue) ? serialize($attributeValue) : null;
            }

            if ($attributeValue !== null) {
                $model->setAttribute($this->attribute, $attributeValue);
            } else {
                unset($model->{$this->attribute});
            }
        }

        /** TODO Удаление картинок с заменной */
        /*else {
            if (!$model->getIsNewRecord() && $model->isAttributeChanged($this->attribute)) {
                if ($this->unlinkOnSave === true) {
                    $this->delete($this->attribute, true);
                }
            }
        }*/
    }

    /**
     * This method is called at the end of inserting or updating a record.
     * @throws \yii\base\InvalidArgumentException
     */
    public function afterSave()
    {
        $paths = $this->getUploadPath($this->attribute);

        if (!is_array($paths)) {
            $paths = [$paths];
        }

        if (!$this->_files) {
            return;
        }

        foreach ($this->_files as $i => $_file) {
            if ($_file instanceof UploadedFile) {
                if (!isset($paths[$i])) {
                    continue;
                }
                $path = $paths[$i];
                if (is_string($path) && FileHelper::createDirectory(dirname($path))) {
                    $this->save($_file, $path);
                } else {
                    throw new InvalidArgumentException(
                        "Directory specified in '$path' attribute doesn't exist or cannot be created."
                    );
                }
            }
        }
        $this->afterUpload();
    }

    /**
     * This method is invoked after deleting a record.
     */
    public function afterDelete()
    {
        $attribute = $this->attribute;
        if ($this->unlinkOnDelete && $attribute) {
            $this->delete($attribute);
        }
    }

    /**
     * Returns file path for the attribute.
     * @param string $attribute
     * @param boolean $old
     * @return string|null the file path.
     */
    public function getUploadPath($attribute, $old = false)
    {
        /** @var BaseActiveRecord $model */
        $model = $this->owner;
        $path = $this->resolvePath($this->path);
        $fileName = ($old === true) ? $this->setValue($model->getOldAttribute($attribute)) : $model->$attribute;

        if (is_array($fileName)) {
            $result = [];
            foreach ($fileName as $fileNameItem) {
                if ($fileNameItem) {
                    $result[] = Yii::getAlias($path . '/' . $fileNameItem);
                }
            }
        } else {
            $result = Yii::getAlias($path . '/' . $fileName);
        }

        return $result;
    }

    /**
     * Returns file url for the attribute.
     * @param string $attribute
     * @param boolean $old
     * @return string|null
     */
    public function getUploadUrl($attribute, $old = false)
    {
        /** @var BaseActiveRecord $model */
        $model = $this->owner;
        $url = $this->resolvePath($this->url);
        $fileName = ($old === true) ? $this->setValue($model->getOldAttribute($attribute)) : $model->$attribute;

        if (is_array($fileName)) {
            $result = [];
            foreach ($fileName as $fileNameItem) {
                if ($fileNameItem) {
                    $result[] = Yii::getAlias($url . '/' . $fileNameItem);
                }
            }
        } else {
            $result = Yii::getAlias($url . '/' . $fileName);
        }

        return $result;
    }

    public function getValue() {
        return $this->_value;
    }

    public function setValue($value) {
        if (null === $this->_value) {
            if ($this->serialized) {
                if (is_string($value)) {
                    $value = unserialize($value);
                }
            }

            if ($this->multiple && empty($value)) {
                $value = [];
            }

            $this->_value = $value;
        }
        return $this->_value;
    }

    /**
     * Returns the UploadedFile instance.
     * @return UploadedFile|UploadedFile[]
     */
    protected function getUploadedFile()
    {
        return $this->_files;
    }

    /**
     * Replaces all placeholders in path variable with corresponding values.
     */
    protected function resolvePath($path)
    {
        /** @var BaseActiveRecord $model */
        $model = $this->owner;
        return preg_replace_callback('/{([^}]+)}/', function ($matches) use ($model) {
            $name = $matches[1];
            $attribute = ArrayHelper::getValue($model, $name);
            if (is_string($attribute) || is_numeric($attribute)) {
                return $attribute;
            } else {
                return $matches[0];
            }
        }, $path);
    }

    /**
     * Saves the uploaded file.
     * @param UploadedFile $file the uploaded file instance
     * @param string $path the file path used to save the uploaded file
     * @return boolean true whether the file is saved successfully
     */
    protected function save($file, $path)
    {
        return $file->saveAs($path, $this->deleteTempFile);
    }

    /**
     * Deletes old file.
     * @param string $attribute
     * @param boolean $old
     */
    protected function delete($attribute, $old = false)
    {
        $paths = $this->getUploadPath($attribute, $old);
        foreach ($paths as $path) {
            if (is_files($path)) {
                unlink($path);
            }
        }
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    protected function getFileName($file)
    {
        if ($this->generateNewName) {
            return $this->generateNewName instanceof Closure
                ? call_user_func($this->generateNewName, $file)
                : $this->generateFileName($file);
        } else {
            return $this->sanitize($file->name);
        }
    }

    /**
     * Replaces characters in strings that are illegal/unsafe for filename.
     *
     * #my*  unsaf<e>&file:name?".png
     *
     * @param string $filename the source filename to be "sanitized"
     * @return boolean string the sanitized filename
     */
    public static function sanitize($filename)
    {
        return str_replace([' ', '"', '\'', '&', '/', '\\', '?', '#'], '-', $filename);
    }

    /**
     * Generates random filename.
     * @param UploadedFile $file
     * @return string
     */
    protected function generateFileName($file)
    {
        return uniqid() . '.' . $file->extension;
    }

    /**
     * This method is invoked after uploading a file.
     * The default implementation raises the [[EVENT_AFTER_UPLOAD]] event.
     * You may override this method to do postprocessing after the file is uploaded.
     * Make sure you call the parent implementation so that the event is raised properly.
     */
    protected function afterUpload()
    {
        $this->owner->trigger(self::EVENT_AFTER_UPLOAD);
    }
}
