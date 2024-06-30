<?php

namespace App\Listeners;

use App\Events\ProductCreated;
use App\Services\apiClick;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendProductCreatedNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    protected $apiClick;
   



    public function __construct(apiClick $apiClick)
    {
        $this->apiClick = $apiClick;
    }
    /**
     * Handle the event.
     *
     * @param  \App\Events\ProductCreated  $event
     * @return void
     */
    public function handle(ProductCreated $event)
    {

        $product = $event->product;

        $this->apiClick->createProduct($product);

    }
}
