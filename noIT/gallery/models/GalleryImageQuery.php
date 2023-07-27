<?php

namespace noIT\gallery\models;

use noIT\core\helpers\AdminHelper;

/**
 * This is the ActiveQuery class for [[GalleryImage]].
 *
 * @see GalleryImage
 */
class GalleryImageQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return GalleryImage[]|array
     */
    public function all($db = null)
    {
    	$this->orderBy([AdminHelper::FIELDNAME_SORT => SORT_ASC]);
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return GalleryImage|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
