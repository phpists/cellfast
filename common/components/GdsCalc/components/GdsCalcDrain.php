<?php

namespace common\components\GdsCalc\components;

use backend\models\ProductFeatureValue;
use common\components\GdsCalc\GdsCalc;
use common\helpers\ProductHelper;
use common\models\Brand;
use common\models\Category;
use common\models\Feature;
use common\models\Option;
use common\models\Product;
use common\models\ProductItem;
use common\models\ProductItemEntity;
use common\models\ProductItemSearch;
use common\models\ProductOptions;
use common\models\ProductSearch;
use yii\helpers\ArrayHelper;

class GdsCalcDrain extends GdsCalc
{
    public $name = 'Drain';

    public $alias = 'drain';

    /** @var Category */
    public $category;

    protected $_result;

    protected $_totalSumms;

    protected $colors;

    protected $systems;

    protected $gutter_length = 3;

    protected $pipe_length = 3;

    /**
     * @var ID-свойства Тип элемента
     */
    public $feature_type;

    public $component_labels = [];

    public $calc_data;

    /**
     * Указание типов товаров для калькулятора
     * gutter - желоб 3м
     * pipe - труба 3м
     * gutter_connector - Соединитель желоба
     * gutter_corner_connector_inner - Соединитель желоба угловой внутренний (90°)
     * gutter_corner_connector_outer - Соединитель желоба угловой внешний (90°)
     * gutter_dummy_left - Заглушка желоба
     * gutter_dummy_right - Заглушка желоба
     * pipe_connector - Соединитель трубы (колено 60°)
     * gutter_gully - Проходной дождеприемник
     * gutter_bracket - Кронштейн желоба
     * pipe_bracket - Кронштейн трубы
     * @var array
     */
    public $feature_type_options = [];

    /**
     * @var ID-свойства Тип системы
     */
    public $feature_system;

    /**
     * @var ID-свойства Цвет
     */
    public $feature_color;

    /**
     * @var array Значения цветов
     */
    public $feature_color_options = [];

    /**
     * @var array ID характеристики Длина (value -> int)
     */
    public $feature_length;

    /**
     * @var array Привязка размера утеплителя к крюкам хомута по артикулам
     */
    public $pipe_bracket_hook;

    /**
     * @var array Рассчетные площади систем для расчета количества воронок
     */
    public $feature_system_calc_areas = [];

    /**
     * @return ProductFeatureValue[]
     */
    public function getColors()
    {
        if ($this->colors === null) {
            $this->colors = [];
            foreach (ProductFeatureValue::find()->where(['feature_id' => $this->feature_color]) as $color) {
                $this->colors[$color->id] = $color;
            }
        }
        return $this->colors;
    }

    /**
     * @return ProductFeatureValue[]
     */
    public function getColor($option_id)
    {
        return isset($this->feature_color_options[$option_id]) ? $this->feature_color_options[$option_id] : null;
    }

    /**
     * @return ProductFeatureValue[]
     */
    public function getSystems()
    {
        if ($this->systems === null) {
            $this->systems = [];
            foreach (ProductFeatureValue::find()->where(['feature_id' => $this->feature_system])->all() as $feature) {
                $this->systems[$feature->id] = $feature;
            }
        }
        return $this->systems;
    }

    /**
     * Вычисляет набор компонентов для каждой системы,
     * @param array $params
     * @return array
     */
    public function getCalcResult($params = []) {
        // Если метод getCalcResult или атрибут calcResult уже вызывался ранее
        if ($this->_result !== null) {
            return $this->_result;
        }

        $this->_result = [];

        foreach ($this->getSystems() as $system) {
            $this->_result[$system->value] = [
                'system' => $system,
                'products' => $this->getProductKits($system, $params),
                'colors' => !empty($this->colors[$system->id]) ? $this->colors[$system->id] : [],
                'summs' => $this->getTableSumm($system->id),
            ];
        }

        return $this->_result;
    }

    /**
     * Расчитывает количество материала и возвращает его количество
     * @param $systemValue string
     * @return array
     */
    public function getCalcData($systemValue)
    {
        if (!isset($this->calc_data[$systemValue])) {

            $model = $this->getModel();

            /** Расчитываем количество товаров по типам
             * gutter - желоб 3м
             * pipe - труба 3м
             * gutter_connector - Соединитель (муфта) желоба
             * gutter_corner_connector_outer - Соединитель желоба угловой (90°) внешний
             * gutter_corner_connector_inner - Соединитель желоба угловой (90°) внутренний
             * gutter_dummy_left - Заглушка желоба
             * gutter_dummy_right - Заглушка желоба
             * pipe_connector - Соединитель трубы (колено 60°)
             * gutter_gully - Воронка / ливнеприемник с желоба
             * gutter_bracket - Кронштейн желоба
             * pipe_bracket - Кронштейн трубы
             */

            $this->calc_data[$systemValue] = [
                // кол-во желобов
                'gutter' => $model->calc('DrainGutterCount'),

                // кол-во муфт желоба
                'gutter_connector' => $model->calc('DrainGutterConnectorsCount'),

                // кол-во воронок (ливнеприемников)
                'gutter_gully' => $model->calc('DrainGutterGullyCount', $systemValue),

                'gutter_corner_connector_inner' => $model->calc('DrainGutterCornerConnectors', 'inner'),  // угловой соеденитель желоба внешний
                'gutter_corner_connector_outer' => $model->calc('DrainGutterCornerConnectors', 'outer'),  // угловой соеденитель желоба внутренний

                'gutter_dummy_left' => $model->calc('DrainGutterDummy', 'left'),  // левая заглушка желоба

                'gutter_bracket' => $model->calc('DrainGutterBracketsCount'), // крепление желоба

                // кол-во труб
                'pipe' => $model->calc('DrainPipeCount', $systemValue),

                'pipe_connector' => $model->calc('DrainPipeConnectorsCount', $systemValue),  // соеденитель трубы

                'pipe_knee' => $model->calc('DrainPipeKneeCount', $systemValue),  // колено трубы

                'pipe_bracket' => $model->calc('DrainPipeBracketsCount', $systemValue), // крепление трубы (хомут)
                'pipe_bracket_hook' => $model->calc('DrainPipeBracketsCount', $systemValue), // крепление трубы (крюк хомута)
            ];

            if (isset($this->feature_type_options['gutter_dummy_right'])) {
                $this->calc_data[$systemValue]['gutter_dummy_right'] = $model->calc('DrainGutterDummy', 'right');  // правая заглушка желоба
            } else {
                $this->calc_data[$systemValue]['gutter_dummy_left'] += $model->calc('DrainGutterDummy', 'right'); // правая заглушка желоба
            }
        }

        return $this->calc_data[$systemValue];
    }


    // todo To delete

   public function getComponentLabels($systemValue, $calc_data = null)
    {
        if ($calc_data === null) {
            $calc_data = $this->getCalcData($systemValue);
        }
        $labels = [];
        foreach ($calc_data as $key => $count) {
            if (!$count) {
                continue;
            }
            $labels[$key] = (isset($this->component_labels[$key]) ? $this->component_labels[$key] : $key) . ' x' . $count;
        }
        return $labels;
    }

    /**
     * @param $system
     * @return int
     */
    public function getTableSumm($systemId) {
        return isset($this->_totalSumms[$systemId]) ? $this->_totalSumms[$systemId] : 0;
    }

    public function getProductKits($system, $params = null, $calc_data = null) {
        if ($this->systems === null) {
            $this->systems = [];
        }

        // Если метод getProductKits или атрибут productKits уже вызывался ранее для системы $systemValue
        if (isset($this->product_kits[$system->id])) {
            return $this->product_kits[$system->id];
        }

        $this->product_kits[$system->id] = [];

        // Получаем кол-ва компонентов для данной системы $systemValue
        if ($calc_data === null) {
            $calc_data = $this->getCalcData($system->value);
        }

        $actual_calc_data = $type_option_ids = [];
        foreach ($calc_data as $type_key => $count) {
            if (!$count) {
                continue;
            }
            $actual_calc_data[$type_key] = $count;
            $type_option_ids[$type_key] = $this->feature_type_options[$type_key];
        }

        $colors = [];

        // Сквозные товары, не зависящие от цвета
        $baseProducts = [];

        // Проходимся по компонентам для данной системы $system и собираем $productItems
        foreach ($type_option_ids as $key => $feature_type_option) {
            if ($key === 'gutter_bracket') {
                if (count($feature_type_option) === 1 && isset($feature_type_option['0'])) {
                    $this->model->drain_gutter_install_type = '0';
                }

                // Если крепление желоба - его определяет параметр конфига gutter_bracket и drain_gutter_install_type
                elseif (!isset($feature_type_option[$this->model->drain_gutter_install_type])) {
                    continue;
                }

                $feature_type_option = $feature_type_option[$this->model->drain_gutter_install_type];
            }

            // Находим товар для данного компонента данной системы
            $searchModel = new ProductSearch([
                'type_id' => $this->type_id,
                'features' => [
                    $this->feature_type => [$feature_type_option],
                    $this->feature_system => [$system->id],
                ],
            ]);

            /** @var Product $product */
            $product = Product::find()->where(['id' => $searchModel->search($params)->query->column()])->one();

            $productData = [
                'image' => null,
                'component' => \Yii::t('app', isset($this->component_labels[$key]) ? $this->component_labels[$key] : $key),
                'count' => $actual_calc_data[$key],
                'price' => null,
                'sku' => null,
                'key' => $key,
            ];

            $productItems = [];
            if (!empty($product)) {
                // Если крепление трубы - его определяет параметр конфига pipe_bracket_hook и drain_gutter_install_type
                if ($key === 'pipe_bracket_hook') {
                    if (!isset($this->pipe_bracket_hook[$this->model->drain_insulation])) {
                        continue;
                    }
                    $productItem = $product->findBySku($this->pipe_bracket_hook[$this->model->drain_insulation]);

                    $productData['image'] = $productItem->getThumbUploadUrl('image', 'thumb_list') ?: $product->getThumbUploadUrl('image', 'thumb_list');
                    $productData['name'] = $productItem->name;
                    $productData['price'] = $productItem->price;
                    $productData['sku'] = $productItem->sku;
                    $productData['summ'] = $productItem->price * $productData['count'];

                    $baseProducts[$key] = $productData;

                    // Проходимся по всем цветам комбинации (должна быть одна, но у безцветных может быть несколько,напрмиер, у Крюка хомута)
                    // и собираем массив комбинаций товаров с ключем по цветам + массив цветов для данной системы
//                    foreach ($productItem->featureGroupedValueIds[$this->feature_color] as $colorFeatureId) {
//                        $productItems[$colorFeatureId][$key] = $productData;
//
//                        if (!isset($colors[$colorFeatureId])) {
//                            $colors[$colorFeatureId] = $colorFeatureId;
//                        }
//                    }

                } else {
                    foreach ($product->items as $productItem) {
                        /** @var ProductItemEntity $productItem */
                        if (!$productItem->status || !isset($productItem->featureGroupedValueIds[$this->feature_color])) {
                            continue;
                        }

                        $productData['image'] = $productItem->getThumbUploadUrl('image', 'thumb_list') ?: $product->getThumbUploadUrl('image', 'thumb_list');
                        $productData['name'] = $productItem->name;
                        $productData['price'] = $productItem->price;
                        $productData['sku'] = $productItem->sku;
                        $productData['summ'] = $productItem->price * $productData['count'];

                        // Проходимся по всем цветам комбинации (должна быть одна, но у безцветных может быть несколько,напрмиер, у Крюка хомута)
                        // и собираем массив комбинаций товаров с ключем по цветам + массив цветов для данной системы
                        foreach ($productItem->featureGroupedValueIds[$this->feature_color] as $colorFeatureId) {
                            $productItems[$colorFeatureId][$key] = $productData;

                            if (!isset($colors[$colorFeatureId])) {
                                $colors[$colorFeatureId] = $colorFeatureId;
                            }
                        }
                    }
                }
            }

            foreach (array_keys($productItems) as $colorFeatureId) {
                $this->product_kits[$system->id][$colorFeatureId][$key] = isset($productItems[$colorFeatureId][$key]) ? $productItems[$colorFeatureId][$key] : $productData;
            }
        }

        foreach ($baseProducts as $key => $productData) {
            foreach (array_keys($colors) as $colorFeatureId) {
                $this->product_kits[$system->id][$colorFeatureId][$key] = $productData;
            }
        }

        foreach ($this->product_kits[$system->id] as $colorFeatureId => $keys) {
            if (count($keys) < count($actual_calc_data)) {
                unset($this->product_kits[$system->id][$colorFeatureId]);
                if (isset($colors[$colorFeatureId])) {
                    unset($colors[$colorFeatureId]);
                }
                continue;
            }

            if (!isset($this->_totalSumms[$system->id][$colorFeatureId])) {
                $this->_totalSumms[$system->id][$colorFeatureId]  = 0;
            }

            foreach ($keys as $product) {
                $this->_totalSumms[$system->id][$colorFeatureId] += $product['summ'];
            }
        }

        $this->colors[$system->id] = empty($colors) ? [] : ProductFeatureValue::find()->where(['feature_id' => $this->feature_color, 'id' => $colors])->all();

        return $this->product_kits[$system->id];

    }
}
