<?php
namespace common\components\projects;

use Yii;
use yii\base\Model;
use yii\base\Exception;

class Project extends Model {
    /**
     * @var $alias string
     */
    public $alias;
    /**
     * @var $name string
     */
    public $name;
    /**
     * @var $url string
     */
    public $url;

    public $params;

    public function init()
    {
        parent::init();

        if (!$this->alias) {
            throw new Exception("You must specify an alias for the project.");
        }

        if (!$this->name) {
            $this->name = ucfirst($this->alias);
        }

        if ($this->params === null) {
            $this->loadPrarams();
        }
    }

    protected function loadPrarams() {
        if ( file_exists(Yii::getAlias("@{$this->alias}/config/project.php")) ) {
            $this->params = require(Yii::getAlias("@{$this->alias}/config/project.php"));
            if (!$this->url && isset($this->params['url'])) {
                $this->url = $this->params['url'];
                unset($this->params['url']);
            }
        }
    }
}