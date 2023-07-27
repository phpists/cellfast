<?php
return [
	// TODO - move to settings-CRUD
	'defaultPriceType' => 1,

    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,

	// working emails

	'adminNameFrom' => 'Robot',
	'adminEmailFrom' => 'no-reply@noit.group',

	'documentType' => [
//		['value' => 'document', 'label' => 'Документ', 'labels' => 'Documents'],
		['value' => 'certificate', 'label' => 'Сертификат', 'labels' => 'Certificates'],
		['value' => 'catalog', 'label' => 'Каталог', 'labels' => 'Catalogs'],
		['value' => 'price-list', 'label' => 'Прайс-лист', 'labels' => 'Price-lists'],
	],

];
