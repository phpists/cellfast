<?php

namespace noIT\feature\components;

use common\models\ProductFeatureEntity;
use Yii;
use yii\base\Component;
use noIT\core\helpers\Html;
use yii\web\NotFoundHttpException;

/**
 * Class FeatureComponent
 * @package noIT\feature\components
 *
 * @param $symbolByOperator string
 * @param $operatorBySymbol string
 */
class FeatureComponent extends Component {
	public $groupModel = 'noIT\feature\models\FeatureGroup';
	public $valueModel = 'noIT\feature\models\Feature';
	public $groupFieldName = 'group_id';

	/**
	 * @var array $operators Параметры формирования фильтров в URL в приложении
	 * https://noit.worksection.com/project/194561/5785760/5791255/
	 */
	public $operators = [
		'equally' => '=',
		'and' => ';',
		'or' => ',',
		'not' => '!',
		'interval' => '~',
	];

	public $pricePart = 'price';

	public function getSymbolByOperator($key) {
		return $this->operators[$key];
	}

	public function getOpertorBySymbol($key) {
		return array_search($key, $this->operators);
	}

	public function getPart($key) {
		return $this->operators[$key];
	}

	/**
	 * Формирует массив фильтра на основании плоского фильтра сущностей Feature[]
	 *
	 * @param $values mixed
	 */
	public function getFilterByValues($values, $prices = null) {
		$result = [
			'features' => [],
			'prices' => [],
		];

		foreach ($values as $_value) {
			$value = $_value;
			if (!is_object($value) && !($value = $this->getValue($value)) ) {
				continue;
				throw new NotFoundHttpException("1");
			}
			$result['features'][$value->{$this->groupFieldName}][$value->id] = $value;
		}

		if (null === $prices) {
			$prices = [null, null];
		}

		// TODO - Validate values
		if (!empty($prices[0])) {
			$result['prices'][0] = floatval($prices[0]);
		}
		if (!empty($prices[1])) {
			$result['prices'][1] = floatval($prices[1]);
		}

		return $result;
	}

	/**
	 * Формирует строку фильтра для использования в URL
	 * https://noit.worksection.com/project/194561/5785760/5791255/
	 *
	 * @param array $values
	 *
	 * @return string
	 */
	public function createFilter($filter) {
		if (!is_array($filter)) {
			return false;
		}

		$result = [];

		if (!empty($filter['prices'])) {
			$result[$this->pricePart] = implode($this->getSymbolByOperator('interval'), $filter['prices']);
		}

		foreach ($filter['features'] as &$item) {
			ksort($item);
		}

		ksort($filter['features']);

		foreach ($filter['features'] as $group_id => $_values) {
			if ( !($group = $this->getGroup($group_id)) ) {
				// TODO - Exception or watchdog
				continue;
			}
			$group_slug = $group->slug ? : $group->id;
			$section = [];
			foreach ($_values as $_value) {
				$section[] = $_value->slug ? : $_value->id;
			}
			if ($section) {
				$result[$group_slug] = implode($this->getSymbolByOperator('or'), $section);
			}
		}

		return implode($this->getSymbolByOperator('and'), $this->_implodeFilter($result));
	}

	public function parseFilter($filterString) {
		$features = $prices = [];
		foreach (explode($this->getSymbolByOperator('and'), $filterString) as $section) {
			list($group_slug, $values) = explode($this->getSymbolByOperator('equally'), $section);
			if ($group_slug == $this->pricePart) {
				$prices = explode($this->getSymbolByOperator('interval'), $values);
				if (count($prices) != 2) {
					throw new NotFoundHttpException("2");
				}
			} else {
				if ( !($group = $this->getGroup($group_slug, 'slug')) && !($group = $this->getGroup($group_slug, 'id')) ) {
					// Если нет группы свойств ни по slug ни по id
					throw new NotFoundHttpException("3");
				}
				foreach (explode($this->getSymbolByOperator('or'), $values) as $value_slug) {
					if ( !($value = $this->getValue($value_slug, 'slug')) && !($value = $this->getValue($value_slug, 'id')) ) {
						// Если нет значения свойства ни по slug ни по id
						throw new NotFoundHttpException("4");
					}
					$features[$group->id][$value->id] = $value;
				}
			}
		}
		return [
			'features' => $features,
			'prices' => $prices,
		];
	}

	/**
	 * Формирует ссылку для фильтра с типом "переключатель"
	 *
	 * @param array $params Текущие параметры
	 * @param array $toggle Какие параметры нужно переключить
	 *
	 * @return mixed
	 */
	public function toggleParams($toggle, $route = null) {

		if (null === $route) {
			$route = Yii::$app->urlManager->getCurrentRoute();
		}

		// Обрабатываем свойства
		if (!empty($toggle['features'])) {
			if ( ! is_array( $toggle['features'] ) ) {
				$toggle['features'] = [$toggle['features']];
			}

			$groups = [];
            foreach ( $toggle['features'] as $value ) {
                $groups[$value->{$this->groupFieldName}] = $value->identity->group;
            }

			if ( ! isset( $route['filter'] ) ) {
				$route['filter'] = $this->getFilterByValues(@$toggle['features'], @$toggle['prices']);
			} else {
				foreach ( $toggle['features'] as $value ) {
					$group_id = $value->{$this->groupFieldName};

					if ( isset($route['filter']['features'][$group_id]) && array_key_exists($value->id, $route['filter']['features'][$group_id]) ) {
						unset($route['filter']['features'][$group_id][$value->id]);
					} else {
                        if ($groups[$group_id]->multiple == 0) {
                            $route['filter']['features'][$group_id] = [];
                        }

						$route['filter']['features'][$group_id][$value->id] = $value;
					}
				}
			}
		}

		return $route;
	}

	protected function _implodeFilter($filterArray) {
		$result = [];
		foreach ($filterArray as$key=> $value) {
			$result[] =$key. $this->getSymbolByOperator('equally') . $value;
		}
		return $result;
	}

	/**
	 * Используется, например, в хлебных крошках
	 */
	public function getFilterLabel($filter = null, $links = true) {
		if (null === $filter) {
			$route = Yii::$app->urlManager->getCurrentRoute();
			if ( ! isset( $route['filter'] ) ) {
				return false;
			}
			$filter = $route['filter'];
		}

		$result = [];
		if (isset($filter['features'])) {
			foreach ($filter['features'] as $group_id => $values) {
				$section = [];
				foreach ($values as $value) {
					$section[] = $links ? Html::a($value->value_label, Yii::$app->productFeature->toggleParams(['features' => [$value]]), ['encode' => false, 'title' => Yii::t('app', 'Remove this conditions')]) : $value->value_label;
				}
				if ($section) {
					$result[] = $this->getGroup($group_id)->name .": ". implode(', ', $section);
				}
			}
		}

		return implode('; ', $result);
	}

	public function getValue($id, $fieldName = 'id') {
		return call_user_func([$this->valueModel, 'findOne'], [$fieldName => $id]);
	}

	public function getGroup($id, $fieldName = 'id') {
		return call_user_func([$this->groupModel, 'findOne'], [$fieldName => $id]);
	}
}
