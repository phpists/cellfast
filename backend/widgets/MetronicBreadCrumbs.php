<?php
namespace backend\widgets;

use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

class MetronicBreadCrumbs extends Breadcrumbs
{

	public $title;

	public $tag = 'ul';

	public $tagOptions = ['class' => 'm-subheader__breadcrumbs m-nav m-nav--inline'];


	public $homeLink = [
		'url' => ['/'],
		'label' => '',
	];

	public function run()
	{

		if (empty($this->links)) {
			return;
		}

		$wrapp = '<div class="m-subheader ">';
		$wrapp .= '<div class="d-flex align-items-center">';
		$wrapp .= '<div class="mr-auto">';
		$wrapp .= '<h3 class="m-subheader__title m-subheader__title--separator">{title}</h3>';
		$wrapp .= '{menu}';
		$wrapp .= '</div>';
		$wrapp .= '</div>';
		$wrapp .= '</div>';

		$links = [];

		if (isset($this->homeLink)) {

			$homeLinkTemplate = '<li class="m-nav__item m-nav__item--home">';
			$homeLinkTemplate .= '<a href="{url}" class="m-nav__link m-nav__link--icon">';
			$homeLinkTemplate .= '<i class="m-nav__link-icon la la-home"></i>';
			$homeLinkTemplate .= '</a>';
			$homeLinkTemplate .= '</li>';

			$links[] = $this->renderItem($this->homeLink, $homeLinkTemplate);
		}

		foreach ($this->links as $link) {

			$linkTemplate = '<li class="m-nav__separator">-</li>';
			$linkTemplate .= '<li class="m-nav__item">';
			$linkTemplate .= '<a href="{url}" class="m-nav__link">';
			$linkTemplate .= '<span class="m-nav__link-text">{label}</span>';
			$linkTemplate .= '</a>';
			$linkTemplate .= '</li>';

			$links[] = $this->renderItem($link, $linkTemplate);

		}

		$menu = Html::tag($this->tag, implode('', $links), $this->tagOptions);

		$breadcrumbs = strtr($wrapp, [
			'{title}' => $this->title,
			'{menu}' => $menu,
		]);

		echo $breadcrumbs;

	}

	protected function renderItem($link, $template)
	{

		if(!empty($link['label'])) {
			$label = Html::encode($link['label']);
		} else {
			$label = '';
		}

		if(is_array($link)) {

			if(!isset($link['url'])) {
				$link['url'] = 'javascript:;';
			}

		} else {

			$link = [];

			$link['url'] = '';

			$label = $this->title;

		}

		return strtr($template, [
				'{url}' => Html::encode(Url::to($link['url'])),
				'{label}' => $label
			]
		);

	}

}
