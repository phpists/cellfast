<?php

namespace noIT\feature\widgets;

use Yii;
use yii\helpers\Url;
use yii\base\Widget;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use noIT\feature\helpers\FeatureHelper;

class FeatureWidget extends Widget {
	/**
	 * Список данных
	 * @var ActiveRecord[] $items
	 */
	public $items;

	/**
	 * Параметры списка элементов для представления
	 * @var array
	 */
	public $options = [];

	/**
	 * Параметры отдельного элемента для представления
	 * @var array
	 */
	public $itemOptions = [];

	/**
	 * Базовый роут. По-умолчанию - текущий роут.
	 *
	 * @var $routeBase array
	 */
	public $routeBase;

	/**
	 * Ключ параметра фильтра
	 *
	 * @var $routeFilterKey string
	 */
	public $routeFilterKey = '@routeFilter';

	/**
	 * Можно задать артибут или ключ массива опции для передачи в urlManager
	 * Если null - передается элемент (объект, элемент массива) целиком
	 *
	 * @var $itemAttributeValue null|string
	 */
	public $itemAttributeValue;

	/**
	 * Артибут или ключ массива опции для отображения (нужен в случае стандартных вьюх виджета)
	 *
	 * @var $itemAttributeLabel string
	 */
	public $itemAttributeLabel = 'name';

	/**
	 * Тип формирования ссылки опций виджета
	 * * single - фильтр одиночка
	 * * toggle - наборной "умный" фильтр
	 *
	 * @var $routeFilterType string
	 */
	public $routeFilterType = 'single';

	/**
	 * Представление списка опций
	 *
	 * @var string
	 */
	public $view = 'feature-filter';

	/**
	 * Представление отдельной опции
	 *
	 * @var string
	 */
	public $viewItem = '_feature-link';

	public function init() {
		parent::init();

		$this->routeFilterType = \Yii::getAlias($this->routeFilterType);

		if (null === $this->routeBase) {
			$this->routeBase = Yii::$app->urlManager->parseRequest(Yii::$app->request);
		}
	}

	public function run() {
		if (!$this->items) {
			return;
		}

		/**
		 * Формируем данные для вывода во вьюхе
		 */
		/** @var array $items */
		$items = [];
		foreach ($this->items as $item) {
			if ( !($_value = ArrayHelper::getValue($item, $this->itemAttributeValue)) ) {
				/** TODO - Set exception message */
				throw new Exception("");
			}

			switch ($this->routeFilterType) {
				case 'toggle':
					// TODO - Реализовать возможность вывода ссылок-переключателей
//					$_link = toggleParams($this->routeBase, [$this->routeFilterKey => $_value]);
					break;
				default:
					$_link = ArrayHelper::merge($this->routeBase, [$this->routeFilterKey => $_value]);
					break;
			}
			$items[] = [
				'route' => $_link,
				'label' => ArrayHelper::getValue($item, $this->itemAttributeLabel),
				'item' => $item,
			];
		}
		return $this->render($this->view, [
			'items' => $items,
			'options' => $this->options,
			'itemOptions' => $this->itemOptions,
			'viewItem' => $this->viewItem,
		]);
	}
}