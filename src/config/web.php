<?php
return [
    'bootstrap'  => ['skeeks\modules\cms\logCms\Bootstrap'],
    'modules'    => [
        'logCms' => [
            'class' => 'skeeks\modules\cms\logCms\Module',
        ],
    ],
    'components' =>
        [
            'logCms' => [
                'class' => 'skeeks\modules\cms\logCms\components\LogComponent',
            ],
            'i18n'   => [
                'translations' => [
                    'skeeks/logCms' => [
                        'class'    => 'yii\i18n\PhpMessageSource',
                        'basePath' => '@skeeks/modules/cms/logCms/messages'
                    ],
                ],
            ],
        ],
];