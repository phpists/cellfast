<?php

namespace noIT\core\behaviors;

use yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use dosamigos\transliterator\TransliteratorHelper;

/** TODO - Добавить проверку уникальности с доп.условием, например, соместно с project_id. Сейчас же, уникальность харак-к сквозная, это плохо! */
class SlugBehavior extends Behavior
{
    public $in_attribute = 'name';
    public $out_attribute = 'slug';
    public $translit = true;
    public $replaced = true;
    public $unique = true;
    public $saveasas = true;

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT=> 'getSlug',
            ActiveRecord::EVENT_BEFORE_UPDATE=> 'getSlug'
        ];
    }

    public function getSlug( $event )
    {
        if(!empty($this->owner->{$this->in_attribute})){
            if ( empty( $this->owner->{$this->out_attribute} ) ) {
                $this->owner->{$this->out_attribute} = $this->generateSlug( $this->owner->{$this->in_attribute} );
            } elseif (!$this->saveasas) {
                $this->owner->{$this->out_attribute} = $this->generateSlug( $this->owner->{$this->out_attribute} );
            }
        }

    }

    private function generateSlug( $slug )
    {
        if ($this->replaced) {
            $slug = $this->slugify($slug);
        }
        if (!$this->unique) {
            return $slug;
        } elseif ( $this->checkUniqueSlug( $slug ) ) {
            return $slug;
        } else {
            for ( $suffix = 2; !$this->checkUniqueSlug( $new_slug = $slug . '-' . $suffix ); $suffix++ ) {}
            return $new_slug;
        }
    }

    private function slugify( $slug )
    {
        if ( $this->translit ) {
            return  yii\helpers\Inflector::slug( TransliteratorHelper::process( $slug ), '-', true );
        } else {
            return $this->slug( $slug, '-', true );
        }
    }

    private function slug( $string, $replacement = '-', $lowercase = true )
    {
        $string = preg_replace( '/[^\p{L}\p{Nd}]+/u', $replacement, $string );
        $string = trim( $string, $replacement );
        return $lowercase ? strtolower( $string ) : $string;
    }

    private function checkUniqueSlug( $slug )
    {
        $pk = $this->owner->primaryKey();
        $pk = $pk[0];

        $condition = $this->out_attribute . ' = :out_attribute';
        $params = [ ':out_attribute' => $slug ];
        if ( !$this->owner->isNewRecord ) {
            $condition .= ' and ' . $pk . ' != :pk';
            $params[':pk'] = $this->owner->{$pk};
        }

        return !$this->owner->find()
            ->where( $condition, $params )
            ->one();
    }

}