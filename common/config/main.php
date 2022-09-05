<?php
return [
    'language' => 'en_US',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'timeZone' => 'UTC',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'formatter' => [
            'dateFormat' => 'php:j M',
            'datetimeFormat' => 'php:D j M H:i',
            'timeFormat' => 'php:H:i',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'EUR',
            'timeZone' => 'UTC',
            'locale' => 'fr-BE'
       ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
        ],
        'i18n' => [
			'translations' => [
				'booking*' => [
					'class' => 'yii\i18n\DbMessageSource',
					'enableCaching' => false,
					'sourceMessageTable' => 'source_message',
					'messageTable' => 'message',
					'sourceLanguage' => 'en',
					'on missingTranslation' => ['common\components\i18n\MissingTranslationHandler', 'load']
				],
			],
		],
    ],
];
