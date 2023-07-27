<?php
namespace common\controllers;

use common\helpers\OrderHelper;
use common\models\Cart;
use common\models\ProductEntity;
use common\models\ProductItemEntity;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class OrderController extends Controller {
	/**
	 * Возвращает текущую корзину
	 *
	 * @return string
	 */
	public function actionCart() {
		/** @var $cart Cart */
		$cart = Yii::$app->cart;

		if ( Yii::$app->request->isAjax ) {
			return $this->renderAjax('cart', [
				'cart' => $cart,
			]);
		}
		return $this->render('wrapper', [
			'cart' => $cart,
		]);
	}

	public function actionCartBadge() {
		/** @var $cart Cart */
		$cart = Yii::$app->cart;

		return $this->renderAjax('badge', [
			'cart' => $cart,
		]);
	}
	/**
	 * Added product item to cart
	 * Ожидает POST-параметры с ключами
	 * * id - ID-комбинации товара
	 * * quantity - количество в штуках
	 *
	 * @return mixed
	 */
	public function actionCartAdd() {
		/** @var $product ProductEntity */
		// Проверяем входящие данные
		if ( !($items = Yii::$app->request->post('items')) ) {
			throw new NotFoundHttpException("Bad request");
		}
		$status = true;
		/** @var $cart Cart */
		$cart = Yii::$app->cart;
		$errors = [];
		foreach ($items as $id => $quantity) {
			if ( null === $productItem = ProductItemEntity::findOne($id) ) {
				continue;
			}
			$cart->add($productItem, Yii::$app->request->get('quantity', $quantity));
		}
		if ( Yii::$app->request->isAjax ) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return [
				'status' => $status,
				'errors' => $errors,
				'quantity' => $cart->quantity,
				'quantity_label' => Yii::t('app', '{n, plural, =0{# товаров} =1{# товар} other{# товаров}}', ['n' => $cart->quantity]),
				'summ' => $cart->summ,
				'summ_label' => Yii::t('app', '{n, plural, =0{# грн} =1{# грн} other{# грн}}', ['n' => $cart->summ]),
				// 'action' => '' заготовка на будущее, можно передавать дополнительные действия, например - показать сообщение или издать звук
			];
		} else {
			$this->redirect(['order/cart']);
		}
	}

	public function actionCartRemove() {
		/** @var $product ProductEntity */
		// Проверяем входящие данные
		if ( !($items = Yii::$app->request->post('items', [])) ) {
			throw new NotFoundHttpException("Bad request");
		}
		/** @var $cart Cart */
		$cart = Yii::$app->cart;
		foreach ($items as $id) {
			if ( null === $productItem = ProductItemEntity::findOne($id) ) {
				continue;
			}
			$cart->delete($productItem);
		}
		return $this->actionCart();
	}

	public function actionCartUpdate() {
		/** @var $product ProductEntity */
		// Проверяем входящие данные
		if ( !($items = Yii::$app->request->post('items', [])) ) {
			throw new NotFoundHttpException("Bad request");
		}
		/** @var $cart Cart */
		$cart = Yii::$app->cart;
		foreach ($items as $id => $quantity) {
			if ( !$quantity || null === $productItem = ProductItemEntity::findOne($id) ) {
				continue;
			}
			$cart->update($productItem, $quantity);
		}
		return $this->actionCart();
	}

	public function actionCartUp() {
		/** @var $product ProductEntity */
		// Проверяем входящие данные
		if ( !($id = Yii::$app->request->post('id')) || null === $productItem = ProductItemEntity::findOne($id) ) {
			throw new NotFoundHttpException("Bad request");
		}
		/** @var $cart Cart */
		$cart = Yii::$app->cart;
		$cart->update($productItem, 1, true);
		return $this->actionCart();
	}

	public function actionCartDown() {
		/** @var $product ProductEntity */
		// Проверяем входящие данные
		if ( !($id = Yii::$app->request->post('id')) || null === $productItem = ProductItemEntity::findOne($id) ) {
			throw new NotFoundHttpException("Bad request");
		}
		/** @var $cart Cart */
		$cart = Yii::$app->cart;
		$cart->update($productItem, $productItem, false, true);
		return $this->actionCart();
	}
}