<?php

namespace App\Observers;

use App\Containers\AppSection\Cart\Models\Cart;
use Illuminate\Support\Facades\Log;

class CartObserver
{
    /**
     * Handle the Cart "created" event.
     */
    public function created(Cart $cart): void
    {
        Log::info('Product added to Cart. Cart ID: ' . $cart->id);
    }

    /**
     * Handle the Cart "updated" event.
     */
    public function updated(Cart $cart): void
    {
        Log::info('Number of products in the cart updated. Cart ID: ' . $cart->id);
    }

    /**
     * Handle the Cart "deleted" event.
     */
    public function deleted(Cart $cart): void
    {
        Log::info('Cart deleted. Cart ID: ' . $cart->id);
    }

    /**
     * Handle the Cart "restored" event.
     */
    public function restored(Cart $cart): void
    {
    }

    /**
     * Handle the Cart "force deleted" event.
     */
    public function forceDeleted(Cart $cart): void
    {
    }
}
