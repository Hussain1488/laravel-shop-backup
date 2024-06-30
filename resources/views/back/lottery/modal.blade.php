<div class="modal fade" id="lottery_code_more_modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">
                    کد قرعه کشی
                </h4>
            </div>
            <hr />
            <!-- Modal body -->
            <div class="modal-body">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" style="font-size: .625rem" data-toggle="tab" href="#menu">
                            قرعه کشی</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="font-size: .625rem" data-toggle="tab" href="#menu1">
                            وضعیت</a>
                    </li>


                </ul>
                <div class="tab-content ">
                    <div id="menu" class="container tab-pane active my-2 py-3">
                        <form id="lottery_winner_form" action="{{ route('admin.lottery.codeWonState') }}"
                            class="setting_form" method="POST">
                            @csrf
                            <div class="mb-1">
                                <h4 style="font-size: 15px">
                                    ثبت برنده:
                                </h4>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <div class="col-lg-8">
                                    <div class="form-row-title">
                                        <h6>نوع قرعه کشی</h6>
                                    </div>
                                    <div class="form-row form-group">
                                        <select class="form-control py-0 gateway-select" name="type" required>
                                            <option class="" value="">انتخاب نوعیت قرعه کشی</option>
                                            <option class="" value="weekly">
                                                هفته ای
                                            </option>
                                            <option class="" value="monthly">
                                                ماهانه
                                            </option>
                                            <option class="" value="yearly">
                                                سالانه
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <div class="col-lg-8">
                                    <div class="form-row-title">
                                        <h6>توضیحات</h6>
                                    </div>
                                    <div class="form-row form-group">
                                        <textarea class="form-control py-0 gateway-select" name="description" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-row  justify-content-center m-1">
                                <button id="lottery-winner-code-button" type="button"
                                    class="btn btn-primary btn-with-icon ">
                                    ارسال
                                    <i class="feather icon-chevrons-left"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div id="menu1" class="container tab-pane my-2 py-3">
                        <form id="lotteryStateCodeForm" action="{{ route('admin.lottery.CodeState') }}" class=""
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-1">
                                <h4 style="font-size: 15px">
                                    وضعیت کد قرعه کشی:
                                </h4>
                            </div>
                            <div class="row mx-2">
                                <div>
                                    <div class="">
                                        <label class="form-check-label " style="font-size: 15px ">
                                            فعال
                                            <input type="radio" style="width: 20px; height:20px"
                                                class="form-check-input ml-2"value="active" name="state">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row my-1 mx-2 mb-5 pb-1">
                                <div>
                                    <div class="">
                                        <label class="form-check-label " style="font-size: 15px ">
                                            باطل
                                        </label>
                                        <input type="radio" style="width: 20px; height:20px"
                                            class="form-check-input ml-2" value="deactive" name="state">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-row m-1  justify-content-center">
                                <button id="lottery-code-state-button" type="button" class="btn btn-primary">
                                    <i class="feather icon-chevrons-left"></i>
                                    ارسال
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
