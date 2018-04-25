<?php
return [
    'id' => 'support',
    'migrationPath' => '@vendor/yuncms/support/migrations',
    'translations' => [
        'yuncms/support' => [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@vendor/yuncms/support/messages',
        ],
    ],
    'frontend' => [
        'class' => 'yuncms\support\frontend\Module',
    ],
];