<?php

return array(
    // This path may be different. You can probably get it from `config/main.php`.
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'Cron',

    'preload'=>array('log'),
    
    'import'=>array(
		'application.components.*',
		'application.models.*',
    ),
    // We'll log cron messages to the separate files
    'components'=>array(
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
                    'logFile'=>'cron.log',
					'levels'=>'error, warning',
				),
				array(
					'class'=>'CFileLogRoute',
					'logFile'=>'cron_trace.log',
					'levels'=>'trace',
				),
			),
		),

        // Your DB connection
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=cot27290_SAM',
			'emulatePrepare' => true,
			'username' => 'cot27290_SAM',
			'password' => '?)ZkEI(O]nDl',
			'charset' => 'utf8',
		),
    ),
);