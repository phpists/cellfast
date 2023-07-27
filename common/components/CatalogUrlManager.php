<?php

namespace common\components;

//use common\models\Brand;
use common\models\Feature;
//use common\models\NpAreas;
//use common\models\NpCities;
use common\models\Option;
//use common\models\Taxonomy;
use Yii;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\UrlRuleInterface;
use common\helpers\ProductHelper;

class CatalogUrlManager implements UrlRuleInterface
{
    public $route_map = [
        'catalog' => 'catalog/category',
//        'taxonomy' => 'catalog/taxonomy',
        'products' => 'catalog/product',
//        'productinfo' => 'catalog/productinfo',
        'brands' => 'catalog/brands',
        'region' => 'region',
    ];

    protected function setRouteMap()
    {
        if ($catalogUrlAliases = @Yii::$app->params['catalogUrlAliases']) {
            foreach($catalogUrlAliases as $key => $val) {
                $this->route_map[$val] = 'catalog/category';
            }
        }
    }

    public function parseRequest($manager, $request)
    {
        if (isset($request->queryParams['page']) && $request->queryParams['page'] == 'all') {
            $get = $request->queryParams;
            unset($get['page']);
            Yii::$app->getResponse()->redirect(array_merge([$request->getPathInfo()], $get), 301)->send();
            Yii::$app->end();
        }

        $this->setRouteMap();

        $pathInfo = $request->getPathInfo();
        $paths = explode('/', trim($pathInfo));
        if (!array_key_exists($paths[0], $this->route_map)) {
            return false;
        }

        $params = [
            'brands' => [],
            'options' => [],
            'region' => null,
        ];

        $catalog_start_index = 0;

        $session = Yii::$app->session;
        if ($paths[0] == 'region') {
            if (empty($paths[1]) || ($region = ProductHelper::getNpCity($paths[1], 'url')) === null ) {
                throw new NotFoundHttpException("Page not found");
                return;
            }

            $session->set('user_region', $region->id);

            $params['region'] = $region;
            $catalog_start_index = 2;
        }

        $catalogUrlAliases = @Yii::$app->params['catalogUrlAliases'];

        if ( $paths[$catalog_start_index] == 'catalog' || $paths[$catalog_start_index] == 'taxonomy' || ($catalogUrlAliases && ($category_url = array_search($paths[$catalog_start_index], $catalogUrlAliases))) ) {

            if (empty($params['region'])) {
                $session->set('user_region', null);
            }

            switch ($paths[$catalog_start_index]) {
                case 'taxonomy':
                    $route = 'catalog/taxonomy';
                    break;
                default:
                    $route = 'catalog/category';
                    break;
            }

            // Category
            if (!empty($category_url)) {
                $category = ProductHelper::getCategoryByUrl($category_url);
                if (empty($category)) {
                    throw new HttpException(404, Yii::t('app', 'Page not found'));
                }
                $catalog_start_index--;
                $params['category'] = $category;
            } elseif (!empty($paths[$catalog_start_index+1])) {
                if ($paths[$catalog_start_index] == 'catalog') {
                    $category = ProductHelper::getCategoryByUrl($paths[$catalog_start_index+1]);
                    if (empty($category)) {
                        throw new HttpException(404, Yii::t('app', 'Page not found'));
                    } elseif ($category->id == 129 && !empty($params['region']) && $params['region']->area_id != 10) {
                        Yii::$app->getResponse()->redirect(ProductHelper::getCategoryUrlArray($category, $params, ['region']), 301)->send();
                    }
                } else {
                    $page = Taxonomy::find()->where(['url' => $paths[$catalog_start_index+1]])->one();
                    if (empty($page)) {
                        throw new HttpException(404, Yii::t('app', 'Page not found'));
                    }
                    $category = $page->category;
                    $category->landing_view = 'products';
                    $params['taxonomy'] = $page;
                }

                $params['category'] = $category;
            }
            if (!empty($paths[$catalog_start_index+2])) {
                if ($paths[$catalog_start_index+2] == 'filter' && !empty($paths[$catalog_start_index+3])) {
                    // Filter
                    $this->parseFilter($paths[$catalog_start_index + 3], $params);
                } elseif ($paths[$catalog_start_index+2] == 'manual' && !empty($paths[$catalog_start_index+3])) {
                    // Manual bu index
                    $params['manual'] = $paths[$catalog_start_index+3];
                } else {
                    // Brand
                    if (!empty($paths[$catalog_start_index+3]) || !($brand = ProductHelper::getBrandyByUrl($paths[$catalog_start_index+2]))) {
                        // Попытка распознать старые фильтры
                        $_options = [];
                        for($i=$catalog_start_index+2;$i<count($paths);$i++) {
                            $_pair = explode('=', $paths[$i]);

                            if (count($_pair) == 2 ) {
                                $f_alias = $_pair[0];
                                $f_values = $_pair[1];
                                if ($feature = Feature::find()->where(['url' => $f_alias])->one()) {
                                    $f_values = explode(',', $f_values);
                                    foreach($f_values as &$f_value) {
                                        $f_value = str_replace(array('~', '&s;'), array(',', '/'), $f_value);
                                    }
                                    $_options = Option::find()->where(['feature_id' => $feature->id, 'value' => $f_values])->orderBy(['position' => SORT_ASC])->all();
                                    foreach($_options as $_option) {
                                        $params['options'][$_option->id] = $_option;
                                    }
                                }
                            }
                        }
                        if (empty($params['options'])) {
                            throw new NotFoundHttpException("Page not found");
                            return;
                        } else {
                            Yii::$app->getResponse()->redirect(['catalog/category', 'category' => $params['category'], 'options' => $params['options']])->send();
                            Yii::$app->end();
                        }
                    } else {
                        $params['brands'][$brand->id] = $brand;
                    }
                }
            }
        } elseif ($paths[0] == 'products') {
            $product = ProductHelper::getProductByUrl($paths[1]);
            if (empty($product->id) || !$product->visible) {
                throw new HttpException(404, Yii::t('app', 'Page not found'));
            }
            $route = 'catalog/product';
            $params = [
                'product' => $product,
            ];
        } elseif ($paths[0] == 'productinfo') {
            $product = ProductHelper::getProductByUrl($paths[1]);
            $section = $paths[2];
            if (empty($product->id) || !$product->enabled || !$section || !in_array($section, ['summary', 'body', 'video', 'properties', 'equipment', 'comments'])) {
                throw new HttpException(404, Yii::t('app', 'Page not found'));
            }
            $route = 'catalog/productinfo';
            $params = [
                'product' => $product,
                'section' => $section
            ];
        } elseif ($paths[0] == 'brands') {
            if (empty($paths[1]) || $paths[1] == 'index') {
                $route = 'catalog/brands';
            } elseif (($brand = ProductHelper::getBrandyByUrl($paths[1]))) {
                $route = 'catalog/brand';
                $params['brand']= $brand;
                if (!empty($paths[2])) {
                    // Filter
                    if ($paths[2] == 'filter' && !empty($paths[3])) {
                        $this->parseFilter($paths[2], $params);
                    }
                }
            } else {
                // @todo redirect or return FALSE
            }
        }

        if (!empty($params['brands']) && count($params['brands']) == 1) {
            $params['brand'] = array_values($params['brands'])[0];
        }

        return [$route, $params];
    }
    /**
     * Creates a URL according to the given route and parameters.
     * @param \yii\web\UrlManager $manager the URL manager
     * @param string $route the route. It should not have slashes at the beginning or the end.
     * @param array $params the parameters
     * @return string|boolean the created URL, or false if this rule cannot be used for creating this URL.
     */
    public function createUrl($manager, $route, $params)
    {
        $this->setRouteMap();

        if (!in_array($route, $this->route_map)) {
            return false;
        }

        switch($route) {
            /*case 'catalog/taxonomy':
                $taxonomy_url = is_object($params['taxonomy']) ? $params['taxonomy']->url : strtolower($params['taxonomy']);

                $url = 'taxonomy/'. $taxonomy_url .'/';

                $this->setFilterUrl($params, $url);

                if (!empty($params) && ($query = http_build_query($params)) !== '') {
                    $url .= '?' . $query;
                }

                return $url;
                break;*/

            case 'catalog/category':
                $url = ['catalog'];
                if (!empty($params['category'])) {
                    $category_url = is_object($params['category']) ? $params['category']->slug : strtolower($params['category']);
                    if ( ($catalogUrlAliases = @Yii::$app->params['catalogUrlAliases']) && array_key_exists($category_url, $catalogUrlAliases) ) {
                        $url[] = $catalogUrlAliases[$category_url];
                    } else {
                        $url[] = $category_url;
                    }
                }  elseif (!empty($params['slug'])) {
                    $url[] = strtolower($params['slug']);
                    unset($params['slug']);
                }

                $url = implode('/', $url);

                if (!empty($params['region'])) {
                    $url = 'region/'. $params['region']->url .'/'. $url;
                    unset($params['region']);
                }

                if (!empty($params['manual'])) {
                    $url = 'manual/'. $params['manual'];
                    unset($params['manual']);
                }
                /*if (!empty($params['word'])) {
                    if (!is_array($params['word'])) {
                        $params['word'] = [$params['word']];
                    }
                    $url .= 'word:'. implode(';', $params['word']);
                }
                if (isset($params['word'])) {
                    unset($params['word']);
                }*/

                $this->setFilterUrl($params, $url);
                if (!empty($params) && ($query = http_build_query($params)) !== '') {
                    $url .= '?' . $query;
                }

                return $url;
                break;

            case 'catalog/product':
                if (!empty($params['product'])) {
                    $product_url = is_object($params['product']) ? $params['product']->slug : strtolower($params['product']);
                    unset($params['product']);
                } elseif (!empty($params['slug'])) {
                    $product_url = strtolower($params['slug']);
                    unset($params['slug']);
                }
                $url = 'products/'. $product_url;

                if (!empty($params) && ($query = http_build_query($params)) !== '') {
                    $url .= '?' . $query;
                }

                return $url;
                break;

            case 'catalog/productinfo':
                if (!empty($params['product'])) {
                    $product_url = is_object($params['product']) ? $params['product']->slug : strtolower($params['product']);
                    unset($params['product']);
                } elseif (!empty($params['slug'])) {
                    $product_url = strtolower($params['slug']);
                    unset($params['slug']);
                }
                if (!empty($params['section'])) {
                    $section = strtolower($params['section']);
                    unset($params['section']);
                } else {
                    $section = 'summary';
                }
                $url = "productinfo/$product_url/$section";

                if (!empty($params) && ($query = http_build_query($params)) !== '') {
                    $url .= '?' . $query;
                }

                return $url;
                break;

           /* case 'catalog/brands':
                if (empty($params['brand'])) {
                    return 'brands';
                } else {
                    $brand_url = is_object($params['brand']) ? $params['brand']->url : strtolower($params['brand']);
                }
                $url = 'brands/'. $brand_url .'/';

                $this->setFilterUrl($params, $url);

                if (!empty($params) && ($query = http_build_query($params)) !== '') {
                    $url .= '?' . $query;
                }

                return $url;
                break;*/
        }
    }

    private function option_value_encode($value) {
        return str_replace(array(',', '/'), array('~', '&s;'), $value);
    }

    private function setFilterUrl(&$params, &$url) {
        $filter = [];
        if (!empty($params['options'])) {
            /** @var $option Option */
            foreach ($params['options'] as $group_id => $options) {
                if (empty($options)) {
                    continue;
                }
                if (!is_array($options)) {
                    $options = [$options];
                }
                /*// Check type of a data
                $option = array_shift($options);
                if (!is_object($option)) {
                    if ($option == intval($option)) {
                        $field = 'id';
                    } else {
                        $field = 'url';
                    }
                    $options = Option::find()->where([$field => $options])->all();
                } else {
                    $options = $options;
                }
                foreach($options as $option) {
                    $filter[$option->feature_id][] = $option->id;
                }*/

                foreach($options as $option) {
                    if (!is_object($option)) {
                        if (is_int($option)) {
                            $option = ProductHelper::getOption($option);
                        } else {
                            $option = ProductHelper::getOptionByUrl($option);
                        }
                    }
                    if (empty($option->id)) {
                        continue;
                    }

                    if ( $option->feature_id && ($feature = ProductHelper::getFeature($option->feature_id)) ) {
                        $filter[$feature->url][$option->id] = $option->url;
                    }
                }
            }

            ksort($filter);
            foreach($filter as &$__options) {
                ksort($__options);
            }

            unset($params['options']);
        }

        /** @var $params['brand'] Brand */
        if (!empty($params['brands'])) {
            /** @var $brand Brand */

            if (count($params['brands']) == 1 && empty($filter)) {
                $brand = array_shift($params['brands']);
                if (!is_object($brand)) {
                    if ($brand == intval($brand)) {
                        $brand = Option::findOne($brand);
                    } else {
                        $brand = Option::find()->where(['url' => $option])->one();
                    }
                }
                $url .= '/'. $brand->url;
            } else {
                foreach ($params['brands'] as $brand) {
                    if (is_null($brand)) {
                        continue;
                    }
                    if (!is_object($brand)) {
                        if ($brand == intval($brand)) {
                            $brand = Option::findOne($brand);
                        } else {
                            $brand = Option::find()->where(['url' => $option])->one();
                        }
                    }
                    if (empty($brand->id)) {
                        continue;
                    }
                    $filter['brands'][$brand->id] = $brand->id;
                }
                ksort($filter['brands']);
            }
        }

        $query = [];
        foreach ($filter as $key => $value) {
            $query[] = $key .'='. (is_array($value) ? implode(',', $value) : $value);
        }

        $url .= $query ? '/filter/'. implode(';', $query) : '';
    }

    private function parseFilter($path, &$params) {
        $options = [];
        $filter_options = explode(';', $path);
        foreach ($filter_options as $filter_option) {
            if (empty($filter_option)) {
                continue;
            }
            list($filter_key, $filter_option) = explode('=', $filter_option, 2);
            if ($filter_key == 'prices') { // price-interval section
                $prices = explode(':', $filter_option);
                $params['prices'] = [
                    'min' => floatval($prices[0]),
                    'max' => floatval($prices[1]),
                ];
            } elseif ($filter_key == 'brands') { // options section
                foreach (Brand::find()->where(['id' => explode(',', $filter_option)])->all() as $brand) {
                    $params['brands'][$brand->id] = $brand;
                }
            } else { // brands and other sections
                foreach(explode(',', $filter_option) as $value)
                $options[] = $value;
            }
        }
        foreach (Option::find()->where(['url' => $options])->orderBy(['position' => SORT_ASC])->all() as $option) {
            $params['options'][$option->id] = $option;
        }
    }
}
