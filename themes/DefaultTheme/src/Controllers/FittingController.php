<?php

namespace Themes\DefaultTheme\src\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AttributeGroup;
use App\Models\Favorite;
use App\Models\Product;
use App\Models\User;
use App\Models\VirtualFitting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class FittingController extends Controller
{
    public function index()
    {

        $user = Auth::user();

        $fittings = $user->fitting()->with(
            'product'
        )->get();
        // dd($fittings);

        return view('front::user.fitting.index', compact('fittings'));
    }

    public function show(VirtualFitting $fitting)
    {
        if (!$fitting->product->isShowable()) {
            abort(404);
        }

        if ($fitting->product->category) {
            $related_products = Product::published()
                ->where('id', '!=', $fitting->product->id)
                ->where('category_id', $fitting->product->category->id)
                ->orderByStock()
                ->latest()
                ->take(6)
                ->get();
        } else {
            $related_products = Product::published()
                ->where('id', '!=', $fitting->product->id)
                ->whereNull('category_id')
                ->orderByStock()
                ->latest()
                ->take(6)
                ->get();
        }

        $fitting->product->load(['comments' => function ($query) {
            $query->whereNull('comment_id')->where('status', 'accepted')->latest();
        }]);

        $reviews = $fitting->product->reviews()
            ->accepted()
            ->latest()
            ->take(10)
            ->get();

        $selected_price = $fitting->product->getPrices()->first();

        $attributeGroups = AttributeGroup::detectLang()->orderBy('ordering')->get();

        $similar_products_count = Product::whereNotIn('id', [$fitting->product->id])
            ->whereNotNull('spec_type_id')
            ->where('spec_type_id', $fitting->product->spec_type_id)
            ->published()
            ->count();

        $show_prices_chart = option('dt_show_price_change_chart', 'yes') == 'yes';

        $fitting->product->increaseViewCount();

        return view('front::user.fitting.show', compact(
            'fitting',
            'related_products',
            'attributeGroups',
            'similar_products_count',
            'selected_price',
            'show_prices_chart',
            'reviews',
        ));
    }

    public function downloadImage($virtualFitting)
    {
        // Increase maximum execution time
        set_time_limit(300);

        // Retrieve the virtual fitting record
        $fitting = VirtualFitting::findOrFail($virtualFitting);
        Log::info('new ' . $fitting);

        // Path to the original image
        $imagePath = public_path($fitting->photo);
        if (!file_exists($imagePath)) {
            Log::error("Image file does not exist at path: " . $imagePath);
            return response()->json(['error' => 'Image file not found'], 404);
        }

        // Create an image instance
        $img = Image::make($imagePath);

        // Resize the image
        // $img->resize(800, null, function ($constraint) {
        //     $constraint->aspectRatio();
        // });
        $svgPath = public_path('test.png');
        // Add SVG watermark
        $svgContent = file_get_contents($svgPath);

        // Save SVG content to a temporary file
        $tempSvgPath = tempnam(sys_get_temp_dir(), 'svg');
        file_put_contents($tempSvgPath, $svgContent);

        $watermark = Image::make($svgPath);

        // Resize the watermark
        $watermark->resize(400, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        // Insert the SVG watermark
        $img->insert($watermark, 'bottom-right', 30, 30);

        // Insert the second watermark at the top-left corner
        $img->insert(
            $watermark,
            'top-left',
            10,
            10
        );

        // Add text watermark
        $img->text('Watermark Text', $img->width() - 10, $img->height() - 10, function ($font) {
            $font->file(public_path('iranyekanwebbold(fanum).ttf'));
            $font->size(48);
            $font->color('rgba(255, 255, 255, 1)');
            $font->align('right');
            $font->valign('bottom');
        });

        // Encode the image
        $encodedImage = $img->encode('png');

        // Create a temporary file path and save the watermarked image
        $tempPath = 'watermarked-images/watermarked-image.png';
        Storage::put($tempPath, (string) $encodedImage);

        // Return the image as a base64-encoded string
        $base64 = base64_encode($encodedImage);

        return response()->json(['image' => $base64]);
    }

    public function delete()
    {
    }
}
