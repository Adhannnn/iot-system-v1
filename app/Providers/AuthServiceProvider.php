<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Room;
use App\Policies\RoomPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Room::class => RoomPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
