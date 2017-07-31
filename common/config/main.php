<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
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
					'sourceLanguage' => 'en-US',
					'on missingTranslation' => ['common\components\i18n\MissingTranslationHandler', 'load']
				],
			],
		],
    ],
];
