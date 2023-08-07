<?php

namespace bryza\widgets;

class LinkPager extends \common\widgets\LinkPager
{

	protected function renderPageButtons()
	{
		$pageCount = $this->pagination->getPageCount();

		if ($pageCount < 2 && $this->hideOnSinglePage) {
			return '';
		}

		return $this->render('link-pager/buttons', [
			'pagination' => $this->pagination,
			'currentPage' => $this->pagination->getPage(),
			'firstPageLabel' => $this->firstPageLabel === true ? '1' : $this->firstPageLabel,
			'prevPageLabel' => $this->prevPageLabel,
			'nextPageLabel' => $this->nextPageLabel,
			'lastPageLabel' => $this->lastPageLabel === true ? $pageCount : $this->lastPageLabel,
			'pageCount' => $pageCount,
			'pageRange' => $this->getPageRange(),
			'firstPageCssClass' => $this->firstPageCssClass,
			'prevPageCssClass' => $this->prevPageCssClass,
			'nextPageCssClass' => $this->nextPageCssClass,
			'lastPageCssClass' => $this->lastPageCssClass,
			'options' => $this->options,
			'pageCssClass' => $this->pageCssClass,
			'activePageCssClass' => $this->activePageCssClass,
			'disabledPageCssClass' => $this->disabledPageCssClass,
			'disabledListItemSubTagOptions' => $this->disabledListItemSubTagOptions,
			'disableCurrentPageButton' => $this->disableCurrentPageButton,
		]);
	}
}