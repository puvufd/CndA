<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Драйвер хэша по умолчанию
    |--------------------------------------------------------------------------
    |
    | Этот параметр определяет хэш-драйвер по умолчанию, который будет использоваться для хэширования паролей
    | для сайта. По умолчанию используется алгоритм bcrypt;
    | однако возмодно изменение этого параметра.
    |
    | Поддержка: "bcrypt", "argon", "argon2id"
    |
    */

    'driver' => 'bcrypt',

    /*
    |--------------------------------------------------------------------------
    | Параметры Bcrypt
    |--------------------------------------------------------------------------
    |
    | Здесь возможно указание параметров конфигурации, которые следует использовать при
    | хэшировании паролей с использованием алгоритма Bcrypt. Это позволит 
    | контролировать количество времени, необходимое для хэширования заданного пароля.
    |
    */

    'bcrypt' => [
        'rounds' => env('BCRYPT_ROUNDS', 12),
        'verify' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Варианты использования аргона
    |--------------------------------------------------------------------------
    |
    | Здесь возможно указание параметров конфигурации, которые следует использовать при
    | хэшировании паролей с использованием алгоритма Bcrypt. Это позволит 
    | контролировать количество времени, необходимое для хэширования заданного пароля.
    |
    */

    'argon' => [
        'memory' => 65536,
        'threads' => 1,
        'time' => 4,
        'verify' => true,
    ],

];
