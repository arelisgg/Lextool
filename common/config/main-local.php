<?php
return [
    'language' => 'es',
    'modules' => [
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
        ],
    ],
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'pgsql:host=localhost;port=5432;dbname=lextool',
            'username' => 'postgres',
            'password' => 'POSTGRES',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => '10.8.176.4',
                'username' => 'cbrito',
                'password' => 'carlosjbrito94',
                'port' => '25',
                /*'host' => 'smtp.gmail.com',
                'username' => 'lextool.org',
                'password' => 'lextool2019',
                'port' => '587',
                'encryption' => 'tls',*/
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
    ],
];


