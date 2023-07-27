<?php

namespace common\widgets;

use Yii;
use yii\base\Widget;

class AjaxPagerWidget extends Widget {
    public $pagination;
    public $url;
    public $wrapper = '#content';
    public $listView = 'ajax-list';
    public $someMoreCaption = 'Load {count} more items';

    public function init()
    {
        parent::init();
    }

    public function run() {
        if ($this->pagination->page >= $this->pagination->pageCount-1) {
            return '';
        }
        return $this->render('ajax-pager', [
            'pagination' => $this->pagination,
            'url' => $this->url,
            'wrapper' => $this->wrapper,
            'listView' => $this->listView,
	        'someMoreCaption' => $this->someMoreCaption,
        ]);
    }
}