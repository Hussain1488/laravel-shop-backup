<?php

namespace Themes\DefaultTheme\src\Controllers;

use App\Models\Cart;
use App\Http\Controllers\Controller;
use App\Models\Product;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Log;


class CartController extends Controller
{
    public function show()
    {
        $city_id = auth()->check() && auth()->user()->address  ? auth()->user()->address->city_id : null;

        return view('front::cart', compact('city_id'));
    }


    public function store(Product $product, Request $request)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:1',
        ]);
    
        $price = $this->getPrice($product, $request);
        if (!$price) {
            return response(['status' => 'error', 'message' => 'قیمت محصول معتبر نیست']);
        }
    
        $stockData = $this->checkStockFromAPI($product);
        if (!$stockData) {
            return response(['status' => 'error', 'message' => 'درخواست به API با خطا مواجه شد']);
        }
    
        // Update the product price and stock in the database
        $this->updatePriceAndStock($product, $price, $stockData);
        if ($this->isProductInStock($stockData) && !$this->isStockNegative($stockData)) {
            $cart = $this->getUserCart();
            $this->updateCart($cart, $product, $price, $request->quantity);
    
            // Only add message to response if product is successfully added to cart
            $response = ['status' => 'success', 'cart' => view('front::partials.cart')->with('render_cart', $cart)->render()];
            $response['message'] = 'محصول مورد نظر با موفقیت به سبد خرید شما اضافه شد برای رزرو محصول سفارش خود را نهایی کنید.';
            return response($response);
        } else {
            if ($this->isStockNegative($stockData)) {
                return response(['status' => 'error', 'message' =>'در انبار مرکزی این محصول موجود نیست']);
            } else {
                return response(['status' => 'error', 'message' => 'در انبار مرکزی این محصول موجود نیست']);
            }
        }
    }
    private function isStockNegative(array $stockData)
{
    foreach ($stockData['holding1'] as $depot => $stock) {
        if ($stock < 0) {
            return true;
        }
    }
    return false;
}
    
    private function getPrice(Product $product, Request $request)
    {
        if ($product->isSinglePrice()) {
            return $product->getPrices()->first();
        } else {
            
            $request->validate([
                'price_id' => [
                    'required',
                    Rule::exists('prices', 'id')->where('product_id', $product->id)->where('deleted_at', null)
                ]
            ]);
            return $product->prices()->find($request->price_id);
        }
    }
    
    private function checkStockFromAPI(Product $product)
    {
        try {
            $client = new Client(['timeout' => 5.0]);
            $response = $client->post("https://h-dastani.clickmis.ir/api/Item/ReportItemDetails?userGuid=e1bde0cd-47fa-4407-8dc5-eb4a62eb8dc8", [
                'json' => [
                    'itemGuid' => $product->Guid,
                    'depotList' => ["905fdfcb-3daa-4631-8ceb-3a9aadd92984"]
                ]
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            Log::error('RequestException: ' . $e->getMessage());
            if ($e->hasResponse()) {
                Log::error('Response: ' . $e->getResponse()->getBody()->getContents());
            }
            return null;
        } catch (\Exception $e) {
            Log::error('Exception: ' . $e->getMessage());
            return null;
        }
    }
    
    private function updatePriceAndStock(Product $product, $price, array $stockData)
    {
        $depotGuid = "905fdfcb-3daa-4631-8ceb-3a9aadd92984";
        $newStock = 0;
    
        // Check if the product has stock in the specified depot
        if (isset($stockData['holding1'][$depotGuid])) {
            $newStock = $stockData['holding1'][$depotGuid];
        } else {
            // If holding1 is empty, set stock to 0
            Log::info("Product ID {$product->id} has no stock in depot. Setting stock to 0.");
        }
    
        DB::transaction(function () use ($product, $price, $newStock) {
            // Update the stock in the prices table
            $price->update(['stock' => $newStock]);
    
            // Optional: Update price if it's part of the response (assuming you have price in the response)
            // $newPrice = $stockData['price'] ?? null; // Replace with actual field
            // if ($newPrice) {
            //     $price->update(['price' => $newPrice]);
            // }
    
            // Log the stock update
            Log::info("Product ID {$product->id} - Price ID {$price->id} stock updated to {$newStock}");
        });
    }
    
    private function isProductInStock(array $stockData)
    {
        $depotGuid = "905fdfcb-3daa-4631-8ceb-3a9aadd92984";
        return isset($stockData['holding1'][$depotGuid]) && !empty($stockData['holding1'][$depotGuid]);
    }
    
    private function getUserCart()
    {
        if (auth()->check()) {
            return auth()->user()->getCart();
        } else {
            $cart_id = Cookie::get('cart_id');
            if (!$cart_id || !($cart = Cart::find($cart_id)) || $cart->user_id != null) {
                $cart = Cart::create(['user_id' => null]);
            }
            Cookie::queue(Cookie::make('cart_id', $cart->id));
            return $cart;
        }
    }
    
    private function updateCart($cart, $product, $price, $quantity)
    {
        $cart_product = $cart->products()->where('product_id', $product->id)->where('price_id', $price->id)->first();
    
        if (!$cart_product) {
            $stock_status = $price->hasStock($quantity);
            if (!$stock_status['status']) {
                return response(['status' => 'error', 'message' => $stock_status['message']]);
            }
    
            $cart->products()->attach([
                $product->id => [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price_id' => $price->id
                ],
            ]);
        } else {
            if ($product->isDownload()) {
                return response(['status' => 'error', 'message' => trans('front::messages.controller.this-file-is-in-cart')]);
            }
    
            $stock_status = $price->hasStock($quantity + $cart_product->pivot->quantity);
            if (!$stock_status['status']) {
                return response(['status' => 'error', 'message' => $stock_status['message']]);
            }
    
            $cart->products()->where('product_id', $product->id)->where('price_id', $price->id)->update([
                'quantity' => $cart_product->pivot->quantity + $quantity,
            ]);
        }
    }




    // public function store(Product $product, Request $request)
    // {
    //     $request->validate([
    //         'quantity' => 'required|numeric|min:1',
    //     ]);
    
    //     if ($product->isSinglePrice()) {
    //         $price = $product->getPrices()->first();
    //     } else {
    //         $request->validate([
    //             'price_id' => ['required', Rule::exists('prices', 'id')->where('product_id', $product->id)->where('deleted_at', null)]
    //         ]);
    
    //         $price = $product->prices()->find($request->price_id);
    //     }
    
    //     // Check stock from supplier's API
    //     try {
    //         $client = new Client();
    //         $response = $client->post("https://h-dastani.clickmis.ir/api/Item/ReportItemDetails?userGuid=e1bde0cd-47fa-4407-8dc5-eb4a62eb8dc8", [
    //             'json' => [
    //                 'itemGuid' => $product->Guid,
    //                 'depotList' => [
    //                     "905fdfcb-3daa-4631-8ceb-3a9aadd92984"
    //                 ]
    //             ]
    //         ]);
    
    //         $data = json_decode($response->getBody()->getContents(), true);
    
    //         // Debugging: Print response data
    //         error_log(print_r($data, true));
    
    //         // Check if the product is in stock
    //         $depotGuid = "905fdfcb-3daa-4631-8ceb-3a9aadd92984";
    //         if (
    //             (isset($data['holding1'][$depotGuid]) && $data['holding1'][$depotGuid] > 0)
    //         ) {
    //             // create or get user cart
    //             if (auth()->check()) {
    //                 $cart = auth()->user()->getCart();
    //             } else {
    //                 $cart_id = Cookie::get('cart_id');
    
    //                 if (!$cart_id || !($cart = Cart::find($cart_id)) || $cart->user_id != null) {
    //                     $cart = Cart::create([
    //                         'user_id' => null,
    //                     ]);
    //                 }
    
    //                 Cookie::queue(Cookie::make('cart_id', $cart->id));
    //             }
    
    //             $cart_product = $cart->products()->where('product_id', $product->id)->where('price_id', $price->id)->first();
    
    //             if (!$cart_product) {
    //                 $stock_status = $price->hasStock($request->quantity);
    
    //                 if (!$stock_status['status']) {
    //                     return response(['status' => 'error', 'message' => $stock_status['message']]);
    //                 }
    
    //                 $cart->products()->attach([
    //                     $product->id => [
    //                         'product_id' => $product->id,
    //                         'quantity' => $request->quantity,
    //                         'price_id' => $price->id
    //                     ],
    //                 ]);
    //             } else {
    //                 if ($product->isDownload()) {
    //                     return response([
    //                         'status' => 'error',
    //                         'message' => trans('front::messages.controller.this-file-is-in-cart')
    //                     ]);
    //                 }
    
    //                 $stock_status = $price->hasStock($request->quantity + $cart_product->pivot->quantity);
    
    //                 if (!$stock_status['status']) {
    //                     return response(['status' => 'error', 'message' => $stock_status['message']]);
    //                 }
    
    //                 $cart->products()->where('product_id', $product->id)->where('price_id', $price->id)->update([
    //                     'quantity' => $cart_product->pivot->quantity + $request->quantity,
    //                 ]);
    //             }
    
    //             return response(['status' => 'success', 'cart' => view('front::partials.cart')->with('render_cart', $cart)->render()]);
    //         } else {
    //             return response(['status' => 'error', 'message' => 'در انبار مرکزی این محصول موجود نیست']);
    //         }
    //     } catch (RequestException $e) {
    //         // Log the error
    //         error_log('RequestException: ' . $e->getMessage());
    //         if ($e->hasResponse()) {
    //             error_log('Response: ' . $e->getResponse()->getBody()->getContents());
    //         }
    //         return response(['status' => 'error', 'message' => 'There was some internal error']);
    //     } catch (\Exception $e) {
    //         // Log the error
    //         error_log('Exception: ' . $e->getMessage());
    //         return response(['status' => 'error', 'message' => 'There was some internal error']);
    //     }
    // }

    public function update(Request $request)
    {
        $cart = get_cart();

        $errors = [];

        foreach ($cart->products as $product) {

            $price     = $product->prices()->find($product->pivot->price_id);
            $quantity  = $request->input('product-' . $product->pivot->id);
            $has_stock = $price->hasStock($quantity, true);

            if (!$has_stock['status']) {
                $errors[] = $has_stock['message'];
            }
        }

        if (!empty($errors)) {
            return response(['errors' => $errors], 406);
        }

        foreach ($cart->products as $product) {
            $quantity = $request->input('product-' . $product->pivot->id);

            if ($quantity === '0') {
                DB::table('cart_product')->where('cart_id', $cart->id)->where('id', $product->pivot->id)->delete();
            } else if (intval($quantity) && $product->isPhysical()) {
                DB::table('cart_product')->where('cart_id', $cart->id)->where('id', $product->pivot->id)->update([
                    'quantity' => $quantity,
                ]);
            }
        }

        return response('success');
    }

    public function destroy($id)
    {
        $cart = get_cart();

        if ($cart) {
            DB::table('cart_product')->where('cart_id', $cart->id)->where('id', $id)->delete();
        }

        return redirect()->route('front.cart');
    }
}
