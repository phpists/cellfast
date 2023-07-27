<?php

use yii\db\Migration;

/**
 * Class m171203_054031_location
 */
class m171203_054031_location extends \noIT\location\migrations\migrate_location {
	protected $modelCountryName = 'common\models\LocationCountry';
	protected $modelRegionName = 'common\models\LocationRegion';
	protected $modelPlaceName = 'common\models\LocationPlace';
}
