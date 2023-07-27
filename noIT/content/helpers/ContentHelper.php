<?php

namespace noIT\content\helpers;

class ContentHelper {
	public static function defaultRoutes($controllerId, $actionListId = 'index', $actionViewId = 'view', $partList = null, $partView = null, $partsTag = null, $pathTag = 'tag') {
		if (null === $partList) {
			$partList = "{$controllerId}s";
		}
		if (null === $partView) {
			$partView = "{$controllerId}";
		}
		if (null === $partsTag) {
			$partsTag = "{$partList}";
		}

		// Отдельная статья
		$routs = [
			[
				'class' => 'noIT\content\components\ContentUrlRule',
				'pattern' => "$partView/<url>",
				'route' => "$controllerId/$actionViewId",
				'suffix' => '.html',
			],
		];
		if ($partsTag) {
			// Список статей с фильтром
			$routs[] = [
				'class'   => 'noIT\content\components\ContentUrlRule',
				'pattern' => "{$partsTag}/<{$pathTag}>",
				'route'   => "$controllerId/$actionListId",
				'filters' => [
					[
						'class' => 'noIT\content\components\CheckPaginatorUrlFilter',
						'part'  => 'page',
					],
					[
						'class' => 'noIT\core\components\NotEmptyUrlFilter',
						'part'  => $pathTag,
					],
					[
						'class' => 'noIT\feature\components\FeatureSingleUrlFilter',
						'part'  => $pathTag,
					],
				],
			];
		}

		// Список статей
		$routs[] = [
			'class' => 'noIT\content\components\ContentUrlRule',
			'pattern' => "$partList",
			'route' => "$controllerId/$actionListId",
			'filters' => [
				[
					'class' => 'noIT\content\components\CheckPaginatorUrlFilter',
					'part' => 'page',
				],
			],
		];

		return $routs;
	}
}