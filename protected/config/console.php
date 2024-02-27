<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Console Application',
	// application components
	'components'=>array(
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=cot27290_SAM',
			'emulatePrepare' => true,
			'username' => 'cot27290_SAM',
			'password' => '?)ZkEI(O]nDl',
			'charset' => 'utf8',
		),
	),
);