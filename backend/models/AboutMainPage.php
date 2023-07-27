<?php

namespace backend\models;

class AboutMainPage extends \common\models\AboutMainPage
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
			'cover' => 'Картинка',
			'body_ru_ru' => 'Описание (Русский)',
			'body_uk_ua' => 'Описание (Украинский)',

			'info_image_1' => 'Информационный блок - картинка 1',
			'info_image_2' => 'Информационный блок - картинка 2',
			'info_teaser_1_ru_ru' => 'Информационный блок - текст 1 (справа) (Русский)',
			'info_teaser_1_uk_ua' => 'Информационный блок - текст 1 (справа) (Украинский)',
			'info_teaser_2_ru_ru' => 'Информационный блок - текст 2 (слева) (Русский)',
			'info_teaser_2_uk_ua' => 'Информационный блок - текст 2 (слева) (Украинский)',
		];
	}
}