<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PerformanceServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Log slow queries in development
        if (config('app.debug')) {
            DB::listen(function ($query) {
                if ($query->time > 100) { // Log queries taking more than 100ms
                    Log::warning('Slow query detected:', [
                        'sql' => $query->sql,
                        'bindings' => $query->bindings,
                        'time' => $query->time
                    ]);
                }
            });
        }

        // Clear relevant caches when models are updated
        Event::listen(['eloquent.saved: *', 'eloquent.deleted: *'], function ($event, $models) {
            foreach ($models as $model) {
                $class = get_class($model);
                switch ($class) {
                    case 'App\Models\Member':
                    case 'App\Models\Payment':
                    case 'App\Models\Attendance':
                    case 'App\Models\ActiveSession':
                        Cache::forget('dashboard_data_' . now()->format('Y-m-d_H'));
                        break;
                }
            }
        });
    }
}
