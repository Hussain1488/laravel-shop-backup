<div class="modal fade" id="general_modal">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">
                    درخواست کد قرعه کشی
                </h4>
            </div>
            <hr />
            <!-- Modal body -->
            <div class="modal-body">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" style="font-size: .625rem" data-toggle="tab" href="#home">
                            کد قرعه کشی با کد روزانه</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="font-size: .625rem" data-toggle="tab" href="#menu1">
                            کد قرعه کشی با فاکتور خرید
                        </a>
                    </li>


                </ul>
                <div class="tab-content ">
                    <div id="home" class="container tab-pane active my-2 py-3">
                        <form id="daily_code_insert_form" action="{{ route('front.lottery.dailyCode') }}"
                            class="setting_form" method="get">
                            @csrf
                            <div class="row d-flex justify-content-center">
                                <div class="col-lg-8">
                                    <div class="form-row-title">
                                        <h6 class="">
                                            کد روزانه را وارد کنید.
                                        </h6>
                                    </div>
                                    <div class="form-row form-group">
                                        <input type="number" class="form-control input-ui pr-2 amount-input"
                                            name="daily_code">
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <div class="col-lg-8">
                                    <div class="form-row-title">
                                        <h6>منبع کد را انتخاب کنید.</h6>
                                    </div>
                                    <div class="form-row form-group">
                                        <select class="form-control py-0 gateway-select" name="code_source" required>
                                            <option class="" value="">منبع را انتخاب کنید

                                            </option>
                                            <option class="" value="insta">
                                                اینستاگرام
                                            </option>
                                            <option class="" value="rubika">
                                                روبیکا
                                            </option>
                                            <option class="" value="eitaa">
                                                ایتا
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-row mt-3 justify-content-center my-5">
                                <button id="lottery-daily-code-button" action="{{ route('front.lottery.dailyCode') }}"
                                    type="button" class="btn-primary-cm btn-with-icon ml-2">
                                    <i class="mdi mdi-arrow-left"></i>
                                    ارسال کد روزانه
                                </button>
                            </div>
                        </form>
                    </div>
                    <div id="menu1" class="container tab-pane my-2 py-3">
                        <form action="{{ route('front.lottery.invoiceCode') }}" class="" method="POST"
                            id="invoice_code_insert_form" enctype="multipart/form-data">
                            @csrf
                            <div class="row d-flex justify-content-center">
                                <div class="col-lg-8">
                                    <div class="form-row-title">
                                        <h6 class="">
                                            شماره فاکتور را وارد کنید
                                        </h6>
                                    </div>
                                    <div class="form-row form-group">
                                        <input type="number" class="form-control  pr-2 factore-number-input"
                                            name="number">
                                    </div>
                                </div>
                            </div>
                            @error('number')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                            
                            <div class="row d-flex justify-content-center">
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label>مبلغ فاکتور</label>
                                        <div class="d-flex align-items-center">
                                            <input class="form-control moneyInput" name="amount"
                                                style="margin-left: 4px"> ریال
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('amount')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                            {{-- </div> --}}

                            <div class="row d-flex justify-content-center">
                                <div class="col-lg-8">
                                    <div class="form-row-title">
                                        <h6>بار گزاری عکس فاکتور</h6>
                                    </div>
                                    <div class="form-row form-group">
                                        <input type="file" accept="image/*" class="form-control py-0" required
                                            name="invoice_img">

                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-row mt-3 justify-content-center">
                                <button id="lottery-invoice-code-button"
                                    action="{{ route('front.lottery.invoiceCode') }}" type="button"
                                    class="btn-primary-cm btn-with-icon ml-2">
                                    <i class="mdi mdi-arrow-left"></i>
                                    ارسال فاکتور خرید
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

                <!-- Modal footer -->

                <button type="button" class="btn btn-danger btn-sm w-auto" data-dismiss="modal">بستن</button>

            </div>
        </div>
    </div>
</div>
