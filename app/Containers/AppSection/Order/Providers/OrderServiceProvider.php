<?php

namespace App\Containers\AppSection\Order\Providers;

use App\Containers\AppSection\Order\Models\Order;
use App\Observers\OrderObserver;
use Illuminate\Support\ServiceProvider;

/**
 * A custom Service Provider - remember to register it in the MainServiceProvider of this Container.
 */
class OrderServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Order::observe(OrderObserver::class);
    }

    protected $observers = [
        Order::class => [OrderObserver::class],
    ];
}

