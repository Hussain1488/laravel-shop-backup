<?php

return [

    'api_url' => 'http://laravel-shop.ir/api/v1',

    'admin_route_prefix' => env('ADMIN_ROUTE_PREFIX'),

    'current_theme' => env('CURRENT_THEME', 'DefaultTheme'),

    'permissions' => [

        'users' => [
            'title'  => 'مدیریت کاربران',
            'values' => [
                'index'          => 'لیست کاربران',
                'view'           => 'مشاهده کاربر',
                'create'         => 'ایجاد کاربر',
                'update'         => 'ویرایش کاربر',
                'delete'         => 'حذف کاربر',
                'export'         => 'خروجی گرفتن',
                'wallet'         => 'مدیریت کیف پول',
            ]
        ],

        'cooperationsales' => [
            'title' =>  'فروشگاه',
            'values' => [
                'index' => 'لیست فروش',
                'create' => 'ایجاد قسط',
                'clearing' => 'درخواست تسویه حساب',
                // 'changeStatus' => 'تغیر وضعیت قسط',
                'store' => 'ذخیره قسط',
                // 'clearing.store' => 'ثبت درخواست تسویه',
                'mainWallet' => 'کیف پول اصلی',
                'payRequestWallet' => 'تراکنش های درخواست واریز',
                'paidSales' => 'تراکنش های فورشات پرداخت شده',
                'creditTrans' => 'تراکنش های اعتبار فروشگاه',
                'more' => 'اطلاعات و گالری مجموعه',
            ]
        ],

        'installmentreports' => [
            'title' =>  'گزارش اقساط',
            'values' => [
                'index' => 'لیست تمامی اقساط',
                'usrestatus.refuse' => 'انصراف از خرید قسط',
                'payRequestList' => 'لیست درخواست تسویه',
                'RequestPaymentStore' => 'ثبت تسویه درخواست تسویه',
                'banklist' => 'لیست بانک ها',
                'storebank' => 'ذخیره بانک جدید',
                'paidList' => 'لیست پرداخت ها',
                'createinternalaccount' => 'ایجاد حساب داخلی',

            ]
        ],
        'createcolleague' => [
            'title' =>  'ایجاد همکار',
            'values' => [
                'index' => 'اعتبار دهی به خریدار',
                'create' => 'ایجاد فروشگاه',
                'shopList' => 'لیست فروشگاه ها',
                'shopedit' => 'ویرایش فروشگاه',
                'shopShow' => 'فروشات فروشگاه',
                'show' => 'جزئیات فروشگاه',
                'reaccreditation.index' => 'صفحه افزایش اعتبار فروشگاه ',
                'createdocument' => 'ایجاد سند مالی',
                'more' => 'اطلاعات و گالری فروشگاه',
            ]
        ],

        'operatoractivity' => [
            'title' => 'اپراتور ها',
            'values' => [
                'index' => 'لیست اپراتور ها',
                'show' => 'فعالیت اپراتور',
            ],
        ],
        'cornjob' => [
            'title' => 'کرن جاب',
            'values' => [
                'index' => 'گزارش کرن جاب',
                'setting' => 'تنظیمات کرن جاب',
            ],
        ],

        'posts' => [
            'title'  => 'مدیریت نوشته ها',
            'values' => [
                'index'      => 'لیست نوشته ها',
                'create'     => 'ایجاد نوشته',
                'update'     => 'ویرایش نوشته',
                'delete'     => 'حذف نوشته',
                'category'   => 'مدیریت دسته بندی ها',
            ]
        ],


        'products' => [
            'title'  => 'مدیریت محصولات',
            'values' => [
                'index'         => 'لیست محصولات',
                'create'        => 'ایجاد محصول',
                'update'        => 'ویرایش محصول',
                'delete'        => 'حذف محصول',
                'export'        => 'خروجی گرفتن',
                'category'      => 'مدیریت دسته بندی ها',
                'spectypes'     => 'مدیریت نوع مشخصات',
                'sizetypes'     => 'مدیریت سایزبندی ها',
                'stock-notify'  => 'مدیریت لیست اطلاع از موجودی',
                'brands'        => 'مدیریت برندها',
                'prices'        => 'قیمت ها',
            ]
        ],

        'discounts' => [
            'title'  => 'مدیریت تخفیف ها',
            'values' => [
                'index'             => 'لیست تخفیف ها',
                'create'            => 'ایجاد تخفیف',
                'update'            => 'ویرایش تخفیف',
                'delete'            => 'حذف تخفیف',
            ]
        ],

        'attributes' => [
            'title'  => 'مدیریت ویژگی ها',
            'values' => [
                'groups.index'    => 'لیست گروه ویژگی ها',
                'groups.show'     => 'مشاهده گروه ویژگی',
                'groups.create'   => 'ایجاد گروه ویژگی',
                'groups.update'   => 'ویرایش گروه ویژگی',
                'groups.delete'   => 'حذف گروه ویژگی',

                'index'           => 'لیست ویژگی ها',
                'create'          => 'ایجاد ویژگی',
                'update'          => 'ویرایش ویژگی',
                'delete'          => 'حذف ویژگی',
            ]
        ],

        'filters' => [
            'title'  => 'مدیریت فیلترها',
            'values' => [
                'index'    => 'لیست فیلترها',
                'create'   => 'ایجاد فیلتر',
                'update'   => 'ویرایش فیلتر',
                'delete'   => 'حذف فیلتر',
            ]
        ],

        'orders' => [
            'title'  => 'مدیریت سفارشات',
            'values' => [
                'index'             => 'لیست سفارشات',
                'create'            => 'افزودن سفارش جدید',
                'view'              => 'مشاهده سفارش',
                'update'            => 'ویرایش سفارش',
                'delete'            => 'حذف سفارش',
                'export'            => 'خروجی گرفتن',
            ]
        ],

        'carriers' => [
            'title'  => 'مدیریت حمل و نقل',
            'values' => [
                'provinces.index'             => 'لیست استان ها',
                'provinces.update'            => 'ویرایش استان',
                'provinces.delete'            => 'حذف استان',
                'provinces.create'            => 'ایجاد استان',
                'provinces.show'              => 'مشاهده استان',
                'cities.update'               => 'ویرایش شهر',
                'cities.delete'               => 'حذف شهر',
                'cities.create'               => 'ایجاد شهر',
            ]
        ],

        'sliders' => [
            'title'  => 'مدیریت اسلایدرها',
            'values' => [
                'index'             => 'لیست اسلایدرها',
                'create'            => 'ایجاد اسلایدر',
                'update'            => 'ویرایش اسلایدر',
                'delete'            => 'حذف اسلایدر',
            ]
        ],

        'banners' => [
            'title'  => 'مدیریت بنرها',
            'values' => [
                'index'             => 'لیست بنرها',
                'create'            => 'ایجاد بنر',
                'update'            => 'ویرایش بنر',
                'delete'            => 'حذف بنر',
            ]
        ],

        'links' => [
            'title'  => 'مدیریت لینک های فوتر',
            'values' => [
                'index'             => 'لیست لینک ها',
                'create'            => 'ایجاد لینک',
                'update'            => 'ویرایش لینک',
                'delete'            => 'حذف لینک',
                'groups'            => 'مدیریت گروه ها'
            ]
        ],

        'backups' => [
            'title'  => 'مدیریت بکاپ ها',
            'values' => [
                'index'             => 'لیست بکاپ ها',
                'create'            => 'ایجاد بکاپ',
                'download'          => 'دانلود بکاپ',
                'delete'            => 'حذف بکاپ',
            ]
        ],

        'apikeys' => [
            'title'  => 'مدیریت کلیدهای وب سرویس',
            'values' => [
                'index'             => 'لیست کلیدهای وب سرویس',
                'create'            => 'ایجاد کلید وب سرویس',
                'update'            => 'ویرایش کلید وب سرویس',
                'delete'            => 'حذف کلید وب سرویس',
            ]
        ],

        'pages' => [
            'title'  => 'مدیریت صفحات',
            'values' => [
                'index'             => 'لیست صفحات',
                'create'            => 'ایجاد صفحه',
                'update'            => 'ویرایش صفحه',
                'delete'            => 'حذف صفحه',
            ]
        ],

        'roles' => [
            'title'  => 'مدیریت مقام ها',
            'values' => [
                'index'             => 'لیست مقام ها',
                'create'            => 'ایجاد مقام',
                'update'            => 'ویرایش مقام',
                'delete'            => 'حذف مقام',
            ]
        ],

        'statistics' => [
            'title'  => 'گزارشات',
            'values' => [
                'orders'              => 'سفارشات',
                'users'               => 'کاربران',
                'views'               => 'بازدیدها',
                'viewsList'           => 'لیست بازدیدها',
                'viewers'             => 'بازدید کنندگان امروز',
                'sms'                 => 'لاگ پیامک های ارسالی',
            ]
        ],

        'themes' => [
            'title'  => 'مدیریت قالب ها',
            'values' => [
                'index'             => 'لیست قالب ها',
                'create'            => 'افزودن قالب',
                'update'            => 'تغییر قالب',
                'delete'            => 'حذف قالب',
                'settings'          => 'تنظیمات قالب',
                'widgets'           => 'مدیریت صفحه اصلی'
            ]
        ],

        'file-manager'    => 'مدیریت فایل ها',

        'tickets' => [
            'title'  => 'مدیریت تیکت ها',
            'values' => [
                'index'             => 'لیست تیکت ها',
                'show'              => 'مشاهده تیکت',
                'create'            => 'ایجاد تیکت',
                'update'            => 'ویرایش تیکت',
                'delete'            => 'حذف تیکت',
            ]
        ],

        'menus' => [
            'title'  => 'مدیریت منو ها',
            'values' => [
                'index'             => 'لیست منو ها',
                'create'            => 'ایجاد منو',
                'update'            => 'ویرایش منو',
                'delete'            => 'حذف منو',
            ]
        ],

        'payments' => [
            'title'  => 'مدیریت پرداخت',
            'values' => [
                'transactions.index'         => 'لیست تراکنش ها',
                'transactions.view'          => 'مشاهده تراکنش',
                'transactions.delete'        => 'حذف تراکنش',
                'currencies'                 => 'مدیریت ارزها',
                'wallet-histories.index'     => 'تاریخچه کیف پول'
            ]
        ],

        'contacts' => [
            'title'  => 'مدیریت تماس با ما',
            'values' => [
                'index'             => 'لیست تماس با ما',
                'view'              => 'مشاهده تماس با ما',
                'delete'            => 'حذف تماس با ما',
            ]
        ],

        'comments' => [
            'title'  => 'مدیریت نظرات',
            'values' => [
                'index'             => 'لیست نظرات',
                'view'              => 'مشاهده نظر',
                'update'             => 'ویرایش نظر',
                'delete'            => 'حذف نظر',
            ]
        ],

        'settings' => [
            'title'  => 'تنظیمات',
            'values' => [
                'information'        => 'اطلاعات سایت',
                'socials'            => 'شبکه های اجتماعی',
                'gateway'            => 'درگاه های پرداخت',
                'others'             => 'تنظیمات دیگر',
                'sms'                => 'تنظیمات پیامک',
            ]
        ],

        'lottery' => [
            'title'         => 'قرعه کشی',
            'values'        => [
                'index'     => 'گزارش قرعه کشی',
                'dailyCode'     => 'کد روزانه',
                'invoicesIndex'     => 'فاکتور های فروش',
                'winners'     => 'لیست برنده ها',
            ]
        ],


    ],

    'static_menus' => [
        'posts' => [
            'title' => 'وبلاگ'
        ],
        'products' => [
            'title' => 'محصولات',
        ]
    ],

    'supported_gateways' => [
        'behpardakht' => 'به پرداخت ملت',
        'payir'       => 'pay.ir',
        'zarinpal'    => 'زرین پال',
        'payping'     => 'پی پینگ',
        'saman'       => 'سامان',
        'sepehr'      => 'بانک صادرات',
        'idpay'       => 'idpay',
        'sadad'       => 'بانک ملی',
        'zibal'       => 'زیبال',
    ],

    'video-helpes' => [
        'installing' => [
            'title'            => 'آموزش نصب کردن اسکریپت',
            'link'             => 'https://www.aparat.com/v/2ZzrD',
            'type'             => 'creator'
        ],
        'sms-config' => [
            'title'            => 'آموزش ثبت نام و تنظیم پنل پیامک',
            'link'             => 'https://aparat.com/v/wbQ9D',
            'type'             => 'admin'
        ],
        'updater' => [
            'title'            => 'آموزش بروزرسانی اسکریپت',
            'link'             => 'https://aparat.com/v/CfgAF',
            'type'             => 'creator'
        ],
        'cronjob' => [
            'title'            => 'آموزش تنظیم کرون جاب',
            'link'             => 'https://aparat.com/v/BpAN1',
            'type'             => 'creator'
        ],
        'users' => [
            'title'            => 'آموزش بخش کاربران',
            'link'             => 'https://aparat.com/v/pWeUw',
            'type'             => 'admin'
        ],
        'products-create' => [
            'title'            => 'آموزش ایجاد محصول',
            'link'             => 'https://aparat.com/v/Re0US',
            'type'             => 'admin'
        ],
    ],

    'non_language_options' => [
        'multi_language_enabled',
        'debugbar_enabled',
        'enable_help_videos',
        'user_register_gift_credit',
    ]
];
