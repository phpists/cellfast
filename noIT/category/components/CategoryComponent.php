<?php
namespace noIT\category\components;

use common\helpers\AdminHelper;
use noIT\category\models\Category;
use Yii;
use yii\base\Component;

class CategoryComponent extends Component {
    public $visible_only = true;

    public $categoryModel = 'noIT\category\models\Category';

    protected $categoryTable;
    protected $sortColumn;
    protected $statusColumn;

    protected $categoriesTree = [];
    /** @var $categories Category[] */
    protected $categories = [];
    protected $categories_tree_map = [];
    protected $categoriesIds = [];
    protected $categories_url_map = [];
    protected $categories_root_map = [];

    public function init()
    {
        parent::init();

        $this->categoryTable = call_user_func($this->categoryModel ."::tableName");

        if (!$this->sortColumn) {
            $this->sortColumn = AdminHelper::FIELDNAME_SORT;
        }

        if (!$this->statusColumn) {
            $this->statusColumn = AdminHelper::FIELDNAME_STATUS;
        }
    }

    public function getCategories() {
        if (empty($this->categories)) {
            /** @var \common\models\Category[] $result */
//            $result = Yii::$app->db->cache(function($db) {
                $query = call_user_func($this->categoryModel ."::find");
                // @todo Check cache
                if ($this->project) {
                    $query->andFilterWhere(['project_id' => $this->project]);
                }
                if ($this->visible_only) {
                    $query->andFilterWhere(['status' => Category::STATUS_ACTIVE]);
                }
	            if ($this->sortColumn) {
                    $query->orderBy([$this->sortColumn => SORT_ASC]);
                }
                $query->orderBy(['depth' => SORT_ASC]);
                $query->select("{$this->categoryTable}.*");

                $query->groupBy("{$this->categoryTable}.". AdminHelper::FIELDNAME_PK);

	            $result = $query->all();
//            }, null, new \yii\caching\DbDependency(['sql' => "SELECT MAX(updated_at) FROM {$this->categoryTable} WHERE {$this->statusColumn} = ". Category::STATUS_ACTIVE]));

            foreach ($result as $item) {
                $this->categoriesIds[$item->id] = $item->id;
                $this->categories[$item->id] = $item;
                $this->categories_url_map[strtolower($item->slug)] = $item->id;
                if ($item->parent_id == 0) {
                    $this->categories_root_map[] = $item->id;
                }
            }
        }
        return $this->categories;
    }

    public function getRootCategories() {
        $result = [];
        $this->getCategories();
        foreach ($this->categories_root_map as $id) {
            $result[] = $this->getCategory($id);
        }
        return $result;
    }

    public function getCategory($id) {
        return isset($this->categories[$id]) ? $this->categories[$id] : null;
    }

    public function getBySlug($slug) {
        $this->getCategories();
        $slug = strtolower($slug);
        return isset($this->categories_url_map[$slug]) ? $this->getCategory($this->categories_url_map[$slug]) : null;
    }

	/**
	 * Return tree of categories
	 *
	 * @param null $rootCategoryId
	 *
	 * @return array
	 */
    public function getCategoriesTree($rootCategoryId = null) {
        if (empty($this->categoriesTree)) {
            $this->getCategories();
            foreach ($this->categories as $i => &$element) {
                if ($element->depth == 0) {
                    $this->categoriesTree[$element->id] = $element;
                    $this->categories_tree_map[$element->id] = &$this->categoriesTree[$element->id];
                    continue;
                }
                $parents = explode(",", $element->path);
                $subcategories = &$this->categoriesTree;
	            // Check for unvisible parents
                $add = true;
                for ($i = 0; $i < $element->depth; $i++) {
                    if (!isset($subcategories[$parents[$i]])) {
                        $add = false;
                        break;
                    }
                    $subcategories = &$subcategories[$parents[$i]]->subcategories;
                }
                // And add child
                if ($add) {
                    $subcategories[$element->id] = $element;
                    $this->categories_tree_map[$element->id] = &$subcategories[$element->id];
                }
            }
        }

        if (!empty($rootCategoryId)) {
            return empty($this->categories_tree_map[$rootCategoryId]) ? [] : $this->categories_tree_map[$rootCategoryId]->subcategories;
        }

        return $this->categoriesTree;
    }

    public function getCategoriesListTree($id = 'id', $name = 'name') {
        $result = [];
        $this->_recuvercyListTree($result, $this->getCategoriesTree(), $id, $name);
        return $result;
    }

    public function getCategoriesFloatListTree($id = 'id', $name = 'name', $symbol = '-') {
        $result = [];
        $this->_recuvercyFloatListTree($result, $this->getCategoriesTree(), $id, $name, $symbol);
        return $result;
    }

    protected function _recuvercyListTree(&$result, $tree, $id = 'id', $name = 'name') {
        /** @var Category $item */
        foreach($tree as $item) {
            if (empty($item->subcategories)) {
                $result[$item->{$id}] = $item->{$name};
            } else {
                $result[$item->{$name}] = [];
                $this->_recuvercyListTree($result[$item->{$name}], $item->subcategories, $id, $name);
            }
        }
    }

    protected function _recuvercyFloatListTree(&$result, $tree, $id = 'id', $name = 'name', $symbol = '&ndash;') {
        /** @var Category $item */
        foreach($tree as $item) {
            $result[$item->{$id}] = str_repeat($symbol, $item->depth+1) . $item->{$name};
            if (!empty($item->subcategories)) {
                $this->_recuvercyFloatListTree($result, $item->subcategories, $id, $name, $symbol);
            }
        }
    }
}