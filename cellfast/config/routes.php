<?php
return array_merge(
 	require(Yii::getAlias('@common/config/routes.php')),
	['<module:(webapi)>' => '<module>'],
	// Download indexes by type
	[
		"download/<type:(document|certificate|price-list|catalog)>" => 'site/download',
	]
);
