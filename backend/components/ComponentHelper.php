<?php
namespace backend\components;

class ComponentHelper extends \yii\base\Component
{
	public function getSortOrderRange()
	{
		$range = [];

		for($i = 1; $i < 101; $i++) {
			$range[] = [ 'value' => $i, 'label' => $i ];
		}

		return $range;
	}

	public function getStatus()
	{
		$status = [];

		$status[] = [ 'value' => 'offline', 'label' => 'Offline'];
		$status[] = [ 'value' => 'online', 'label' => 'Online'];

		return $status;
	}


	public function getHours()
	{
		$hours = [];

		for($i = 0; $i < 24; $i++) {

			if($i < 10) {
				$label = "0{$i}";
			}  else {
				$label = $i;
			}

			$hours[] = [ 'value' => $i, 'label' => $label];

		}

		return $hours;
	}

	public function getMinutes()
	{
		$minutes = [];

		for($i = 0; $i < 60; $i++) {

			if($i < 10) {
				$label = "0{$i}";
			} else {
				$label = $i;
			}

			$minutes[] = [ 'value' => $i, 'label' => $label];

		}

		return $minutes;
	}

	public function getProjects()
	{
		return [
			[
				'name' => 'Cellfast',
				'alias' => 'cellfast',
			],
			[
				'name' => 'Bryza',
				'alias' => 'bryza',
			],
			[
				'name' => 'Ines',
				'alias' => 'ines',
			],

		];
	}

}