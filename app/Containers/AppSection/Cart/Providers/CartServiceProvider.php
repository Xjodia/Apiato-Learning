<?php

namespace App\Containers\AppSection\Cart\Providers;

use App\Containers\AppSection\Cart\Models\Cart;
use App\Observers\CartObserver;
use Illuminate\Support\ServiceProvider;

/**
 * A custom Service Provider - remember to register it in the MainServiceProvider of this Container.
 */
class CartServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Cart::observe(CartObserver::class);
    }

    protected $observers = [
        Cart::class => [CartObserver::class],
    ];
}
