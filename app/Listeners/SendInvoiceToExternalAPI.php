<?php

namespace App\Listeners;

use App\Events\InvoiceCreated;
use App\Services\apiClick;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendInvoiceToExternalAPI
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
     * @param  \App\Events\InvoiceCreated  $event
     * @return void
     */
    public function handle(InvoiceCreated $event)
    {
        $invoice = $event->order;

        $this->apiClick->createInvoice($invoice);

    }
}
