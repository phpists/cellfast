<?php
namespace common\widgets;

class Breadcrumbs extends \yii\widgets\Breadcrumbs
{
    public function init()
    {
        parent::init();
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        if (empty($this->links)) {
            return;
        }

        return $this->render('breadcrumbs', [
        	'links' => $this->links,
	        'homeLink' => $this->homeLink,
	        'encodeLabels' => $this->encodeLabels,
	        'activeItemTemplate' => $this->activeItemTemplate,
	        'itemTemplate' => $this->itemTemplate,
	        'tag' => $this->tag,
	        'options' => $this->options,
        ]);
    }
}