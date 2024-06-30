<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Requests\Back\EmployeeStoreRequest;
use App\Models\createstore;
use App\Models\EmployeeModal;
use App\Models\File;
use App\Models\ShopServiceReviewModel;
use DOMDocument;
use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Spatie\Browsershot\Browsershot;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{

    /**
     * Display a listing of the resource.
     *
    //  * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employee = EmployeeModal::where('shop_id', Auth::user()->store->id)->get();
        foreach ($employee as $key) {
            $key->review_count = $key->reviewCount();
        }
        // dd($employee);
        return view('back.employee.index', compact('employee'));
    }


    // public function datatable()
    // {
    //     $employee = EmployeeModal::where('shop_id', Auth::user()->store->id)->latest();

    //     return DataTables::eloquent($employee)
    //         ->addColumn('counter', function () {
    //             return null;
    //         })->addColumn('full_name', function ($query) {
    //             return $query->full_name;
    //         })->addColumn('uniqe_id', function ($query) {
    //             return $query->unique_id;
    //         })->addColumn('role', function ($query) {
    //             return $query->role;
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
        $employee = null;
        return view('back.employee.create', compact('employee'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeStoreRequest $request)
    {
        // dd($request->all());
        $shop = createstore::where('user_id', Auth::user()->id)->first();
        $employeeCount = EmployeeModal::where('shop_id', $shop->id)->count();
        $unique_id = $shop->id . '' . $employeeCount;


        $paths = [];


        if ($request->file('photo')) {
            $file = $request->file('photo');

            $imageName = time() . '_store.' . $file->getClientOriginalExtension();
            $file->move('document/createstore/', 'employee' . $imageName);
            $path = '/document/createstore/' . 'employee' . $imageName;
        }
        $newEmployee = [
            'unique_id'      =>   $unique_id,
            'full_name'      =>   $request->full_name,
            'role'           =>   $request->role,
            'photo'          =>   $path,
            'title'          =>   $request->title,
            'QR_code_link'   =>   '',
            'description'    =>   $request->description,
        ];


        try {
            DB::beginTransaction();
            $employee = $shop->employee()->create($newEmployee);
            $qr_link = $this->generateQrCode(url('/shops/employee') . '/' . $employee->id);
            $employee->update([
                'QR_code_link' => $qr_link
            ]);
            DB::commit();
            toastr()->success('پرسنل جدید با موفقیت اضافه شد!');
            return redirect()->route('admin.employee.index');
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

    public function show($id)
    {
        $employee = EmployeeModal::find($id);

        return view('back.employee.show', compact('employee'))->render();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = EmployeeModal::findOrFail($id);
        if ($employee) {
            return view('back.employee.create', compact('employee'));
        } else {
            toastr()->warning('صفحه مورد نظر یافت نشد!');
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
    public function update(EmployeeStoreRequest $request, $id)
    {
        $shop = createstore::where('user_id', Auth::user()->id)->first();
        $employee = EmployeeModal::findOrFail($id);


        $path = null;

        if ($request->file('photo')) {
            $file = $request->file('photo');

            $imageName = time() . '_store.' . $file->getClientOriginalExtension();
            $file->move('document/createstore/', 'employee' . $imageName);
            $path = '/document/createstore/' . 'employee' . $imageName;
            Storage::disk('public')->delete($employee->photo);
        }
        $newEmployee = [
            'full_name'      =>   $request->full_name,
            'role'           =>   $request->role,
            'photo'          =>   $path ?? $employee->photo,
            'title'          =>   $request->title,
            'description'    =>   $request->description,
        ];

        try {
            DB::beginTransaction();
            $employee->update($newEmployee);
            DB::commit();
            toastr()->success('پرسنل با موفقیت اصلاح شد!');
            return redirect()->route('admin.employee.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            toastr()->error('متأسفانه خطایی رخ داده است، عملیات انجام نشد.');
            return redirect()->back();
        }
    }

    public function reviews()
    {
        $reviews = ShopServiceReviewModel::where('shop_id', Auth::user()->store->id)->filter()->employee()->paginate(20);
        return view('back.employee.review', compact('reviews'));
    }

    public function showReview($id)
    {
        $review = ShopServiceReviewModel::with('point')->findOrFail($id);
        return view('back.cooperationsales.employeeReviewDetails', compact('review'))->render();
    }

    public function delete($id)
    {
        $employee = EmployeeModal::find($id);
        $file = $employee->QR_code_link;
        if (Storage::disk('public')->exists($file . '.svg')) {
            Storage::disk('public')->delete($file . '.svg');
        }
        if (Storage::disk('public')->exists($file . '.png')) {
            Storage::disk('public')->delete($file . '.png');
        }
        $employee->delete();
        return response('success');
    }

    public function updateReview(request $request, $review)
    {
        $review = ShopServiceReviewModel::findOrFail($review);
        $data = $this->validate(
            $request,
            [
                'title'       => 'required|string',
                'body'        => 'required|string|max:1000',
                'rating'      => 'required|between:1,5',
                'suggest'     => 'nullable|in:yes,no,not_sure',
                'status'      => 'in:pending,accepted,rejected',
                'comment'     => 'in:valid,not_valid'
            ]
        );
        // dd($review);

        $review->update($data);

        $review->point()->delete();

        $request->validate([
            'review.advantages.*' => 'string',
            'review.disadvantages.*' => 'string',
        ]);

        $advantages = $request->input('review.advantages');

        if ($advantages) {
            foreach ($advantages as $advantage) {
                $review->point()->create([
                    'text' => $advantage,
                    'type' => 'positive',
                ]);
            }
        }
        $disadvantages = $request->input('review.disadvantages');
        if ($disadvantages) {
            foreach ($disadvantages as $advantage) {
                $review->point()->create([
                    'text' => $advantage,
                    'type' => 'negative',
                ]);
            }
        }

        $review->employee->refreshRating();

        return response($review);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
