{{-- @isset($state) --}}
<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel19">فرم اشتراک در مسابقه!</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">

        <form action="{{ route('front.participant.participatStore') }}" id="participantForm">
            @csrf
            <div class="contaienr dir-ltr text-start">
                <div class="progress-container m-auto">
                    <div class="progress" id="progress"></div>
                    <div class="circle stepper-active">1</div>
                    <div class="circle">2</div>
                    <div class="circle">3</div>
                    <div class="circle">4</div>
                </div>
                <div class="tab-content  dir-rtl">
                    <div id="step-1" class=" tab-pane active my-2 py-3 stepper-content">
                        <small class="text-info">لطفا مسابقه مد نظر خود را انتخاب نمایید!</small>
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="m-1">
                                    انتخاب مسابقه:
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-6">
                                <div class="m-1">
                                    <select name="post_id" class="form-control post-id">
                                        @foreach ($post as $key)
                                            <option value="{{ $key->id }}">{{ $key->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="step-2" class=" tab-pane my-2 py-3 stepper-content">
                        Step 2 Content
                    </div>
                    <div id="step-3" class=" tab-pane my-2 py-3 stepper-content">
                        Step 3 Content
                    </div>
                    <div id="step-4" class=" tab-pane my-2 py-3 stepper-content">
                        Step 4 Content
                    </div>
                </div>
                <div class="text-right">
                    <button class="btn stepper-btn" id="prev" type="button" disabled>قبلی</button>
                    <button type="button" class="btn stepper-btn" id="next">بعدی</button>
                    <button class="btn stepper-btn" id="submit" type="button"
                        onclick="participantFormSubmit()">ارسال</button>
                </div>
            </div>
        </form>
    </div>

    <div class="modal-footer">

    </div>
</div>
{{-- @else
    <div class="d-flex justify-content-center align-items-center text-center">
        <div class="alert alert-warning">
            وضعیتی برای این شرکت کننده وجود ندارد!
        </div>
    </div>
@endisset --}}
