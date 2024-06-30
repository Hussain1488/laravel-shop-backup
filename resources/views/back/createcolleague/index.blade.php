 @extends('back.layouts.master')

 @section('content')
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
                                     <li class="breadcrumb-item">مدیریت
                                     </li>
                                     <li class="breadcrumb-item">ایجاد همکار
                                     </li>
                                     <li class="breadcrumb-item active">اعتبار خریدار
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
                         <h4 class="card-title">اعتبار کاربر</h4>
                     </div>
                     <div class="card-content">

                         <div class="container mt-3">

                             {{-- giving creadit to users from seller --}}

                             <form action="{{ route('admin.createcolleague.Creditstore') }}" method="POST"
                                 enctype="multipart/form-data" id="createCredit">
                                 @csrf


                                 <div class="tab-content">
                                     <div id="home" class="container tab-pane active"><br>


                                         <div class="row">
                                             <div class="col-lg-3 col-md-6 col-12 ">
                                                 <h5>
                                                     انتخاب فرد مورد نظر
                                                 </h5>
                                             </div>
                                             <div class="col-lg-3 col-md-6 col-12">
                                                 <div class="form-group">
                                                     <label>سرچ بر اساس شماره تلفن</label>
                                                     <select type="text" class="form-control user_select2 user_selection"
                                                         id="User_selected" id="" name="user_id">
                                                     </select>
                                                     @error('user_id')
                                                         <span class="text-danger">
                                                             {{ $message }}
                                                         </span>
                                                     @enderror
                                                     <div class="m-1">
                                                         <span class="user_title" id="user_title"></span>
                                                         <span class="text-success user_name" id="user_name"></span>
                                                     </div>


                                                 </div>
                                             </div>
                                         </div>
                                         <div class="row">
                                             <div class="col-lg-3 col-md-6 col-12 ">
                                                 <h5>
                                                     نوعیت تراکنش
                                                 </h5>
                                             </div>
                                             <div class="col-lg-3 col-md-6 col-12">
                                                 <div class="form-group">
                                                     <select type="text" class="form-control" id=""
                                                         id="" name="trans_type">
                                                         <option value="increase">افزایش اعتبار</option>
                                                         <option value="decrease">کاهش اعتبار</option>

                                                     </select>
                                                     @error('trans_type')
                                                         <span class="text-danger">
                                                             {{ $message }}
                                                         </span>
                                                     @enderror
                                                 </div>
                                             </div>
                                         </div>

                                         <div class="row mt-1">

                                             <div class="col-lg-3 col-md-6 col-12">

                                                 <h5 for="inventory" class="mr-2">
                                                     موجودی نقدی
                                                 </h5>

                                             </div>
                                             <div class="col-lg-3 col-md-6 col-12 ">
                                                 <div class="d-flex align-items-center">
                                                     <input readonly type="text" placeholder="100,000"
                                                         class="form-control moneyInput" id="inventory" name="inventory"
                                                         style="margin-left: 4px;">
                                                     <span>ریال</span>
                                                 </div>
                                                 @error('inventory')
                                                     <span class="text-danger">
                                                         {{ $message }}
                                                     </span>
                                                 @enderror
                                             </div>
                                         </div>
                                         <div class="row mt-1">

                                             <div class="col-lg-3 col-md-6 col-12 ">

                                                 <h5 for="purchasecredit" class="mr-2">
                                                     مقدار اعتبار خرید اقساطی
                                                 </h5>

                                             </div>
                                             <div class="col-lg-3 col-md-6 col-12 ">
                                                 <div class="d-flex align-items-center">
                                                     <input type="text" placeholder="100,000"
                                                         class="form-control moneyInput" id="purchase_credit"
                                                         name="purchasecredit" style="margin-left: 4px;">
                                                     <span>ریال</span>
                                                 </div>
                                                 @error('purchasecredit')
                                                     <span class="text-danger">
                                                         {{ $message }}
                                                     </span>
                                                 @enderror
                                             </div>
                                         </div>
                                         <div class="row mt-1">
                                             <div class="col-lg-3 col-md-6 col-12 ">
                                                 <h5 for="documents" class="mr-2">
                                                     آپلود مدارک
                                                 </h5>
                                             </div>
                                             <div class="col-lg-3 col-md-6 col-12 ">
                                                 <div class="d-flex align-items-center">
                                                     <input multiple type="file" class="form-control imageInput"
                                                         name="documents[]">
                                                     @error('documents')
                                                         <span class="text-danger">
                                                             {{ $message }}
                                                         </span>
                                                     @enderror
                                                 </div>
                                                 <div class="imgContainer"></div>
                                             </div>
                                         </div>


                                         <div class="row mt-1">
                                             <div class="col-lg-3 col-md-6 col-12 ">
                                                 <h5>
                                                     تاریخ پایان اعتبار
                                                 </h5>
                                             </div>
                                             <div class="col-lg-3 col-md-6 col-12">
                                                 <div class="form-group">
                                                     <input type="text" class="form-control persian-date-picker"
                                                         name="enddate">
                                                     @error('enddate')
                                                         <span class="text-danger">
                                                             {{ $message }}
                                                         </span>
                                                     @enderror
                                                 </div>
                                             </div>
                                         </div>

                                         <div class="row ">
                                             <div
                                                 class="col-lg-3 col-md-6 col-6 d-flex align-items-baseline justify-content-center">
                                                 <input type="button" id="summit_button1"
                                                     class="btn btn-primary my-1"value=" تأیید تغییرات" />
                                             </div>
                                             <div
                                                 class="col-lg-3 col-md-6 col-6 d-flex align-items-baseline justify-content-center">
                                                 <a href="" class="btn btn-danger my-1">انصراف </a>
                                             </div>
                                         </div>
                                     </div>
                                 </div>

                             </form>

                         </div>
                 </section>

             </div>
         </div>
     </div>
 @endsection
 @include('back.partials.plugins', [
     'plugins' => ['persian-datepicker'],
 ])

 @push('scripts')
     <script>
         var url = '{{ route('admin.user.searchUser') }}';
     </script>
     <script src="{{ asset('back/assets/js/scripts.js') }}?v=51"></script>
     <script src="{{ asset('back/assets/js/pages/users/all.js') }}"></script>
     <script src="{{ asset('back/assets/js/pages/cooperationSales/create.js') }}"></script>
 @endpush
