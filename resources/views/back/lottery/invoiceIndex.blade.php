@extends('back.layouts.master')

@section('content')
    @use \Morilog\Jalali\Jalalian;

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb no-border">
                                    <li class="breadcrumb-item">قرعه کشی
                                    </li>
                                    <li class="breadcrumb-item active">فاکتور فروش
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section class="card">
                    <div class="card-header">
                        <h4 class="card-title"></h4>
                    </div>
                    <div class="card-content">
                        <div class="container mt-3">
                            <!-- Tab panes -->
                            <div class="">
                                <div id="home" class="container tab-pane "><br>

                                    <div class="row">
                                        <div class="ml-1">
                                        </div>
                                    </div>
                                    <div class="row mt-1 ml-2 mb-2">
                                        <h3>
                                            فاکتور فروش
                                        </h3>
                                    </div>
                                    <table class="table table-hover" id="invoice_code_table"
                                        action={{ route('admin.lottery.invoicesDatatable') }}>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <div class="modal fade" id="validationChicking">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">اعتبار سنجی فاکتور فروش<span class="text-success"
                            id="deposit_amount_show"></span>
                    </h4>
                </div>
                <!-- Modal body -->
                <div class="modal-body">

                    <div class="d-flex justify-content-center">
                        <img style="max-width: 100%; max-height: 100%;" class="invoiceImage" id="" src=""
                            alt="عکس فاکتور">
                    </div>
                    <div class="d-flex justify-content-around p-2 text-lg text-dark">
                        <div>
                            مبلغ خرید: <span class="text-success invoice-price monyInputSpan"></span> ریال
                        </div>
                        <div>
                            شماره فاکتور: <span class="text-success invoice-code-number"></span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-around">
                        <input type="button" class="btn btn-success validationValidateButton" value="تأیید اعتبار" />
                        <input type="button" data-action=""
                            class="btn
                            btn-warning invoiceRejectButton" value="رد اعتبار" />
                    </div>
                    <input type="hidden" id="selectedInvoice">
                </div>
                <!-- Modal footer -->
            </div>
        </div>
    </div>
    <div class="modal fade" id="validationValidateModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">تأیید فاکتور برای تولید کد قرعه کشی<span class="text-success"
                            id="deposit_amount_show"></span>
                    </h4>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <form id="invoiceValidationForm" action="{{ route('admin.lottery.invoiceValidation') }}" method="POST">
                        @csrf
                        <div class="d-flex justify-content-around my-1">
                            <div class="col">
                                <label for="">تعداد کد های قرعه کشی برای این فاکتور</label>
                            </div>
                            <div class="col">
                                <input required type="number" class="form-control" name="lottery_code_number">
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-between">
                            <input type="button" id="invoiceValidationButton" class="btn btn-success" value="تأیید">
                            <input type="button" class="btn btn-danger" data-dismiss="modal" value="انصراف">
                        </div>
                    </form>
                    <input type="hidden" id="selectedInvoice">
                </div>
                <!-- Modal footer -->
            </div>
        </div>
    </div>
    <div class="modal image_modal fade" style="max-height: 90%" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="max-height: 90%">
                    <img style="max-width: 100%; max-height: 100%;" id="invoiceImage" src="" alt="عکس فاکتور">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm w-full" data-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@include('back.partials.plugins', [
    'plugins' => ['persian-datepicker', 'jquery.validate'],
])
@push('scripts')
    <script>
        var rejectionUrl = '{{ route('admin.lottery.invoiceRejection') }}';
        var validationUrl = '{{ route('admin.lottery.invoiceValidation') }}';
    </script>
    <script src="{{ asset('back/assets/datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('back/assets/js/pages/lottery/invoice.js') }}?v=51"></script>
@endpush
