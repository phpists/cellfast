<?php
namespace backend\widgets\metronic_select2;

use kartik\widgets\AssetBundle;
use kartik\widgets\Select2;

class MetronicSelect2 extends Select2
{
	/**
	 * @var string the locale ID (e.g. 'fr', 'de') for the language to be used by the Select2 Widget. If this property
	 * not set, then the current application language will be used.
	 */
	public $language = 'ru';
	/**
	 * @var string the theme name to be used for styling the Select2.
	 */
	public $theme = self::THEME_DEFAULT;
	/**
	 * Registers the asset bundle and locale
	 */
	public function registerAssetBundle()
	{
		$view = $this->getView();
		$lang = isset($this->language) ? $this->language : '';
		MetronicSelect2Asset::register($view)->addLanguage($lang, '', 'js/i18n');
		if (in_array($this->theme, self::$_inbuiltThemes)) {
			/**
			 * @var AssetBundle $bundleClass
			 */
			$bundleClass = __NAMESPACE__ . '\MetronicThemeDefaultAsset';

			$bundleClass::register($view);
		}
	}
}