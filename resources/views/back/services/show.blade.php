<div class="d-flex justify-content-center">
    <div class="col-lg-3 col-md-3 col-sm-6" style="border-radius: 20px; border:1px solid grey">
        <img src="{{ $service->photo }}" alt="{{ $service->full_name }}">
    </div>
</div>
<div class="">
    <div class="row mt-3">
        <div class="col col-4">
            <h4>
                نام فروشگاه:
            </h4>
        </div>
        <div class="col col-4">
            <h4>
                {{ $service->shop->nameofstore }}
            </h4>
        </div>
        <div class="col col-4">

        </div>
    </div>
    <div class="row mt-3">
        <div class="col col-4">
            <h4>
                اسم امکانات:
            </h4>
        </div>
        <div class="col col-4">
            <h4>
                {{ $service->name }}
            </h4>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col col-4">
            <h4>
                توضیحات :
            </h4>
        </div>
        <div class="col col-4">
            <p>
                {!! $service->description !!}
            </p>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col col-4">
            <h4>
                کد QR:
            </h4>
        </div>
        <div class="col col-4 d-flex align-items-start">
            <h4>
                <img src="{{ asset($service->QR_code_link . '.svg') }}" alt="">
                <button type="button" class="badge badge-pill badge-success download-button" download
                    style="position: absolute; top:15%; left:15%" data-name="{{ $service->name }}"
                    data-url="{{ asset($service->QR_code_link . '.png') }}">
                    <i class="feather icon-download"></i></button>
            </h4>
        </div>
    </div>
</div>
