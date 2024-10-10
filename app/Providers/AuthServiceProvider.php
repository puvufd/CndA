<?php

namespace App\Providers;

// используется Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Сопоставление модели с политикой сайта.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Регистрация любых служб аутентификации / авторизации.
     */
    public function boot(): void
    {
        //
    }
}
