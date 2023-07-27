<?php
namespace noIT\language;

use Yii;
use yii\base\Model;
use yii\base\Exception;

class LanguageModel extends Model {
    /**
     * @var $code string
     */
    public $code;
    /**
     * @var $shortcode string
     */
    public $shortcode;
    /**
     * @var $name string
     */
    public $name;
    /**
     * @var $short string
     */
    public $short;
    /**
     * @var $url string
     */
    public $url;
    /**
     * @var $url string
     */
    public $suffix;

    public function init()
    {
        parent::init();

        if (!$this->code) {
            throw new Exception("You must specify an code for the project.");
        }

        if (!$this->shortcode) {
            $this->shortcode = strtolower(explode('-', $this->code)[0]);
        }

        if (!$this->url) {
            $this->url = strtolower($this->code);
        }

        if (!$this->name) {
            $this->name = ucfirst($this->code);
        }

        if (!$this->short) {
            $this->short = $this->name;
        }

        if (!$this->suffix) {
            $this->suffix = strtolower(str_replace('-', '_', $this->code));
        }
    }

    public function getEntityField($model, $fieldName) {
        $fieldName = "{$fieldName}_{$this->suffix}";
        return $model->$fieldName;
        /*if (property_exists($model, $fieldName)) {
            return $model->$fieldName;
        } else {
            return null;
        }*/
    }
}