<?php

namespace App\Http\Controllers\Back;

use App\Events\ProductCreated;
use App\Events\ProductPricesChanged;
use App\Exports\ProductsExport;
use App\Models\AttributeGroup;
use App\Models\Brand;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\Back\Product\StoreProductRequest;
use App\Http\Requests\Back\Product\UpdateProductRequest;
use App\Http\Resources\Datatable\Product\ProductCollection;
use App\Models\Currency;
use App\Models\Label;
use App\Models\Price;
use App\Models\Product;
use App\Models\SizeType;
use App\Models\Specification;
use App\Models\SpecificationGroup;
use App\Models\SpecType;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Services\SlugService;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;
use Morilog\Jalali\Jalalian;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Product::class, 'product');
    }
 public function updateParentId(Request $request){
 
    $request->validate([
        'id' => 'required|integer',
        // 'parent_id' => 'required|integer',
    ]);
  
    $product = Product::find($request->id);



    if ($product) {
        // به‌روزرسانی parent_id
        $product->parent_id = $request->parent_id??null;
        $product->save();

        return response()->json(['message' => 'Parent ID updated successfully.']);
    } else {
        return response()->json(['message' => 'Product not found.'], 404);
    }
 }

public function stock_sync(Request $request){



    try {
   


            $client = new Client();
            $response = $client->post("https://h-dastani.clickmis.ir/api/Item/ReportInventory?userGuid=e1bde0cd-47fa-4407-8dc5-eb4a62eb8dc8", [
                'json' => [
                    'depotList' => ['905fdfcb-3daa-4631-8ceb-3a9aadd92984']
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            DB::table('prices')->delete();
        // بررسی و به‌روزرسانی موجودی محصولات
        foreach ($data['holdings'] as $holding) {
            $product = Product::where('Guid', $holding['ItemGuid'])->first();
           
            // if ($product->prices()->exists()) {
            //     $product->prices()->update([
            //         'stock' => $holding['holding1'],
            //         'price' => $holding['LastBuyPrice'],
            //         'regular_price' => 0,
            //         'discount_price' => 0
            //     ]);
            // } else {
           
                $newPrice = new Price([
                    'product_id' => $product->id, // شناسه محصول
                    'stock' => $holding['holding1']??0,
                    'price' => $holding['LastBuyPrice']??0,
                    'regular_price' => 0,
                    'discount_price' => 0
                ]);
                $newPrice->save();
            }
        
       



        \Log::info('Product inventory synced successfully');
  



        } catch (\Exception $e) {
            \Log::error('Failed to sync product inventory: ' . $e->getMessage());
            throw $e;
        }

return redirect()->back();
}
    public function products_sync(){
        $client = new Client();

        $getListCount = $client->get('https://h-dastani.clickmis.ir/api/Item/ListCount?userGuid=e1bde0cd-47fa-4407-8dc5-eb4a62eb8dc8&step={step}');
        $dataListCount = json_decode($getListCount->getBody()->getContents(), true);
        $ListCount=$dataListCount['Data'][0]['Pages'];

        $existingGuids = Product::pluck('guid')->toArray();
        $receivedGuids = [];

        for ($pageNumber = 1; $pageNumber <= $ListCount; $pageNumber++) {
            // Get products data for each page
            $response = $client->get("https://h-dastani.clickmis.ir/api/Item/List?userGuid=e1bde0cd-47fa-4407-8dc5-eb4a62eb8dc8&pageNumber=$pageNumber&step={10}&search=");
            $data = json_decode($response->getBody()->getContents(), true);
   
             
            foreach ($data['Data'] as $productData) {
                $category = Category::where('guid', $productData['ParentGuid'])->first();
                 $category_id = $category ? $category->id : null;
    
                 $existingProduct = Product::where('Guid', $productData['Guid'])->first();

                 // بررسی اگر محصول از قبل منتشر شده بود، تغییری در published اعمال نشود
                 $publishedValue = $existingProduct && $existingProduct->published ? $existingProduct->published : $productData['Status'];
                Product::updateOrCreate(
                [
                    'Guid' => $productData['Guid']
                ],
                [
                  
                    'title' => $productData['Title'],
                    'lang' => app()->getLocale(),
                    'type' => 'physical', // Set appropriate type
                    'category_id' => $category_id,
                    'slug' => $productData['Title'],
                    'weight' => $productData['Weight'],
                    'unit' => $productData['Unit1Description'], // Set appropriate unit
                    'rounding_type' => 'default', // Set appropriate rounding type
                    'rounding_amount' => 'default', // Set appropriate rounding amount
                    'price_type' => 'multiple-price', // Set appropriate price_type
                    'published' => $publishedValue,
                    'ItemCode' => $productData['Code'], // Fill this with appropriate code
                    
                ]
            );
    
            $receivedGuids[] = $productData['Guid'];
            }
        }

     // GUIDهایی که دیگر دریافت نشده‌اند را حذف می‌کنیم
     $existingGuids = Product::pluck('guid')->toArray();
     $guidsToDelete = array_diff($existingGuids, $receivedGuids);
     Product::whereIn('guid', $guidsToDelete)->delete();
     return redirect()->back();
    }



public function categories_sync(){
    $client = new Client();
    $response = $client->get('https://h-dastani.clickmis.ir/api/Item/ItemParentList?userGuid=e1bde0cd-47fa-4407-8dc5-eb4a62eb8dc8'); // جایگزین API_URL با آدرس واقعی
    $data = json_decode($response->getBody()->getContents(), true);

   
    $existingGuids = Category::pluck('guid')->toArray(); // دریافت تمام guidهای موجود در پایگاه داده
    $receivedGuids = []; // آرایه‌ای برای ذخیره guidهای دریافتی از API
    foreach ($data['Data'] as $categoryData) {
        $parentCategory = null;
        if ($categoryData['ParentGuid'] != '00000000-0000-0000-0000-000000000000') {
            $parentCategory = Category::where('guid', $categoryData['ParentGuid'])->first();
        }

        Category::updateOrCreate(
            ['guid' => $categoryData['Guid']],
            [
                'type' => 'productcat',
                'code' => $categoryData['Code'],
                'title' => $categoryData['Title'],
                'lang' => app()->getLocale(),
                'slug' => $categoryData['Title'],
                'category_id' => $parentCategory ? $parentCategory->id : null, // تنظیم category_id بر اساس ParentGuid
                'alias' => $categoryData['Alias'],
                'parent_guid' => $categoryData['ParentGuid'] != '00000000-0000-0000-0000-000000000000' ? $categoryData['ParentGuid'] : null
            ]
        );

        $receivedGuids[] = $categoryData['Guid']; // اضافه کردن guid به آرایه guidهای دریافتی
    }

    // حذف دسته‌بندی‌هایی که در داده‌های API وجود ندارند
    $guidsToDelete = array_diff($existingGuids, $receivedGuids);
    Category::whereIn('guid', $guidsToDelete)->delete();

    return redirect()->back();
}
    public function index()
    {
 
        return view('back.products.index');
    }

    public function apiIndex(Request $request)
    {
      
        $this->authorize('products.index');

        $products = Product::detectLang()->datatableFilter($request);

        $products = datatable($request, $products);

        return new ProductCollection($products);
    }

    public function indexPrices(Request $request)
    {
        $this->authorize('products.prices');

        $products = Product::detectLang()->filter($request)->customPaginate($request);

        return view('back.products.prices', compact('products'));
    }

    public function updatePrices(Request $request)
    {
        $this->authorize('products.prices');

        $request->validate([
            'products'        => 'required|array',
        ]);

        $products_id    = array_keys($request->products);
        $prices_count   = Price::whereIn('product_id', $products_id)->count() * 2;
        $max_input_vars = ini_get('max_input_vars');

        if ($prices_count + 5 > $max_input_vars) {
            throw ValidationException::withMessages([
                'prices' => 'لطفا مقدار max_input_vars را در فایل php.ini تغییر دهید.'
            ]);
        }

        foreach ($request->products as $key => $value) {
            $product = Product::find($key);

            if (!$product) {
                continue;
            }

            foreach ($product->prices as $price) {
                if (!isset($value['prices'][$price->id])) {
                    continue;
                }

                $request_price = $value['prices'][$price->id];

                if (isset($request_price['price']) && isset($request_price['stock']) && ($request_price['price'] != $price->price || $request_price['stock'] != $price->stock)) {

                    $price->update([
                        'price'          => $request_price['price'],
                        'stock'          => $request_price['stock'],
                        'discount_price' => get_discount_price($request_price['price'], $price->discount, $product),
                        'regular_price'  => get_discount_price($request_price['price'], 0, $product),
                    ]);
                }
            }

            event(new ProductPricesChanged($product));
        }

        // clear product caches
        Product::clearCache();

        return response('success');
    }

    public function store(StoreProductRequest $request)
    {
  
        $data = $request->only([
            'title',
            'title_en',
            'category_id',
            'size_type_id',
            'weight',
            'unit',
            'type',
            'description',
            'short_description',
            'special',
            'meta_title',
            'image_alt',
            'meta_description',
            'published',
            'currency_id',
            'rounding_amount',
            'rounding_type',
        ]);

        $data['spec_type_id']     = spec_type($request);
        $data['price_type']       = "multiple-price";
        $data['slug']             = $request->slug ?: $request->title;
        $data['publish_date']     = $request->publish_date ? carbon::parse($request->publish_date)->format('Y/m/d H:i:s') : null;
        $data['special_end_date'] = $request->special_end_date ? carbon::parse($request->special_end_date)->format('Y/m/d H:i:s') : null;
        $data['lang']             = app()->getLocale();

        $product = Product::create($data);

        // update product brand
        $this->updateProductBrand($product, $request);

        // update product prices
        $this->updateProductPrices($product, $request);

        // update product files
        $this->updateProductFiles($product, $request);

        // update product specifications
        $this->updateProductSpecifications($product, $request);

        // update product images
        $this->updateProductImages($product, $request);

        // update product categories
        $this->updateProductCategories($product, $request);

        // update product labels
        $this->updateProductLabels($product, $request);

        // update product sizes
        $this->updateProductSizes($product, $request);

        toastr()->success('محصول با موفقیت ایجاد شد.');
if ($request->sizebandi!=='on') {
    event(new ProductCreated($product));
}
        

        return response("success");
    }

    public function create(Request $request)
    {
        $categories      = Category::detectLang()->where('type', 'productcat')->orderBy('ordering')->get();
        $specTypes       = SpecType::detectLang()->get();
        $sizetypes       = SizeType::detectLang()->get();
        $attributeGroups = AttributeGroup::detectLang()->orderBy('ordering')->get();
        $currencies      = Currency::latest()->get();

        $copy_product = $request->product ? Product::where('slug', $request->product)->first() : null;

        return view('back.products.create', compact(
            'categories',
            'specTypes',
            'sizetypes',
            'attributeGroups',
            'copy_product',
            'currencies'
        ));
    }

    public function edit(Product $product)
    {
        $categories      = Category::detectLang()->where('type', 'productcat')->orderBy('ordering')->get();
        $specTypes       = SpecType::detectLang()->get();
        $sizetypes       = SizeType::detectLang()->get();
        $attributeGroups = AttributeGroup::detectLang()->orderBy('ordering')->get();
        $currencies      = Currency::whereNull('deleted_at')->orWhere('id', $product->currency_id)->latest()->get();

        return view('back.products.edit', compact(
            'product',
            'categories',
            'specTypes',
            'sizetypes',
            'attributeGroups',
            'currencies'
        ));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->only([
            'title',
            'title_en',
            'category_id',
            'size_type_id',
            'weight',
            'unit',
            'type',
            'description',
            'short_description',
            'special',
            'meta_title',
            'image_alt',
            'meta_description',
            'published',
            'currency_id',
            'rounding_amount',
            'rounding_type',
        ]);

        $data['spec_type_id']     = spec_type($request);
        $data['price_type']       = "multiple-price";
        $data['slug']             = $request->slug ?: $request->title;
        $data['publish_date']     = $request->publish_date ? Jalalian::fromFormat('Y-m-d H:i:s', $request->publish_date)->toCarbon() : null;
        $data['special_end_date'] = $request->special_end_date ? Jalalian::fromFormat('Y-m-d H:i:s', $request->special_end_date)->toCarbon() : null;

        $product->update($data);

        // update product brand
        $this->updateProductBrand($product, $request);

        // update product prices
        $this->updateProductPrices($product, $request);

        // update product files
        $this->updateProductFiles($product, $request);

        // update product specifications
        $this->updateProductSpecifications($product, $request);

        // update product images
        $this->updateProductImages($product, $request);

        // update product categories
        $this->updateProductCategories($product, $request);

        // update product labels
        $this->updateProductLabels($product, $request);

        // update product sizes
        $this->updateProductSizes($product, $request);

        toastr()->success('محصول با موفقیت ویرایش شد.');

        return response("success");
    }

    public function image_store(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|image|max:10240',
        ]);

        $image = $request->file('file');

        $currentDate = Carbon::now()->toDateString();
        $imagename = 'img' . '-' . $currentDate . '-' . uniqid() . '.' . $image->getClientOriginalExtension();

        // Store the original image temporarily
        // $path =

        // Create instance and resize the image
        $imagePath = $image->storeAs('tmp', $imagename);

        // Log::info(public_path('uploads/' . $imagePath));
        // Check if the file exists
        if (!file_exists(public_path('uploads/' . $imagePath))) {
            // Log::info('file exists!!');
            return response()->json(['error' => 'File not found.'], 404);
        }

        // Make the image and resize
        try {
            $image = Image::make(public_path('uploads/' . $imagePath))->orientate();

            // Log::info(public_path('uploads/tmp/' . $imagename));
            $image->save(public_path('uploads/tmp/' . $imagename), 25);

            return response()->json(['imagename' => $imagename]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['error' => 'Image processing failed.'], 500);
        }
    }

    public function image_delete(Request $request)
    {
        $filename = $request->get('filename');

        if (Storage::exists('tmp/' . $filename)) {
            Storage::delete('tmp/' . $filename);
        }

        return response('success');
    }

    public function destroy(Product $product)
    {
        $product->tags()->detach();
        $product->specifications()->detach();

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        foreach ($product->gallery as $image) {
            if (Storage::disk('public')->exists($image->image)) {
                Storage::disk('public')->delete($image->image);
            }

            $image->delete();
        }

        $product->delete();

        return response('success');
    }

    public function multipleDestroy(Request $request)
    {
        $this->authorize('products.delete');

        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:products,id',
        ]);

        foreach ($request->ids as $id) {
            $product = Product::find($id);
            $this->destroy($product);
        }

        return response('success');
    }

    public function generate_slug(Request $request)
    {
        $request->validate([
            'title' => 'required',
        ]);

        $slug = SlugService::createSlug(Product::class, 'slug', $request->title);

        return response()->json(['slug' => $slug]);
    }

    public function export(Request $request)
    {
        $this->authorize('products.export');

        $products = Product::detectLang()->datatableFilter($request)->get();

        switch ($request->export_type) {
            case 'excel': {
                    return $this->exportExcel($products, $request);
                    break;
                }
            default: {
                    return $this->exportPrint($products, $request);
                }
        }
    }

    //------------- Category methods

    public function categories()
    {
      
        $this->authorize('products.category');

        $categories = Category::detectLang()->where('type', 'productcat')->whereNull('category_id')
            ->with('childrenCategories')
            ->orderBy('ordering')
            ->get();

        return view('back.products.categories', compact('categories'));
    }

    private function updateProductPrices(Product $product, Request $request)
    {
        if ($product->isDownload()) {
            return;
        }

        $prices_id = [];

        foreach ($request->prices as $price) {

            $attributes = array_filter($price['attributes'] ?? []);

            $update_price = false;

            foreach ($product->prices()->withTrashed()->get() as $product_price) {
                $product_price_attributes = $product_price->get_attributes()->get()->pluck('id')->toArray();

                sort($attributes);
                sort($product_price_attributes);

                if ($attributes == $product_price_attributes) {
                    $update_price = $product_price;
                    break 1;
                }
            }

            if ($update_price) {

                $update_price->update([
                    "price"              => $price["price"],
                    "discount"           => $price["discount"],
                    "discount_price"     => get_discount_price($price["price"], $price["discount"], $product),
                    "regular_price"      => get_discount_price($price["price"], 0, $product),
                    "stock"              => $price["stock"],
                    "cart_max"           => $price["cart_max"],
                    "cart_min"           => $price["cart_min"],
                    "discount_expire_at" => $price["discount_expire_at"] ? Jalalian::fromFormat('Y-m-d H:i:s', $price["discount_expire_at"])->toCarbon() : null,
                    "deleted_at"         => null,
                ]);

                $update_price->get_attributes()->sync($attributes);

                $prices_id[] = $update_price->id;
            } else {

                $insert_price = $product->prices()->create(
                    [
                        "price"              => $price["price"],
                        "discount"           => $price["discount"],
                        "discount_price"     => get_discount_price($price["price"], $price["discount"], $product),
                        "regular_price"      => get_discount_price($price["price"], 0, $product),
                        "stock"              => $price["stock"],
                        "cart_max"           => $price["cart_max"],
                        "cart_min"           => $price["cart_min"],
                        "discount_expire_at" => $price["discount_expire_at"] ? Jalalian::fromFormat('Y-m-d H:i:s', $price["discount_expire_at"])->toCarbon() : null,
                    ]
                );

                foreach ($attributes as $attribute) {
                    $insert_price->get_attributes()->attach([$attribute]);
                }

                $prices_id[] = $insert_price->id;
            }
        }

        $product->prices()->whereNotIn('id', $prices_id)->delete();

        DB::table('cart_product')
            ->where('product_id', $product->id)
            ->whereNotNull('price_id')
            ->whereNotIn('price_id', $prices_id)
            ->delete();

        event(new ProductPricesChanged($product));
    }

    private function updateProductFiles(Product $product, Request $request)
    {
        if ($product->isPhysical()) {
            return;
        }

        $prices_id = [];
        $ordering = 1;

        foreach ($request->download_files as $price) {

            $update_price = false;

            if (isset($price['price_id'])) {
                $update_price = $product->prices()->withTrashed()->where('prices.id', $price['price_id'])->first();
            }

            if ($update_price) {

                $update_price->update([
                    "price"          => $price["price"],
                    "discount"       => $price["discount"],
                    "discount_price" => get_discount_price($price["price"], $price["discount"], $product),
                    "regular_price"  => get_discount_price($price["price"], 0, $product),
                    "deleted_at"     => null,
                    "ordering"       => $ordering++
                ]);

                $update_price->updateFile($price['title'], $price['file'] ?? null, $price['status']);

                $prices_id[] = $update_price->id;
            } else {
                $insert_price = $product->prices()->create(
                    [
                        "price"          => $price["price"],
                        "discount"       => $price["discount"],
                        "discount_price" => get_discount_price($price["price"], $price["discount"], $product),
                        "regular_price"  => get_discount_price($price["price"], $price["discount"], $product),
                        "ordering"       => $ordering++
                    ]
                );

                $insert_price->createFile($price['title'], $price['file'], $price['status']);

                $prices_id[] = $insert_price->id;
            }
        }

        $delete_prices = $product->prices()->whereNotIn('id', $prices_id)->get();

        foreach ($delete_prices as $delete_price) {
            $file = $delete_price->file;

            if ($file) {
                Storage::disk('downloads')->delete('product-files/' . $file->file);
                $file->delete();
            }

            $delete_price->delete();
        }
    }

    private function updateProductSpecifications(Product $product, Request $request)
    {
        $product->specifications()->detach();
        $group_ordering = 0;

        if ($request->specification_group) {
            foreach ($request->specification_group as $group) {

                if (!isset($group['specifications'])) {
                    continue;
                }

                $spec_group = SpecificationGroup::firstOrCreate([
                    'name' => $group['name'],
                ]);

                $specification_ordering = 0;

                foreach ($group['specifications'] as $specification) {
                    $spec = Specification::firstOrCreate([
                        'name' => $specification['name']
                    ]);

                    $product->specifications()->attach([
                        $spec->id => [
                            'specification_group_id' => $spec_group->id,
                            'group_ordering'         => $group_ordering,
                            'specification_ordering' => $specification_ordering++,
                            'value'                  => $specification['value'],
                            'special'                => isset($specification['special']) ? true : false
                        ]
                    ]);
                }

                $group_ordering++;
            }
        }
    }

    private function updateProductBrand(Product $product, Request $request)
    {
        if ($request->brand) {
            $brand = Brand::firstOrCreate(
                [
                    'name'    => $request->brand,
                    'lang'    => app()->getLocale(),
                ],
                [
                    'slug' => $request->brand,
                ]
            );

            $product->update([
                'brand_id' => $brand->id
            ]);
        }
    }

    private function updateProductImages(Product $product, Request $request)
    {
        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $file = $request->image;
            $name = uniqid() . '_' . $product->id . '.' . $file->getClientOriginalExtension();
            $request->image->storeAs('products', $name);

            $product->image = '/uploads/products/' . $name;
            $product->save();
        }

        $product_images = $product->gallery()->pluck('image')->toArray();
        $images         = explode(',', $request->images);
        $deleted_images = array_diff($product_images, $images);

        foreach ($deleted_images as $del_img) {
            $del_img = $product->gallery()->where('image', $del_img)->first();

            if (!$del_img) {
                continue;
            }

            if (Storage::disk('public')->exists($del_img)) {
                Storage::disk('public')->delete($del_img);
            }

            $del_img->delete();
        }

        $ordering = 1;

        if ($request->images) {

            foreach ($images as $image) {

                if (Storage::exists('tmp/' . $image)) {

                    Storage::move('tmp/' . $image, 'products/' . $image);

                    $product->gallery()->create([
                        'image'    => '/uploads/products/' . $image,
                        'ordering' => $ordering++,
                    ]);
                } else {
                    $product->gallery()->where('image', $image)->update([
                        'ordering' => $ordering++,
                    ]);
                }
            }
        }
    }

    private function updateProductCategories(Product $product, Request $request)
    {
        if ($request->categories) {
            $product->categories()->sync(array_merge($request->categories, [$product->category_id]));
        } else {
            $product->categories()->sync([$product->category_id]);
        }
    }

    private function updateProductLabels(Product $product, Request $request)
    {
        $label_ids = [];

        if ($request->labels) {
            $labels = explode(',', $request->labels);

            foreach ($labels as $item) {
                $label = Label::firstOrCreate([
                    'title'    => $item,
                    'lang'     => app()->getLocale(),
                ]);

                $label_ids[] = $label->id;
            }
        }

        $product->labels()->sync($label_ids);
    }

    private function updateProductSizes(Product $product, Request $request)
    {
        $product->sizes()->detach();

        if (!$request->sizes) return;

        $ordering      = 1;
        $groupOrdering = 1;

        foreach ($request->sizes as $group => $sizes) {

            foreach ($sizes as $size_id => $value) {
                $product->sizes()->attach(
                    [
                        $size_id => [
                            'group'    => $groupOrdering,
                            'value'    => $value,
                            'ordering' => $ordering++
                        ]
                    ]
                );
            }

            $groupOrdering++;
        }
    }

    private function exportExcel($products, Request $request)
    {
        return Excel::download(new ProductsExport($products, $request), 'products.xlsx');
    }

    private function exportPrint($products, Request $request)
    {
        //
    }
}
