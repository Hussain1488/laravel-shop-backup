<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel19">لیست پست های تعریف شده برای {{ $participant->name }} </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body modal-state-content">
        <div class="row my-2 p-1 text-right">
            <div class="col">
                اسم اشتراک کننده:
            </div>
            <div class="col">
                <span class="form-control">{{ $participant->name }}</span>
            </div>
            <div class="col">
                تاریخ ثبت:
            </div>
            <div class="col">
                <span class="form-control">

                    {{ Jdate($participant->created_at)->format('Y/m/d') }}
                </span>
            </div>
        </div>
        @if ($state->count() > 0)
            <ul class="nav nav-tabs">
                @foreach ($state as $key)
                    <li class="nav-item nav-info">
                        <a class="nav-link {{ $loop->first ? 'active' : '' }}" style="font-size: .625rem"
                            data-toggle="tab" href="#tab-{{ $key->id }}">
                            <span class=" " style="font-size: 16px">پست {{ $loop->index + 1 }}</span>
                            {{-- {{ $key->state == 'valid' ? 'badge-success' : ($key->state == 'pending' ? 'badge-warning' : 'badge-danger') }} --}}
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content ">
                @foreach ($state as $key)
                    <div id="tab-{{ $key->id }}"
                        class="container tab-pane my-2 py-3 {{ $loop->first ? 'active' : '' }}">
                        <div class="row my-1">
                            <div class="col col-6">
                                <div class="row">
                                    <div class="col-6 my-1">
                                        اسم پست:
                                    </div>
                                    <div class="col-6 my-1">
                                        {{ $key->name }}
                                    </div>
                                    <div class="col-6 my-2">
                                        وضعیت:
                                    </div>
                                    <div class="col-6 my-2">
                                        {{-- {{ $key->state == 'valid' ? 'badge-success' : ($key->state == 'pending' ? 'badge-warning' : 'badge-danger') }} --}}
                                        <span
                                            class="btn {{ $key->state == 'valid' ? 'btn-success' : ($key->state == 'pending' ? 'btn-warning' : 'btn-danger') }}">
                                            {{ $key->state == 'valid' ? 'تأیید شده' : ($key->state == 'pending' ? 'انتظار تأیید' : 'رد شده') }}
                                        </span>
                                    </div>
                                    <div class="col-6 my-1">
                                        <h6>لینک ویدئو:</h6>

                                        <a href="{{ $key->video }}">{{ $key->video }}</a>

                                    </div>
                                </div>
                            </div>
                            <div class="col col-6">

                                <h6>تصاویر:</h6>
                                <div class="row">
                                    @if ($key->photo)
                                        @php
                                            $photos = explode(',', $key->photo);
                                        @endphp
                                        @foreach ($photos as $img)
                                            <div class="d-flex flex-wrap">
                                                <div class="d-flex align-items-start">
                                                    <img src="{{ $img }}" class="GalleryImage" alt="Photo">
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="alert alert-warning">
                                            تصویری برای نمایش وجود ندارد
                                        </div>
                                    @endif


                                </div>
                            </div>
                        </div>
                        <div class="row">




                        </div>

                        @if ($key->state != 'valid')
                            <button class="btn btn-info btn-sm participant-edit-button"
                                data-action="{{ route('front.participant.stateForm', [$key->id]) }}">اصلاح
                                پست</button>
                        @endif

                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center">
                <div class="alert alert-warning">
                    پستی برای این اشتراک شما وجود ندارد!
                </div>
            </div>
        @endif
    </div>

</div>
