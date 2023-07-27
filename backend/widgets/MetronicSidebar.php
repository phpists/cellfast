<?php
namespace backend\widgets;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Menu;

class MetronicSidebar extends Menu
{

	const SINGLE_TEMPLATE_TYPE = 'single';

	const SUBMENU_TEMPLATE_TYPE = 'submenu';

	const SUB_SUBMENU_TEMPLATE_TYPE = 'sub-submenu';

	const CHILD_TEMPLATE_TYPE = 'child';

	public $linkTemplate = '<a href="{url}" class="{class_name}">{inner_html}</a>';

	public function run()
	{

		if ($this->route === null && Yii::$app->controller !== null) {
			$this->route = Yii::$app->controller->getRoute();
		}

		if ($this->params === null) {
			$this->params = Yii::$app->request->getQueryParams();
		}

		$items = $this->normalizeItems($this->items, $hasActiveChild);

		if (!empty($items)) {

			$tag = 'ul';

			$tagOptions = ['class' => 'm-menu__nav  m-menu__nav--dropdown-submenu-arrow '];

			echo Html::tag($tag, $this->renderItems($items), $tagOptions);

		}

	}

	protected function renderItems($items)
	{

		$lines = [];

		foreach ($items as $i => $item) {

			$tag = 'li';

			$parentOpened = false;

			if(!empty($item['items'])) {

				$list = $item['items'];

				foreach ($list as $single) {

					if($single['active']) {
						$parentOpened = true;
					}

				}

			}

			if(empty($item['items'])) {

				$tagOptions = [
					'class' => 'm-menu__item ',
					'aria-haspopup' => 'true'
				];

			} else {

				$tagOptions = [
					'aria-haspopup' => 'true',
					'm-menu-submenu-toggle' => 'hover',
				];

				if($parentOpened) {
					$tagOptions['class'] = 'm-menu__item m-menu__item--submenu m-menu__item--open';
				} else {
					$tagOptions['class'] = 'm-menu__item m-menu__item--submenu ';
				}

			}

			$class = [];

			if ($item['active']) {
				$class[] = 'm-menu__item--active';
			}

			Html::addCssClass($tagOptions, $class);

			$menu = $this->renderItem($item); // generate link with params

			if (!empty($item['items'])) {

				$submenuTemplate = '<div class="m-menu__submenu ">';
				$submenuTemplate .= '<span class="m-menu__arrow"></span>';
				$submenuTemplate .= '<ul class="m-menu__subnav">{items}</ul>';
				$submenuTemplate .= '</div>';

				$menu .= strtr($submenuTemplate, [
					'{items}' => $this->renderItems($item['items']),
				]);

			}

			$lines[] = Html::tag($tag, $menu, $tagOptions); // generate li with params

		}

		return implode("\n", $lines);
	}

	protected function renderItem($item)
	{

		$inner_html = '';

		if(!empty($item['items'])) {

			// submenu parent link template
			if(isset($item['template']) && $item['template'] === self::SUBMENU_TEMPLATE_TYPE) {
				$inner_html = $this->getInnerPartOfLinkTemplate($item, self::SUBMENU_TEMPLATE_TYPE);
			}

			// all sub-submenu parent link template
			if(!isset($item['template'])) {
				$inner_html = $this->getInnerPartOfLinkTemplate($item, self::SUB_SUBMENU_TEMPLATE_TYPE);
			}

			return strtr($this->linkTemplate, [
				'{url}' => Html::encode(Url::to($item['url'])),
				'{class_name}' => 'm-menu__link m-menu__toggle',
				'{inner_html}' => $inner_html,
			]);

		} else {

			// only single link template
			if(isset($item['template']) && $item['template'] === self::SINGLE_TEMPLATE_TYPE) {
				$inner_html = $this->getInnerPartOfLinkTemplate($item, self::SINGLE_TEMPLATE_TYPE);
			}

			//  all child link template
			if(!isset($item['template'])) {
				$inner_html = $this->getInnerPartOfLinkTemplate($item, self::CHILD_TEMPLATE_TYPE);
			}

			return strtr($this->linkTemplate, [
				'{url}' => Html::encode(Url::to($item['url'])),
				'{class_name}' => 'm-menu__link ',
				'{inner_html}' => $inner_html,
			]);

		}

	}

	protected function getInnerPartOfLinkTemplate($item, $template) {

		if($template === self::SUBMENU_TEMPLATE_TYPE) {

			$inner_html = '<i class="m-menu__link-icon {icon}"></i>';
			$inner_html .= '<span class="m-menu__link-text">{label}</span>';
			$inner_html .= '<i class="m-menu__ver-arrow la la-angle-right"></i>';

			$inner_html = strtr($inner_html, [
				'{icon}' => $item['icon'],
				'{label}' => $item['label']
			]);

			return $inner_html;

		} elseif ($template === self::SUB_SUBMENU_TEMPLATE_TYPE) {

			$inner_html = '<i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>';
			$inner_html .= '<span class="m-menu__link-text">{label}</span>';
			$inner_html .= '<i class="m-menu__ver-arrow la la-angle-right"></i>';

			$inner_html = strtr($inner_html, [
				'{label}' => $item['label']
			]);

			return $inner_html;

		} elseif ($template === self::SINGLE_TEMPLATE_TYPE) {

			$inner_html = '<i class="m-menu__link-icon {icon}"></i>';
			$inner_html .= '<span class="m-menu__link-title"><span class="m-menu__link-wrap">';
			$inner_html .= '<span class="m-menu__link-text">{label}</span>';
			$inner_html .= '</span></span>';

			$inner_html = strtr($inner_html, [
				'{icon}' => $item['icon'],
				'{label}' => $item['label'],
			]);

			return $inner_html;

		}  elseif ($template === self::CHILD_TEMPLATE_TYPE) {

			$inner_html = '<i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>';
			$inner_html .= '<span class="m-menu__link-text">{label}</span>';

			$inner_html = strtr($inner_html, [
				'{label}' => $item['label']
			]);

			return $inner_html;

		} else {

			return "Error in method getInnerPartOfLinkTemplate()";

		}

	}

}