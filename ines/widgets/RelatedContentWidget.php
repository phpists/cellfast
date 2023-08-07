<?php
namespace ines\widgets;
use Yii;

class RelatedContentWidget extends \common\widgets\RelatedContentWidget
{
	public $title;

	public $items;

	public $type;
	public $typeItem;

	public $view;
	public $viewItem;

	public function init()
	{

		if( $this->title == null ){
			$this->title = 'Другие материалы';
		}

		if( $this->type === null && $this->view === null ){
			$this->type = 'static';
		}

		if( $this->type !== null ){
			$this->view = "related-content/{$this->type}/index";
		}

		if( $this->typeItem === null && $this->viewItem === null ){
			$this->typeItem = 'content';
		}

		if( $this->typeItem !== null ){
			$this->viewItem = "items/{$this->typeItem}";
		}

	}

	public function run()
	{
		return $this->render($this->view, [
			'title' => $this->title,
			'items' => $this->items,
			'viewItem' => $this->viewItem,
		]);
	}
}
