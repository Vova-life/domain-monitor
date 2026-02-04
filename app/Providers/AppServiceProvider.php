<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // Додай цей імпорт

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Додай цей блок: якщо ми на сервері (production), змушуємо використовувати HTTPS
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
