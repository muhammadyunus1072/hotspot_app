<?php

namespace App\Providers;

use Illuminate\View\View;
use Illuminate\Support\Facades;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

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

        Blade::directive('currency', function ($expression) {
            return "Rp <?php echo App\Helpers\NumberFormatter::format($expression); ?>";
        });

        $this->loadMigrationsFrom([
            database_path('migrations'), // Default
            database_path('migrations/user'),
            database_path('migrations/other'),
            database_path('migrations/product'),
            database_path('migrations/transaction'),
        ]);
    }
}
