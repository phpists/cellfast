<?php
namespace noIT\cache;

use Yii;
use noIT\content\models\Content;
use yii\caching\Cache;

// TODO - Переделать в behavior
trait EntityTrait {
	public static function findOne($id, $cacheComponentId = 'cache')
	{
		/** @var self ActiveRecord */
		if ( is_array($id) ) {
			$fieldName = isset($id['id']) ? 'id' : array_keys($id)[0];
			if ( ! $fieldName ) {
				$fieldName = 'id';
			}
			$id = $id[$fieldName];
		} elseif ( is_object($id) ) {
			$id = $id->id;
			$fieldName = 'id';
		} else {
			$fieldName = 'id';
		}

		if ( empty($id) ) {
			return null;
		}

		$cacheKey = self::className() . ":{$fieldName}[{$id}]";

		// Попытка взять данные из кеша
		/** @var $cacheComponent Cache */
		if ( $cacheComponentId && ( $cacheComponent = Yii::$app->get($cacheComponentId) ) && false !== $cache = $cacheComponent->get($cacheKey) ) {
			// Найдено в кеше
			if ( $fieldName != 'id' ) {
				// Если поле не id, то $cache - это id записи, а не сама запись
				if ( false !== $cache = $cacheComponent->get(self::className() . ":id[{$cache}]") ) {
					return $cache;
				}
				// тут конфликтная ситуация - есть закешированный id, но нет кеша сущности
				$cacheComponent->delete($cacheKey);
			}

			return $cache;
		}

		// Данных в кеше нет, получим объект из базы и создадим кеш
		if ( ( $entity = call_user_func([self::$activerecordCass, 'findOne'], [$fieldName => $id]) ) ) {
			$entity = method_exists($entity, 'getModelEntity') ? $entity->getModelEntity() : $entity;

			if ( $cacheComponentId && isset($cacheComponent) ) {
				if ( $fieldName != 'id' ) {
					$cacheComponent->set($cacheKey, $entity->id);
				}
				$cacheComponent->set(self::className() . ":id[{$entity->id}]", $entity);
			}
		}

		return $entity;
	}

	/**
	 * @param $slug
	 *
	 * @return array|null|Content
	 */
	public static function findBySlug($slug) {
		return self::findOne(['slug' => $slug]);
	}
}