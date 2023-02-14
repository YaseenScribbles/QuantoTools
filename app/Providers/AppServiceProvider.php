<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Config::set([
        //     'fYear' => DB::select('SELECT CASE WHEN DATE_PART(\'MONTH\',CURRENT_TIMESTAMP) <= 3
        //     THEN concat((DATE_PART(\'YEAR\',CURRENT_TIMESTAMP) - 1),\'-\', CAST(DATE_PART(\'YEAR\',CURRENT_TIMESTAMP) as integer) % 100)
        //     ELSE concat(DATE_PART(\'YEAR\',CURRENT_TIMESTAMP),\'-\',(CAST(DATE_PART(\'YEAR\',CURRENT_TIMESTAMP) as integer)  % 100)+1) END as FYear')
        // ]);
    }
}
