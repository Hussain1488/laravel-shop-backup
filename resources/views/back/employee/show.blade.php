<div class="d-flex justify-content-center">
    <div class="col-lg-3 col-md-3 col-sm-6" style="border-radius: 20px; border:1px solid grey">
        <img src="{{ $employee->photo }}" alt="{{ $employee->full_name }}">
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
                {{ $employee->shop->nameofstore }}
            </h4>
        </div>
        <div class="col col-4">

        </div>
    </div>
    <div class="row mt-3">
        <div class="col col-4">
            <h4>
                اسم پرسنل:
            </h4>
        </div>
        <div class="col col-4">
            <h4>
                {{ $employee->full_name }}
            </h4>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col col-4">
            <h4>
                سمت پرسنل:
            </h4>
        </div>
        <div class="col col-4">
            <h4>
                {{ $employee->role }}
            </h4>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col col-4">
            <h4>
                کد پرسنل:
            </h4>
        </div>
        <div class="col col-4">
            <h4>
                {{ $employee->unique_id }}
            </h4>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col col-4">
            <h4>
                کد QR:
            </h4>
        </div>
        <div class="col col-4">
            <h4>
                <img src="{{ asset($employee->QR_code_link . '.svg') }}" alt="">
                <button type="button" class="badge badge-pill badge-success download-button"
                    data-name="{{ $employee->full_name }}" style="position: absolute; top:15%; left:15%"
                    data-url="{{ asset($employee->QR_code_link . '.png') }}">
                    <i class="feather icon-download"></i></button>
            </h4>
        </div>
    </div>
</div>
