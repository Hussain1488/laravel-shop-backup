<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Requests\Back\ServicesStoreRequest;
use App\Models\createstore;
use App\Models\File;
use App\Models\ShopReviewPhotoModel;
use App\Models\ShopServiceModel;
use App\Models\ShopServiceReviewModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Spatie\Browsershot\Browsershot;
use Yajra\DataTables\Facades\DataTables;

class ServicesController extends Controller
{

    public function index()
    {

        $service = ShopServiceModel::where('shop_id', Auth::user()->store->id)->get();
        foreach ($service as $key) {
            $key->review_count = $key->reviewCount();
        }
        // dd($service);
        return view('back.services.index', compact('service'));
    }


    // public function datatable()
    // {
    //     $services = ShopServiceModel::where('shop_id', Auth::user()->store->id)->latest();

    //     return DataTables::eloquent($services)
    //         ->addColumn('counter', function () {
    //             return null;
    //         })->addColumn('name', function ($query) {
    //             return $query->name;
    //         })->addColumn('role', function ($query) {
    //             return $query->role;
    //         })->addColumn('title', function ($query) {
    //             return $query->title;
    //         })->addColumn('QR_code_link', function ($query) {
    //             return $query->QR_code_link ?: 'ندارد';
    //         })->addColumn('action', function ($query) {
    //             return $query->id;
    //         })->make(true);
    // }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $services = null;
        return view('back.services.create', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServicesStoreRequest $request)
    {
        // dd($request->all());
        $shop = createstore::where('user_id', Auth::user()->id)->first();

        $path = null;

        if ($request->file('photo')) {
            $file = $request->file('photo');
            $imageName = time() . '_store.' . $file->getClientOriginalExtension();
            $file->move('document/createstore/', 'service' . $imageName);
            $path = '/document/createstore/' . 'service' . $imageName;
        }

        $newService = [

            'name'      =>   $request->name,
            'photo'          =>   $path,
            'QR_code_link'   =>   '',
            'description'    =>   $request->description,
        ];
        try {
            DB::beginTransaction();
            $service = $shop->service()->create($newService);
            $qr_link = $this->generateQrCode('https://khaneaghsate.ir/shops/service/' . $service->id);
            $service->update([
                'QR_code_link' => $qr_link
            ]);
            DB::commit();
            toastr()->success('امکانات جدید با موفقیت اضافه شد!');
            return redirect()->route('admin.services.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            toastr()->error('متأسفانه خطایی رخ داده است، عملیات انجام نشد.');
            return redirect()->back();
        }
    }

    public function generateQrCode($data)
    {
        // Set the image driver to GD
        config(['qrcode.image_driver' => 'gd']);

        // Generate QR code image
        $qrcode = QrCode::size(950)->margin(10)->generate($data);

        // Save the QR code as SVG
        $unique_id = uniqid('qrcode_');
        $imageName = $unique_id . '.svg';
        $svgPath  = 'document/QR-code/' . $unique_id;
        file_put_contents($svgPath . '.svg', $qrcode);

        // Convert SVG to PNG
        $pngImagePath = 'document/QR-code/' . $unique_id . '.png';
        $this->convertSVGtoPNG($qrcode, $pngImagePath);

        // Return path to the generated QR code image (SVG)
        return $svgPath;
    }

    public function convertSVGtoPNG($inputSvgPath, $outputImagePath)
    {
        try {
            // Set the path to the Chromium executable if necessary
            Browsershot::html(file_get_contents($inputSvgPath))
                ->setOption('executablePath', '/path/to/chromium')
                ->windowSize(1000, 1000)
                ->format('png')
                ->save($outputImagePath);

            return true; // Or return something indicating success
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the conversion
            \Log::error('Error converting SVG to PNG: ' . $e->getMessage());
            return false; // Or handle failure appropriately
        }
    }




    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $service = ShopServiceModel::find($id);

        return view('back.services.show', compact('service'))->render();
    }
    public function delete($id)
    {
        $service = ShopServiceModel::find($id);
        $file = $service->QR_code_link;
        if (Storage::disk('public')->exists($file . '.svg')) {
            Storage::disk('public')->delete($file . '.svg');
        }
        if (Storage::disk('public')->exists($file . '.png')) {
            Storage::disk('public')->delete($file . '.png');
        }

        $service->delete();

        return response('success');
    }


    public function reviews()
    {
        $reviews = ShopServiceReviewModel::where('shop_id', Auth::user()->store->id)->filter()->service()->paginate(20);
        return view('back.services.review', compact('reviews'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $services = ShopServiceModel::findOrFail($id);
        // dd($services);
        if ($services) {
            return view('back.services.create', compact('services'));
        } else {
            toastr()->error('متأسفانه امکانات مورت نظر پیدا نشد!');
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ServicesStoreRequest $request, $id)
    {
        $shop = createstore::where('user_id', Auth::user()->id)->first();
        $service = ShopServiceModel::findOrFail($id);



        $path = null;

        if ($request->file('photo')) {
            $file = $request->file('photo');
            $imageName = time() . '_store.' . $file->getClientOriginalExtension();
            $file->move('document/createstore/', 'service' . $imageName);
            $path = '/document/createstore/' . 'service' . $imageName;
            $paths[] = $path;
            Storage::disk('public')->delete($service->photo);
        }
        $newService = [
            'name'           =>   $request->name,
            'photo'          =>   $path ?? $service->photo,
            'description'    =>   $request->description,
        ];
        $service = ShopServiceModel::findOrFail($id);
        try {
            DB::beginTransaction();
            $service->update($newService);
            DB::commit();
            toastr()->success('امکانات با موفقیت اصلاح شد!');
            return redirect()->route('admin.services.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            toastr()->error('متأسفانه خطایی رخ داده است، عملیات انجام نشد.');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function deletePhoto($id)
    {
        $photo = ShopReviewPhotoModel::findOrFail($id);
        try {
            File::destroy($photo);
            $photo->delete();
            return response('success');
        } catch (Exception $e) {
            return response('error');
        }
    }
}
