<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'timeZone' => 'UTC',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'formatter' => [
            'dateFormat' => 'php:j M',
            'datetimeFormat' => 'php:j M H:i',
            'timeFormat' => 'php:H:i',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => '€',
            'timeZone' => 'UTC',
       ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
        ],
        'i18n' => [
			'translations' => [
				'booking*' => [
					'class' => 'yii\i18n\DbMessageSource',
					'enableCaching' => true,
					'sourceMessageTable' => 'source_message',
					'messageTable' => 'message',
					'sourceLanguage' => 'en',
					'on missingTranslation' => ['common\components\i18n\MissingTranslationHandler', 'load']
				],
			],
		],
    ],
];
