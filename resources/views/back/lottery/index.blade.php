@extends('back.layouts.master')

@section('content')
    @use \Morilog\Jalali\Jalalian;
    <input type="hidden" name="id" value="" class="lottery_code_id">
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
                                    <li class="breadcrumb-item active">گزارش قرعه کشی
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



                                    <div class="row mt-1 ml-2 mb-2">
                                        <h3>
                                            گزارش قرعه کشی
                                        </h3>
                                    </div>


                                    <table class="table table-hover" id="lottery_codes_table"
                                        data-action={{ route('admin.lottery.lotteryCodeNumbers') }}>

                                    </table>

                                </div>
                            </div>

                        </div>
                    </div>
                </section>


            </div>
        </div>
    </div>
    @include('back.lottery.modal')
@endsection
@include('back.partials.plugins', [
    'plugins' => ['persian-datepicker', 'jquery.validate'],
])
@push('scripts')
    <script src="{{ asset('back/assets/datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('back/assets/js/pages/lottery/index.js') }}?v=51"></script>
@endpush
