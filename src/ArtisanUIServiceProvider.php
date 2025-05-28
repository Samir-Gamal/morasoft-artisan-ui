<?php

namespace Morasoft\ArtisanUI;

use Illuminate\Support\ServiceProvider;

class ArtisanUIServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // تحميل الـ views من الباكيج
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'artisan-ui');

        // تحميل الراوتات
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        // نشر الـ views
        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('views/artisan-ui'),
        ], 'artisan-ui');
    }

    public function register()
    {
        $helperFile = __DIR__ . '/Helpers/helper.php';

        if (file_exists($helperFile))
            require_once $helperFile;
    }
}
