<?php
namespace noIT\feature\migrations;

use common\helpers\AdminHelper;
use noIT\junction\migrations\migrate_junction;

class migrate_single_feature_junction extends migrate_junction
{
    protected $sourceModelName = 'noIT\feature\models\SingleFeature';
}
