<?php

namespace App\Http\Middleware;

use App\Models\Product;
use Closure;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class CheckProductAvailability
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function handle(Request $request, Closure $next)
    {
        $cartItems = Auth::user()->cart->products;

        if ($cartItems->isEmpty()) {
            return redirect()->back()->withErrors('Your cart is empty.');
        }

        $unavailableProducts = [];

        foreach ($cartItems as $item) {
            try {
                $response = $this->client->post("https://h-dastani.clickmis.ir/api/Item/ReportItemDetails?userGuid=e1bde0cd-47fa-4407-8dc5-eb4a62eb8dc8", [
                    'json' => [
                        'itemGuid' => $item->Guid,
                        'depotList' => ["905fdfcb-3daa-4631-8ceb-3a9aadd92984"]
                    ]
                ]);

                $responseData = json_decode($response->getBody()->getContents(), true);

                if (isset($responseData['holding1'])) {
                    $totalAvailability = array_sum($responseData['holding1']);
                 
                    if ($totalAvailability <= 0) {
                        $unavailableProducts[] = $item;
                    }elseif ($totalAvailability < $item->pivot->quantity) {
                        $item->prices[0]->update(['stock' => $totalAvailability]);
                        $item->pivot->update(['quantity' => $totalAvailability]);
                  
                        return redirect()->back()->withErrors("موجودی {$item->title} به روز رسانی شد");
                    }
                } else {
                    Log::error('Invalid API response: ' . json_encode($responseData));
                    return redirect()->back()->withErrors("Error checking availability for product {$item->title}.");
                }
            } catch (\Exception $e) {
                Log::error('Exception: ' . $e->getMessage());
                continue; // Continue to the next item in case of an error
            }
        }

        if (!empty($unavailableProducts)) {
            DB::beginTransaction();
            try {
                foreach ($unavailableProducts as $unavailableProduct) {
                    DB::table('cart_product')
                        ->where('cart_id', Auth::user()->cart->id)
                        ->where('product_id', $unavailableProduct->id)
                        ->delete();
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Error removing unavailable products: ' . $e->getMessage());
                return redirect()->back()->withErrors("Error removing unavailable products from the cart.");
            }

            $errors = [];
            foreach ($unavailableProducts as $unavailableProduct) {
                $errors["product_errors.{$unavailableProduct['id']}"] = "موجودی محصول {$unavailableProduct['title']} پایان یافته است و از سبد خرید شما حذف شد";
            }

            return redirect()->back()->withErrors($errors);
        }

        return $next($request);
    }
}