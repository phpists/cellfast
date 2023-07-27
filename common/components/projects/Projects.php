<?php
namespace common\components\projects;

use Yii;
use yii\base\Component;

class Projects extends Component
{
    /** @var $projects Project[] */
    public $projects;

    public $current;

    public function init()
    {
        parent::init();

        $projects = $this->projects;

        $this->projects = [];

        foreach ($projects as $project) {
            if (is_string($project)) {
                $this->projects[$project] = new Project(['alias' => $project]);
            } elseif (is_array($project)) {
                $project = new Project($project);
                $this->projects[$project->alias] = $project;
            } else {
                $this->projects[$project->alias] = $project;
            }
        }

        if ($this->current === null) {
            $this->current = $this->getProject(Yii::$app->id);
        }
    }

    public function getProject($alias)
    {
        return isset($this->projects[$alias]) ? $this->projects[$alias] : null;
    }
}