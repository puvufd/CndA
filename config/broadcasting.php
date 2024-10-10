<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Вещатель по умолчанию
    |--------------------------------------------------------------------------
    |
    | Этот параметр определяет вещателя по умолчанию, который будет использоваться платформой
    | framework при трансляции события. Возможна установка значения
    | для любого из подключений, определенных в приведенном массиве "подключения".
    |
    | Поддержка: "pusher", "ably", "redis", "log", "null"
    |
    */

    'default' => env('BROADCAST_DRIVER', 'null'),

    /*
    |--------------------------------------------------------------------------
    | Широковещательные соединения
    |--------------------------------------------------------------------------
    |
    | Здесь возможно определение всех широковещательные соединения, которые будут использоваться
    | для трансляции событий в другие системы или через websockets. 
    | В этом массиве представлены примеры каждого доступного типа соединений.
    |
    */

    'connections' => [

        'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'app_id' => env('PUSHER_APP_ID'),
            'options' => [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'host' => env('PUSHER_HOST') ?: 'api-'.env('PUSHER_APP_CLUSTER', 'mt1').'.pusher.com',
                'port' => env('PUSHER_PORT', 443),
                'scheme' => env('PUSHER_SCHEME', 'https'),
                'encrypted' => true,
                'useTLS' => env('PUSHER_SCHEME', 'https') === 'https',
            ],
            'client_options' => [
                // Возможности клиента: https://docs.guzzlephp.org/en/stable/request-options.html
            ],
        ],

        'ably' => [
            'driver' => 'ably',
            'key' => env('ABLY_KEY'),
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],

    ],

];
