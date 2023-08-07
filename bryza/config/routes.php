<?php
return array_merge(
    [
        'catalog/gds-calc' => 'catalog/gds-calc',
    ],
	require(Yii::getAlias('@common/config/routes.php')),

	// Download indexes by type
	[
		"download/<type:(document|certificate|price-list|catalog)>" => 'site/download',
	]
);
