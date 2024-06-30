<!-- Product Info -->

<div class="col-lg-12 p-3 p-lg-5 mt-3 col-md-12 pb-5 product-info-block ">
    <div class="product-info dt-sl">
        <div class="product-title">
            <h1>توضیحات</h1>
            {{-- <h3 class="mb-1">{{ $shop->title_en }}</h3> --}}
        </div>
        <div class="row p-2">
            <div class="col-md-12 col-lg-12 p-2">
                <hr class="border-product-title">
                @isset($employee)

                    @if ($employee->description)
                        <p class="little-des pt-0 mt-0">{!! nl2br($employee->description) !!}</p>
                    @endif
                @endisset
                @isset($service)

                    @if ($service->description)
                        <p class="little-des pt-0 mt-0">{!! nl2br($service->description) !!}</p>
                    @endif
                @endisset
            </div>

        </div>

    </div>
</div>
