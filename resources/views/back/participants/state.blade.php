@isset($post)
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel19">لیست پست های تعریف شده برای {{ $participant->name }} </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body modal-state-content">
            <div class="row my-2 p-1">
                <div class="col-12 col-md-6 col-lg-3">
                    اسم اشتراک کننده:
                </div>
                <div class="col-12 col-md-6 col-lg-3 alert alert-success">
                    {{ $participant->name }}
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    تاریخ ثبت:
                </div>
                <div class="col-12 col-md-6 col-lg-3 alert alert-success">
                    {{ Jdate($participant->created_at)->format('Y/m/d') }}
                </div>
            </div>
            <ul class="nav nav-tabs">
                @foreach ($post as $key)
                    <li class="nav-item">
                        <a class="nav-link {{ $loop->first ? 'active' : '' }}" style="font-size: .625rem" data-toggle="tab"
                            href="#tab-{{ $key->id }}">
                            <span
                                class="badge {{ $key->state == 'valid' ? 'badge-success' : ($key->state == 'pending' ? 'badge-warning' : 'badge-danger') }} badge-lg">{{ $key->name }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content ">
                @foreach ($post as $key)
                    <div id="tab-{{ $key->id }}"
                        class="container tab-pane my-2 py-3 {{ $loop->first ? 'active' : '' }}">
                        <div class="row p-1">
                            <div class="col-4 col-md-3 col-lg-2">
                                اسم پست:
                            </div>
                            <div class="col">
                                {{ $key->name }}
                            </div>
                        </div>
                        <hr />
                        <div class="row p-1">
                            <div class="col-4 col-md-3 col-lg-2">
                                لینک ویدئو:
                            </div>
                            <div class="col">
                                {{ $key->video }}
                            </div>
                        </div>
                        <hr />
                        <div class="row p-1">
                            <div class="col-4 col-md-3 col-lg-2">
                                کپشن:
                            </div>
                            <div class="col">
                                {{ $key->caption }}
                            </div>
                        </div>
                        <hr />

                        <div class="row">
                            <div class="col-12 my-1">
                                <h2>تصاویر:</h2>
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
                        <button class="btn btn-danger btn-sm participant-stat-delete" data-id="{{ $key->id }}">حذف
                            پست</button>
                        <button class="btn btn-info btn-sm participant-edit-button"
                            data-action="{{ route('admin.participant.postEdit', [$key->id]) }}">اصلاح
                            پست</button>

                    </div>
                @endforeach
            </div>
        </div>
        <div class="modal-footer dir-ltr">
            <button data-id="{{ $participant->id }}"class="btn btn-outline-danger btn-sm delete-participat-button"
                style="margin-right:2px; margin-left:2px">حذف شرکت کننده</button>
            <button data-id="{{ $participant->id }}"
                class="btn btn-outline-success btn-sm add-participant-state-button">پست جدید
            </button>
        </div>

    </div>
@else
    <div class="d-flex justify-content-center align-items-center text-center">
        <div class="alert alert-warning">
            پستی برای این شرکت کننده وجود ندارد!
        </div>
    </div>
@endisset
