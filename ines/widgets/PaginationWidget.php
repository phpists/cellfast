<?php

namespace ines\widgets;

use common\helpers\SiteHelper;
use Yii;
use yii\helpers\Url;

class PaginationWidget extends \common\widgets\LinkPager
{
	public $url;
	public $route;
	// TODO - WTF slug?
	public $slug = '';

	public $showTravel = true;
	public $showAll = false;
	public $showSeparator = true;
	public $showFirstElement = 1;
	public $showLastElement = 1;
	public $currentElementsNearby = 1;

	public $itemClassName = '';
	public $prevClassName = 'pagination__arr';
	public $nextClassName = 'pagination__arr';
	public $firstClassName = 'pagination__arr';
	public $lastClassName = 'pagination__arr';

	public $urlScheme = false;
	public $urlEncode = true;

	public $_items = [];
	public $_arrows = [];

	protected function renderPageButtons() {
		$pageCount = $this->pagination->getPageCount();
		if ( $pageCount < 2 && $this->hideOnSinglePage ) {
			return '';
		}

		$this->createButtons();
		$this->listTransformation();
		$this->addTravel();

		return $this->render( 'pagination/list', [
			'items' => $this->_items,
			'arrows' => $this->_arrows,
		] );
	}

	public function createButtons() {
		$currentPage = $this->pagination->getPage() + 1;


		//  internal pages
		for ( $page = 1; $page <= $this->pagination->getPageCount(); $page ++ ) {
			$class = $this->itemClassName;
			if ( $currentPage == $page ) {
				$class .= ' active';
			}
			if ( $currentPage == $page ) { /* $class .= ' disabled'; */
			}

			$this->_items[ $page ] = $this->createItem( [
				'page'     => $page,
				'label'    => $page,
				'class'    => $class,
				'href'     => $this->createUrl( $page ),
				'disabled' => $currentPage == $page,
				'active'   => $currentPage == $page,
			] );
		}
	}

	public function createItem( $params = [] ) {
		$default_params = [
			'page'     => '',
			'class'    => $this->itemClassName,
			'label'    => '',
			'view'    => 'item',
			'disabled' => false,
			'active'   => false,
			'href'     => false,
		];

		if ( isset( $params ) && is_array( $params ) ) {
			if ( isset( $params['href'] ) && empty( $params['href'] ) ) {
				$params['disabled'] = true;
			}

			return array_merge( $default_params, $params );
		}

		return $default_params;
	}

	public function getRoute($changes = []) {
		if ( null === $this->route ) {
			$this->route = Yii::$app->urlManager->getCurrentRoute();
		}
		return array_merge($changes, $this->route);
	}

	public function createUrl( $page ) {
		$url = Url::to($this->getRoute(['page' => $page]), $this->urlScheme );
		if (!$this->urlEncode) {
			$url = urldecode($url);
		}
		return $url;
	}

	public function listTransformation() {

		if ( $this->showAll === true ) {
			return;
		} else {
			$currentPage = $this->pagination->getPage() + 1;

			$return = [];

			if ( $this->showFirstElement > 0 ) {
				$count        = 0;
				$return_first = [];

				if( $currentPage == 1 ){
					$this->showFirstElement += $this->currentElementsNearby + 1;
				}

				foreach ( $this->_items as $key => $val ) {
					if ( $count >= $this->showFirstElement ) {
						break;
					}

					$return_first[ $key ] = $val;

					$count ++;
				}

				$return += $return_first;
			}

			if ( $this->currentElementsNearby !== false ) {
				$return_nearby = [];

				if ( $this->currentElementsNearby > 0 ) {
					for ( $i = $this->currentElementsNearby; $i > 0; $i -- ) {
						if ( isset( $this->_items[ $currentPage - $i ] ) ) {
							$return_nearby[ $currentPage - $i ] = $this->_items[ $currentPage - $i ];
						}
					}
				}

				$return_nearby[ $currentPage ] = $this->_items[ $currentPage ];

				if ( $this->currentElementsNearby > 0 ) {
					for ( $i = 1; $i <= $this->currentElementsNearby; $i ++ ) {
						if ( isset( $this->_items[ $currentPage + $i ] ) ) {
							$return_nearby[ $currentPage + $i ] = $this->_items[ $currentPage + $i ];
						}
					}
				}

				$return += $return_nearby;
			}


			if ( $this->showLastElement > 0 ) {
				$count       = 0;
				$return_last = [];

				if( $currentPage == count($this->_items) ){
					$this->showLastElement += $this->currentElementsNearby + 1;
				}

				foreach ( array_reverse( $this->_items, true ) as $key => $val ) {
					if ( $count >= $this->showLastElement ) {
						break;
					}

					$return_last[ $key ] = $val;

					$count ++;
				}

				$return += array_reverse( $return_last, true );
			}

			if ( $this->showSeparator == true ) {
				$add_separator = true;
				for ( $i = 1; $i < $this->pagination->getPageCount(); $i ++ ) {
					if ( isset( $return[ $i ] ) ) {
						$add_separator = true;
					} else {
						if ( $add_separator == true ) {
							$return        = $this->addSeparator( $return, $i );
							$add_separator = false;
						}
					}
				}
			}

			ksort( $return );

			$this->_items = $return;
		}
	}

	public function addSeparator( $list, $key ) {
		$list[ $key ] = $this->createItem( [
//			'label'    => '&hellip;',
			'label'    => '<span></span><span></span><span></span>',
			'class'    => 'pagination__nums__dots',
			'view'    => 'item-dot',
			'disabled' => false,
			'href'     => 'javascript:;',
		] );

		return $list;
	}

	public function addTravel() {
		if ( $this->showTravel == true ) {
			$currentPage = $this->pagination->getPage() + 1;

			// first page
			if ( false ) {
				$this->_arrows['first'] = $this->createItem( [
					'page'  => 0,
					'label' => '&laquo;',
					'class' => $this->firstClassName,
					'href'  => $this->createUrl( 1 ),
				] );
			}

			// prev page
			if ( ( $currentPage - 1 > 0 ) == true ) {
				$this->_arrows['prev'] = $this->createItem( [
					'page'  => $currentPage - 1,
					'label' => '&laquo;',
					'class' => $this->prevClassName,
					'href'  => $this->createUrl( $currentPage - 1 ),
				] );
			}


			// next page
			if ( ( $currentPage + 1 <= $this->pagination->getPageCount() ) == true ) {
				$this->_arrows['next'] = $this->createItem( [
					'page'     => $currentPage + 1,
					'class'    => $this->nextClassName,
					'disabled' => false,
					'label'    => '&raquo;',
					'href'     => $this->createUrl( $currentPage + 1 ),
				] );
			}
		}

	}
}
