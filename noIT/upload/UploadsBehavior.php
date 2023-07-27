<?php

namespace noIT\upload;

use Yii;
use yii\base\Behavior;
use yii\base\ErrorException;
use yii\base\InvalidConfigException;
use yii\db\BaseActiveRecord;
use yii\web\UploadedFile;

/**
 * UploadsBehavior automatically uploads file and fills the specified attribute
 * with a value of the name of the uploaded file.
 *
 * To use UploadsBehavior, insert the following code to your ActiveRecord class:
 *
 * ```php
 * use noIT\upload\file\UploadsBehavior;
 *
 * function behaviors()
 * {
 *     return [
 *         [
 *             'class' => UploadsBehavior::class,
 *             'attributes' => [
 *                 'attribute' => 'file',
 *                 'scenarios' => ['insert', 'update'],
 *                 'path' => '@webroot/upload/{id}',
 *                 'url' => '@web/upload/{id}',
 *             ],
 *         ],
 *     ];
 * }
 * ```
 *
 */
class UploadsBehavior extends Behavior
{
    /**
     * @event Event an event that is triggered after a file is uploaded.
     */
    const EVENT_AFTER_UPLOAD = 'afterUpload';

    /**
     * @var UploadsBehavior[] the attribute which holds the attachment.
     */
    public $attributes;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->attributes === null) {
            throw new InvalidConfigException('The "attributes" property must be set.');
        }

        $_attributes = [];
        foreach ($this->attributes as $key => $_attribute) {
            if (!isset($_attribute['attribute'])) {
                $_attribute['attribute'] = $key;
            } else {
                $key = $_attribute['attribute'];
            }

            if (!isset($_attribute['class'])) {
                $_attribute['class'] = UploadImageBehavior::className();
            }
            $_attributes[$key] = Yii::createObject($_attribute);
        }

        $this->attributes = $_attributes;
    }

    public function attach($owner)
    {
        parent::attach($owner);

        foreach ($this->attributes as &$attribute) {
            $attribute->attach($this->owner);
        }
    }

    public function getAttribute($attribute) {
        if (!isset($this->attributes[$attribute])) {
            throw new ErrorException("Attribute {attribute} nt exists");
        }
        return $this->attributes[$attribute];
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
        ];
    }

    /**
     * This method is invoked before validation starts.
     */
    public function beforeValidate()
    {
        foreach ($this->attributes as $_attribute) {
            $_attribute->beforeValidate();
        }
    }

    /**
     * This method is called at the beginning of inserting or updating a record.
     */
    public function beforeSave()
    {
        foreach ($this->attributes as $_attribute) {
            $_attribute->beforeSave();
        }
    }

    /**
     * This method is called at the end of inserting or updating a record.
     * @throws \yii\base\InvalidArgumentException
     */
    public function afterSave()
    {
        foreach ($this->attributes as $_attribute) {
            $_attribute->afterSave();
        }
    }

    /**
     * This method is invoked after deleting a record.
     */
    public function afterDelete()
    {
        foreach ($this->attributes as $_attribute) {
            $_attribute->afterDelete();
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
        $_attribute = $this->getAttribute($attribute);
        return $_attribute->getUploadPath($attribute, $old);
    }

    /**
     * Returns file url for the attribute.
     * @param string $attribute
     * @return string|null
     */
    public function getUploadUrl($attribute)
    {
        $_attribute = $this->getAttribute($attribute);

        return $_attribute->getUploadUrl($attribute);
    }

//    public function getUploadedThumbs() {
//
//    }

    /**
     * Returns the UploadedFile instance.
     * @return UploadedFile
     */
    protected function getUploadedFile()
    {
        return $this->_file;
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

    /**
     * @param string $attribute
     * @param string $profile
     * @return string|null
     */
    public function getThumbUploadUrl($attribute, $profile = 'thumb')
    {
        $behavior = $this->getAttribute($attribute);

        return $behavior->getThumbUploadUrl($attribute, $profile);
    }

    /**
     * @param string $attribute
     * @param string $profile
     * @param boolean $old
     * @return string
     */
    public function getThumbUploadPath($attribute, $profile = 'thumb', $old = false)
    {
        $behavior = $this->getAttribute($attribute);

        return $behavior->getThumbUploadPath($attribute, $profile, $old);
    }
}
