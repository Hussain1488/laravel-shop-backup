@extends('front::layouts.master')

@section('content')
    <main class="main-content dt-sl mt-4 mb-3">
        <div class="container main-container">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">

                    <div class="col-12">

                        <div class="dt-sl">
                            <div class="table-responsive">



                                <div class="row mt-1 ml-2 mb-2 mr-1 d-flex justify-content-around">
                                    <div class="col">

                                        <h6>
                                            مسابقه
                                        </h6>
                                    </div>
                                    <div class="col">
                                        <h6>
                                            <div class="logo-area  mr-2 d-flex justify-content-end">
                                                <input type="image"
                                                    data-action="{{ route('front.participant.participate') }}"
                                                    src="{{ theme_asset('img/match.png') }}"
                                                    class="btn btn-secondary match-participation w-3"
                                                    value="اشتراک در مسابقه">

                                            </div>
                                        </h6>
                                    </div>
                                </div>
                                <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request('tab') != 'winner' && request('tab') != 'invoice' ? 'active' : '' }}"
                                            style="font-size: .625rem" data-toggle="tab" href="#lottery">
                                            مسابقات من</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request('tab') == 'invoice' ? 'active' : '' }}"
                                            style="font-size: .625rem" data-toggle="tab" href="#invoices">
                                            لیست مسابقات
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request('tab') == 'invoice' ? 'active' : '' }}"
                                            style="font-size: .625rem" data-toggle="tab" href="#states">
                                            پست های من
                                        </a>
                                    </li>

                                </ul>
                                <div class="tab-content ">
                                    <div id="lottery"
                                        class="container tab-pane {{ request('tab') != 'winner' && request('tab') != 'invoice' ? 'active' : 'fade' }} my-2 py-3">


                                        <div class="row">
                                            <div class="col-md-6 col-12 d-flex justify-content-between align-items-center">
                                                <div class="form-group d-flex align-items-center">
                                                    <label for="first_name" class="mr-2">
                                                        مسابقات من
                                                    </label>

                                                </div>
                                            </div>

                                        </div>
                                        @if ($participant->count() > 0)
                                            <div class="pc-size" data-screen="pc">

                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                #
                                                            </th>
                                                            <th>
                                                                مسابقه
                                                            </th>
                                                            <th>
                                                                اسم
                                                            </th>
                                                            <th>
                                                                اسم مستعار
                                                            </th>
                                                            <th>
                                                                شماره تماس
                                                            </th>
                                                            <th>
                                                                امتیاز
                                                            </th>
                                                            <th>
                                                                تعداد پست
                                                            </th>
                                                            <th>
                                                                تاریخ ثبت نام
                                                            </th>
                                                            <th>
                                                                عملیات
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    @php
                                                        $counter = 0;
                                                        // ($lottery->currentPage() - 1) * $lottery->perPage() + 1;
                                                    @endphp
                                                    <tbody>
                                                        @foreach ($participant as $key)
                                                            <tr>
                                                                <td>
                                                                    {{ $counter++ }}
                                                                </td>
                                                                <td>
                                                                    {{ $key->post->title }}
                                                                </td>
                                                                <td>
                                                                    {{ $key->name }}
                                                                </td>
                                                                <td>
                                                                    {{ $key->username }}
                                                                </td>
                                                                <td>
                                                                    {{ $key->phone }}
                                                                </td>
                                                                <td>
                                                                    {{ $key->rating }}
                                                                </td>
                                                                <td class="text-success">
                                                                    {{ $key->participantPost->count() }}
                                                                </td>
                                                                <td>
                                                                    {{ jDate($key->created_at)->format('Y-m-d') }}
                                                                </td>
                                                                <td>
                                                                    <input type="button"
                                                                        data-action="{{ route('front.participant.more', [$key->id]) }}"
                                                                        class="btn btn-info btn-sm participant-more"
                                                                        value="بیشتر">
                                                                </td>


                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="mobile-size " data-screen="mobile">
                                                @foreach ($participant as $key)
                                                    <div class=" border rounded mb-1 pb-1">
                                                        <div class="row pt-1">
                                                            <div class="col mr-2">
                                                                <h6 class="">
                                                                    مسابقه:

                                                                </h6>
                                                            </div>
                                                            <div class="col">
                                                                <span class="transaction_datetime">
                                                                    {{ $key->post->title }}
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <div class="row pt-1">
                                                            <div class="col mr-2">
                                                                <h6 class="">
                                                                    اسم:</h6>
                                                            </div>
                                                            <div class="col">
                                                                <span class="text-dark">
                                                                    {{ $key->name }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="row pt-1">
                                                            <div class="col mr-2">
                                                                <h6 class="">
                                                                    اسم مستعار:
                                                                </h6>
                                                            </div>
                                                            <div class="col">
                                                                {{ $key->username }}
                                                            </div>
                                                        </div>
                                                        <div class="row pt-1">
                                                            <div class="col mr-2">

                                                                <h6 class="">
                                                                    شماره تماس:</h6>
                                                            </div>
                                                            <div class="col">
                                                                {{ $key->phone }}
                                                            </div>
                                                        </div>
                                                        <div class="row pt-1">
                                                            <div class="col mr-2">

                                                                <h6 class="">
                                                                    امتیاز:</h6>
                                                            </div>

                                                            <div class="col">
                                                                {{ $key->rating }}
                                                            </div>

                                                        </div>
                                                        <div class="row pt-1">
                                                            <div class="col mr-2">

                                                                <h6 class="">
                                                                    تعداد پست:</h6>
                                                            </div>

                                                            <div class="col">
                                                                {{ $key->participantPost->count() }}
                                                            </div>

                                                        </div>
                                                        <div class="row pt-1">
                                                            <div class="col mr-2">

                                                                <h6 class="">
                                                                    تاریخ ثبت نام:</h6>
                                                            </div>

                                                            <div class="col">
                                                                <span class="">
                                                                    {{ jDate($key->created_at)->format('Y-m-d') }}
                                                                </span>
                                                            </div>

                                                        </div>
                                                        <div class="row pt-1">
                                                            <div class="col mr-2">

                                                                <h6 class="">
                                                                    عملیات:
                                                                </h6>
                                                            </div>

                                                            <div class="col">
                                                                <input type="button"
                                                                    data-action="{{ route('front.participant.more', [$key->id]) }}"
                                                                    class="btn btn-info btn-sm participant-more"
                                                                    value="اصلاح">
                                                            </div>

                                                        </div>

                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="p-3">
                                                <div class="alert alert-warning">
                                                    چیزی برای نمایش نیست!
                                                </div>
                                            </div>
                                        @endif
                                        <div class="mt-3">
                                            {{-- {{ $lottery->appends(['tab' => 'lottery', 'page' => $lottery->currentPage()])->links('front::components.paginate') }} --}}
                                        </div>
                                    </div>
                                    <div id="invoices"
                                        class="container tab-pane my-2 py-3 {{ request('tab') == 'invoice' ? 'active' : 'fade' }}">

                                        <div class="row">
                                            <div class="col-md-6 col-12 d-flex justify-content-between align-items-center">
                                                <div class="form-group d-flex align-items-center">
                                                    <label for="first_name" class="mr-2">
                                                        لیست مسابقات
                                                    </label>

                                                </div>
                                            </div>

                                        </div>


                                        @if ($post->count() > 0)
                                            @php
                                                $counter = 0;
                                                // ($invoice->currentPage() - 1) * $invoice->perPage() + 1;
                                            @endphp
                                            <div class="pc-size" data-screen="pc">

                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                #
                                                            </th>
                                                            <th>
                                                                اسم مسابقه
                                                            </th>
                                                            <th>
                                                                وضعیت
                                                            </th>
                                                            <th>
                                                                برو به
                                                            </th>
                                                            <th>
                                                                تاریخ ایجاد
                                                            </th>
                                                            <th>
                                                                تاریخ انتشار
                                                            </th>
                                                            <th>
                                                                تعداد اشتراک کننده
                                                            </th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($post as $key)
                                                            <tr>
                                                                <td>
                                                                    {{ $counter++ }}
                                                                </td>
                                                                <td>
                                                                    {{ $key->title }}
                                                                </td>
                                                                <td>
                                                                    @if ($key->status == 'pending')
                                                                        <span class="badge badge-info">
                                                                            درحال ثبت نام</span>
                                                                    @elseif ($key->status == 'finished')
                                                                        <span class="badge badge-danger">
                                                                            ختم شده</span>
                                                                    @elseif ($key->status == 'deactive')
                                                                        <span class="badge badge-warning">
                                                                            غیر فعال</span>
                                                                    @else
                                                                        <span class="badge badge-success">
                                                                            در حال انجام
                                                                        </span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($key->status == 'published')
                                                                        <a
                                                                            href="{{ route('front.posts.show', ['post' => $key]) }}">
                                                                            {{ $key->title }}
                                                                        </a>
                                                                    @else
                                                                        <span class="badge badge-warning">انتظار
                                                                            انتشار!</span>
                                                                    @endif

                                                                </td>
                                                                <td>
                                                                    {{ jDate($key->created_at)->format('Y-m-d') }}
                                                                </td>
                                                                <td>
                                                                    {{ jDate($key->publish_date)->format('Y-m-d') }}
                                                                </td>
                                                                <td>
                                                                    {{ $key->participant->count() }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="mobile-size " data-screen="mobile">
                                                @foreach ($post as $key)
                                                    <div class=" border rounded mb-1">
                                                        <div class="row pt-1">
                                                            <div class="col mr-2">
                                                                <h6 class="">
                                                                    اسم مسابقه:

                                                                </h6>
                                                            </div>
                                                            <div class="col">
                                                                <span class="">
                                                                    {{ $key->title }}
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <div class="row pt-1">
                                                            <div class="col mr-2">
                                                                <h6 class="">
                                                                    وضعیت: </h6>
                                                            </div>
                                                            <div class="col">
                                                                @if ($key->status == 'pending')
                                                                    <span class="badge badge-info">
                                                                        درحال ثبت نام</span>
                                                                @elseif ($key->status == 'finished')
                                                                    <span class="badge badge-danger">
                                                                        ختم شده</span>
                                                                @elseif ($key->status == 'deactive')
                                                                    <span class="badge badge-warning">
                                                                        غیر فعال</span>
                                                                @else
                                                                    <span class="badge badge-success">
                                                                        در حال انجام
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="row pt-1">
                                                            <div class="col mr-2">
                                                                <h6 class="">
                                                                    برو به:
                                                                </h6>
                                                            </div>
                                                            <div class="col">
                                                                @if ($key->status == 'published')
                                                                    <a
                                                                        href="{{ route('front.posts.show', ['post' => $key]) }}">
                                                                        {{ $key->title }}
                                                                    </a>
                                                                @else
                                                                    <span class="badge badge-warning">انتظار انتشار!</span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="row pt-1">
                                                            <div class="col mr-2">

                                                                <h6 class="">
                                                                    تاریخ ایجاد:</h6>
                                                            </div>

                                                            <div class="col">
                                                                {{ jDate($key->created_at)->format('Y-m-d') }}
                                                            </div>

                                                        </div>

                                                        <div class="row pt-1">
                                                            <div class="col mr-2">
                                                                <h6 class="">
                                                                    تاریخ انتشار:
                                                                </h6>
                                                            </div>
                                                            <div class="col">
                                                                {{ jDate($key->publish_date)->format('Y-m-d') }}
                                                            </div>
                                                        </div>
                                                        <div class="row pt-1">
                                                            <div class="col mr-2">
                                                                <h6 class="">
                                                                    تعداد اشتراک کننده:
                                                                </h6>
                                                            </div>
                                                            <div class="col">
                                                                {{ $key->participant->count() }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="p-3">
                                                <div class="alert alert-warning">
                                                    چیزی برای نمایش نیست!
                                                </div>
                                            </div>
                                        @endif

                                        <div class="mt-3">
                                            {{-- {{ $invoice->appends(['tab' => 'invoice', 'page' => $invoice->currentPage()])->links('front::components.paginate') }} --}}
                                        </div>
                                    </div>

                                    <div id="states"
                                        class="container tab-pane my-2 py-3 {{ request('tab') == 'invoice' ? 'active' : 'fade' }}">
                                        <div class="row">
                                            <div class="col-md-6 col-12 d-flex justify-content-between align-items-center">
                                                <div class="form-group d-flex align-items-center">
                                                    <label for="first_name" class="mr-2">
                                                        پست های من
                                                    </label>

                                                </div>
                                            </div>

                                        </div>
                                        @if ($participant->isNotEmpty())
                                            @php
                                                $counter = 0;
                                                $hasStates = false; // Flag to track if any states are found
                                            @endphp

                                            <div class="pc-size" data-screen="pc">

                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                #
                                                            </th>
                                                            {{-- <th>
                                                                اسم
                                                            </th> --}}
                                                            <th>
                                                                وضعیت
                                                            </th>
                                                            <th>
                                                                برو به
                                                            </th>
                                                            <th>
                                                                کپشن
                                                            </th>
                                                            <th>
                                                                تاریخ ثبت
                                                            </th>
                                                            <th>
                                                                تعداد لایک
                                                            </th>
                                                            <th>
                                                                تعداد دیسلایک
                                                            </th>
                                                            <th>
                                                                عملیات
                                                            </th>


                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        {{-- @dump($states) --}}
                                                        @foreach ($participant as $item)
                                                            @if ($item->participantPost->isNotEmpty())
                                                                @php $hasStates = true; @endphp
                                                                @foreach ($item->participantPost as $key)
                                                                    <tr>
                                                                        <td>
                                                                            {{ $counter++ }}
                                                                        </td>
                                                                        {{-- <td>
                                                                            {{ $key->name }}
                                                                        </td> --}}
                                                                        <td>
                                                                            @if ($key->state == 'pending')
                                                                                <span class="badge badge-info">
                                                                                    انتظار تأیید</span>
                                                                            @elseif ($key->state == 'not-valid')
                                                                                <span class="badge badge-danger">
                                                                                    رد شده</span>
                                                                            @else
                                                                                <span class="badge badge-success">
                                                                                    تأیید شده</span>
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if ($key->status == 'valid')
                                                                                <a
                                                                                    href="{{ url('posts/' . $key->participant->post->slug . '/?state-' . $key->id) }}">
                                                                                    {{ $key->title }}
                                                                                </a>
                                                                            @else
                                                                                <span class="badge badge-info">
                                                                                    انتظار</span>
                                                                            @endif

                                                                        </td>
                                                                        <td>
                                                                            {{ Str::limit($key->caption, 30) }}
                                                                        </td>
                                                                        <td>
                                                                            {{ jDate($key->created_at)->format('Y-m-d') }}
                                                                        </td>
                                                                        <td>
                                                                            {{ $key->like_count }}
                                                                        </td>
                                                                        <td>
                                                                            {{ $key->dislike_count }}
                                                                        </td>
                                                                        <td>
                                                                            @if ($key->state != 'valid')
                                                                                <button
                                                                                    class="btn btn-info btn-sm participant-edit-button"
                                                                                    data-action="{{ route('front.participant.stateForm', [$key->id]) }}">اصلاح
                                                                                    وضعیت</button>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        @endforeach

                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="mobile-size " data-screen="mobile">
                                                @foreach ($participant as $item)
                                                    @if ($item->participantPost->isNotEmpty())
                                                        @php $hasStates = true; @endphp
                                                        @foreach ($item->participantPost as $key)
                                                            <div class=" border rounded mb-1 pb-1">
                                                                {{-- <div class="row pt-1">
                                                                    <div class="col mr-2">
                                                                        <h6 class="">
                                                                            اسم:
                                                                        </h6>
                                                                    </div>
                                                                    <div class="col">
                                                                        <span class="">
                                                                            {{ $key->name }}
                                                                        </span>
                                                                    </div>
                                                                </div> --}}

                                                                <div class="row pt-1">
                                                                    <div class="col mr-2">
                                                                        <h6 class="">
                                                                            وضعیت: </h6>
                                                                    </div>
                                                                    <div class="col">
                                                                        @if ($key->state == 'pending')
                                                                            <span class="badge badge-info">
                                                                                انتظار تأیید</span>
                                                                        @elseif ($key->state == 'not-valid')
                                                                            <span class="badge badge-danger">
                                                                                رد شده</span>
                                                                        @else
                                                                            <span class="badge badge-success">
                                                                                تأیید شده</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="row pt-1">
                                                                    <div class="col mr-2">
                                                                        <h6 class="">
                                                                            برو به:
                                                                        </h6>
                                                                    </div>
                                                                    <div class="col">
                                                                        @if ($key->status == 'valid')
                                                                            <a
                                                                                href="{{ url('posts/' . $key->participant->post->slug . '/?state-' . $key->id) }}">
                                                                                {{ $key->title }}
                                                                            </a>
                                                                        @else
                                                                            <span class="badge badge-info">
                                                                                انتظار</span>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <div class="row pt-1">
                                                                    <div class="col mr-2">

                                                                        <h6 class="">
                                                                            کپشن:</h6>
                                                                    </div>

                                                                    <div class="col">
                                                                        {{ Str::limit($key->caption, 30) }}
                                                                    </div>

                                                                </div>

                                                                <div class="row pt-1">
                                                                    <div class="col mr-2">
                                                                        <h6 class="">
                                                                            تاریخ ثبت:
                                                                        </h6>
                                                                    </div>
                                                                    <div class="col">
                                                                        {{ jDate($key->created_at)->format('Y-m-d') }}
                                                                    </div>
                                                                </div>
                                                                <div class="row pt-1">
                                                                    <div class="col mr-2">
                                                                        <h6 class="">
                                                                            تعداد لایک:
                                                                        </h6>
                                                                    </div>
                                                                    <div class="col">
                                                                        {{ $key->like_count }}
                                                                    </div>
                                                                </div>
                                                                <div class="row pt-1">
                                                                    <div class="col mr-2">
                                                                        <h6 class="">
                                                                            تعداد دیسلایک:
                                                                        </h6>
                                                                    </div>
                                                                    <div class="col">
                                                                        {{ $key->dislike_count }}
                                                                    </div>
                                                                </div>
                                                                <div class="row pt-1">
                                                                    <div class="col mr-2">
                                                                        <h6 class="">
                                                                            عملیات:
                                                                        </h6>
                                                                    </div>
                                                                    <div class="col">
                                                                        @if ($key->state != 'valid')
                                                                            <button
                                                                                class="btn btn-info btn-sm participant-edit-button"
                                                                                data-action="{{ route('front.participant.stateForm', [$key->id]) }}">اصلاح
                                                                                پست</button>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </div>
                                            @if (!$hasStates)
                                                <div class="p-3">
                                                    <div class="alert alert-warning">
                                                        چیزی برای نمایش نیست!
                                                    </div>
                                                </div>
                                            @endif
                                        @else
                                            <div class="p-3">
                                                <div class="alert alert-warning">
                                                    چیزی برای نمایش نیست!
                                                </div>
                                            </div>
                                        @endif
                                        <div class="mt-3">
                                            {{-- {{ $invoice->appends(['tab' => 'invoice', 'page' => $invoice->currentPage()])->links('front::components.paginate') }} --}}
                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <div class="modal fade text-left" id="match-participation-modal" tabindex="-1" role="dialog"
        style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg match-participation-modal-content"
            role="document">

        </div>
    </div>
    {{-- <div class="modal fade text-left" id="state-modal" tabindex="-1" role="dialog" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg participant-stat-content"
            role="document">

        </div>
    </div> --}}

    <!-- End Content -->
@endsection
@include('back.partials.plugins', [
    'plugins' => [
        'ckeditor',
        'dropzone',
        'jquery-tagsinput',
        'jquery.validate',
        'jquery-ui',
        'jquery-ui-sortable',
    ],
])
@push('styles')
    <link rel="stylesheet" href="{{ theme_asset('css/fitting.css') }}">
    {{-- <link rel="stylesheet" href="{{ theme_asset('css/post.css') }}"> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
@endpush


@push('scripts')
    <script src='{{ asset('front/script.js') }}'></script>
    <script src="{{ theme_asset('js/pages/fitting/show.js') }}?v=51"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@endpush
