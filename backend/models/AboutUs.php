<?php

namespace backend\models;

class AboutUs extends \common\models\AboutUs
{
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_id' => 'Проект',
            'name_ru_ru' => 'Заголовок (Русский)',
            'name_uk_ua' => 'Заголовок (Украинский)',
            'slug' => 'URL',

            'cover' => 'Картинка #1',
            'cover_2' => 'Картинка #2',

            'teaser_ru_ru' => 'Вступление #1 (Русский)',
            'teaser_uk_ua' => 'Вступление #1 (Украинский)',

            'teaser_2_ru_ru' => 'Вступление #2 (Русский)',
            'teaser_2_uk_ua' => 'Вступление #2 (Украинский)',

            'teaser_3_ru_ru' => 'Вступление #3 (Русский)',
            'teaser_3_uk_ua' => 'Вступление #3 (Украинский)',

            'body_ru_ru' => 'Контент #1 (Русский)',
            'body_uk_ua' => 'Контент #1 (Украинский)',

            'video' => 'Видео - Youtube',

            'body_2_ru_ru' => 'Контент #2 (Русский)',
            'body_2_uk_ua' => 'Контент #2 (Украинский)',
        ];
    }
}