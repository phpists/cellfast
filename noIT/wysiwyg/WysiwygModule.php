<?php

namespace noIT\wysiwyg;

use Yii;
use yii\helpers\Url;
use yii\base\Exception;

/**
 * wysiwyg module definition class
 */
class WysiwygModule extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'noIT\wysiwyg\controllers';

	/** TODO - Внесен хак в JS-скрипт (в yii 2.0.13 обновилась версия jQuery и вылез баг) */
	/** TODO - Отследить обновление  \vova07\imperavi\Widget */
    public $widgetClass = 'vova07\imperavi\Widget';

	public $params = [];

	protected $widgetParams;

	protected $defaultParams = [
		'settings' => [
		    'minHeight' => 100,
		    'maxHeight' => 300,
		    'plugins'   => [
			    'fullscreen',
		    ],
		],
    ];

    public $uploadPath;

    public $uploadUrl;

    public $uploadAction = ['/wysiwyg/filesystem/image-upload'];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (null === $this->widgetClass) {
        	throw new Exception("WYSIWYG widget class-name are not empty");
        }

        // Conver route to string
        if (is_array($this->uploadAction)) {
	        $this->uploadAction = Url::to($this->uploadAction);
	        $this->uploadPath = Yii::getAlias($this->uploadPath);
	        $this->uploadUrl = Yii::getAlias($this->uploadUrl);
        }
    }

	public function widgetParams() {
	    if (null === $this->widgetParams) {
		    $this->widgetParams = array_merge($this->params, $this->defaultParams);
		    if ($this->uploadAction) {
		    	$this->widgetParams['settings']['imageUpload'] = $this->uploadAction;
		    }
	    }
	    return $this->widgetParams;
    }
}
