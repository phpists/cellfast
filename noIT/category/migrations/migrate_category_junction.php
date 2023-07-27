<?php
namespace noIT\category\migrations;

use noIT\junction\migrations\migrate_junction;

class migrate_category_junction extends migrate_junction
{
    protected $sourceModelName = 'noIT\category\models\Category';
}
