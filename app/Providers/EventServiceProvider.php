<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Сопоставление события со слушателем для приложения.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Регистрирация любых мероприятий для сайта.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Определение, должны ли события и прослушиватели обнаруживаться автоматически.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
