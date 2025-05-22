<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Room;
use App\Policies\RoomPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    protected $policies = [
        Room::class => RoomPolicy::class,
    ];

    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
