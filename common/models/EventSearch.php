<?php

namespace common\models;


/**
 * EventSearch represents the model behind the search form about `common\models\Event`.
 *
 * @property integer $timestamp_to
 * @property integer $timestamp_from
 */

class EventSearch extends Event
{
	use BaseContentSearchTrait;
	public $modelTableName = 'common\models\Event';
}