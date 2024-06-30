<?php

use App\Http\Controllers\Back\CornjobController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Back\SmsController;
use App\Http\Controllers\Back\CityController;

use App\Http\Controllers\Back\LinkController;
use App\Http\Controllers\Back\installmentpurchaseController;
use App\Http\Controllers\Back\CreateColleagueController;
use App\Http\Controllers\Back\InstallmentReportsController;
use App\Http\Controllers\Back\OperatorActivityController;


use App\Http\Controllers\Back\MainController;
use App\Http\Controllers\Back\MenuController;
use App\Http\Controllers\Back\PageController;
use App\Http\Controllers\Back\PostController;
use App\Http\Controllers\Back\RoleController;
use App\Http\Controllers\Back\UserController;
use App\Http\Controllers\Back\BrandController;
use App\Http\Controllers\Back\OrderController;
use App\Http\Controllers\Back\ThemeController;
use App\Http\Controllers\Back\ApikeyController;
use App\Http\Controllers\Back\BackupController;
use App\Http\Controllers\Back\BannerController;
use App\Http\Controllers\Back\FilterController;
use App\Http\Controllers\Back\ReviewController;
use App\Http\Controllers\Back\SliderController;
use App\Http\Controllers\Back\TariffController;
use App\Http\Controllers\Back\TicketController;
use App\Http\Controllers\Back\WalletController;
use App\Http\Controllers\Back\WidgetController;
use App\Http\Controllers\Back\CarrierController;
use App\Http\Controllers\Back\CommentController;
use App\Http\Controllers\Back\ContactController;
use App\Http\Controllers\Back\InstallController;
use App\Http\Controllers\Back\ProductController;
use App\Http\Controllers\Back\SettingController;
use App\Http\Controllers\Back\CategoryController;
use App\Http\Controllers\Back\CurrencyController;
use App\Http\Controllers\Back\DiscountController;
use App\Http\Controllers\Back\ProvinceController;
use App\Http\Controllers\Back\SizeTypeController;
use App\Http\Controllers\Back\SpecTypeController;
use App\Http\Controllers\Back\AttributeController;
use App\Http\Controllers\Back\DeveloperController;
use App\Http\Controllers\Back\PermissionController;
use App\Http\Controllers\Back\StatisticsController;
use App\Http\Controllers\Back\StockNotifyController;
use App\Http\Controllers\Back\TransactionController;
use App\Http\Controllers\Back\CooperationSalesController;
use App\Http\Controllers\PushSubscriptionController;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;
use App\Http\Controllers\Back\WalletHistoryController;
use App\Http\Controllers\Back\AttributeGroupController;
use App\Http\Controllers\Back\EmployeeController;
use App\Http\Controllers\Back\ParticipantController;
use App\Http\Controllers\Back\ServicesController;
use App\Http\Controllers\Back\ShopReviewController;
use App\Http\Controllers\lotteryController;
use App\Models\Participant;
use Melipayamak\MelipayamakApi;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__ . '/auth.php';




Route::get('province/get-cities', [ProvinceController::class, 'getCities'])->name('provinces.get-cities');

Route::group(['as' => 'admin.', 'prefix' => 'admin/' . admin_route_prefix(), 'middleware' => ['guest']], function () {
    Route::get('login', [MainController::class, 'login'])->middleware(['CheckUserExists'])->name('login');
    Route::get('register', [InstallController::class, 'showRegisterForm'])->name('register')->middleware(['CheckUserNotExists']);
    Route::post('register', [InstallController::class, 'register'])->middleware(['CheckUserNotExists']);
});

// ------------------ Admin Part Routes
Route::group(['as' => 'admin.', 'prefix' => 'admin/' . admin_route_prefix(), 'middleware' => ['auth', 'Admin', 'verified', 'CheckPasswordChange']], function () {

    // ------------------ MainController
    Route::get('/', [MainController::class, 'index'])->name('dashboard');
    Route::get('get-tags', [MainController::class, 'get_tags'])->name('get-tags');
    Route::get('get-labels', [MainController::class, 'getLabels'])->name('get-labels');

    Route::get('notifications', [MainController::class, 'notifications'])->name('notifications');

    Route::get('file-manager', [MainController::class, 'fileManager'])->name('file-manager');
    Route::get('file-manager-iframe', [MainController::class, 'fileManagerIframe'])->name('file-manager-iframe');

    // ------------------ backups
    Route::get('backups', [BackupController::class, 'index'])->name('backups.index');
    Route::post('backups/create', [BackupController::class, 'create'])->name('backups.create');
    Route::get('backups/{backup}/download', [BackupController::class, 'download'])->name('backups.download');
    Route::delete('backups/{backup}', [BackupController::class, 'destroy'])->name('backups.destroy');


    // ------------------ cooperation Sales
    Route::get('cooperationsales/index', [CooperationSalesController::class, 'index'])->name('cooperationsales.index');
    Route::get('cooperationsales/create', [CooperationSalesController::class, 'create'])->name('cooperationsales.create');
    Route::get('cooperationsales/Income', [CooperationSalesController::class, 'Income'])->name('cooperationsales.Income');
    Route::get('cooperationsales/clearing', [CooperationSalesController::class, 'clearing'])->name('cooperationsales.clearing');
    Route::get('cooperationsales/changeStatus/{id}', [CooperationSalesController::class, 'changeStatus'])->name('cooperationsales.changeStatus');
    Route::post('cooperationsales/store', [CooperationSalesController::class, 'store'])->name('cooperationsales.store');
    Route::post('cooperationsales/clearing', [CooperationSalesController::class, 'clearingStore'])->name('cooperationsales.clearing.store');
    Route::get('cooperationsales/payrequest/{store_id}/{installments_id}', [CooperationSalesController::class, 'PayRequest'])->name('installments.payrequest');
    Route::get('cooperationsales/mainWallet/{store_id}', [CooperationSalesController::class, 'mainWallet'])->name('cooperationsales.mainWallet');
    Route::get('cooperationsales/payRequestWallet/{store_id}', [CooperationSalesController::class, 'payRequestWallet'])->name('cooperationsales.payRequestWallet');
    Route::get('cooperationsales/paidSales/{store_id}', [CooperationSalesController::class, 'paidSales'])->name('cooperationsales.paidSales');
    Route::get('cooperationsales/creditTrans/{store_id}', [CooperationSalesController::class, 'creditTrans'])->name('cooperationsales.creditTrans');
    Route::get('cooperationsales/transaction/details/{id}', [CooperationSalesController::class, 'transactionDetails'])->name('cooperationsales.transaction.details');
    Route::get('shopmore/more/{id}', [CooperationSalesController::class, 'more'])->name('shop_more.more');
    Route::put('shopmore/moreStore/{id}', [CooperationSalesController::class, 'moreStore'])->name('shop_more.moreStore');

    // ---------- more
    Route::delete('shop_more/{id}', [ShopReviewController::class, 'delete'])->name('shop_more.delete');
    Route::resource('shop_more', ShopReviewController::class);

    // Route::get('shopmore/deleteReview/{id}', [ShopReviewController::class, 'deleteReview'])->name('shop_more.deleteReview');
    // Route::get('shopmore/reviews/{review}', [ShopReviewController::class, 'reviews'])->name('shop_more.reveiws');


    // ------------ employee

    Route::resource('employee', EmployeeController::class)->except('show');
    Route::get('employee/reviews', [EmployeeController::class, 'reviews'])->name('employee.review');
    Route::get('employee/{id}/show', [EmployeeController::class, 'show'])->name('employee.show');
    Route::post('employee/{id}/delete', [EmployeeController::class, 'delete'])->name('employee.delete');
    Route::post('employee/datatable', [EmployeeController::class, 'datatable'])->name('employee.datatable');

    Route::get('employee/{id}/deletePhoto', [EmployeeController::class, 'deletePhoto'])->name('employee.deletePhoto');

    // Services

    Route::resource('services', ServicesController::class)->except('show');;
    Route::get('service/reviews', [ServicesController::class, 'reviews'])->name('services.review');
    Route::get('service/{id}/show', [ServicesController::class, 'show'])->name('services.show');
    Route::post('service/{id}/delete', [ServicesController::class, 'delete'])->name('services.delete');
    Route::get('services/{id}/deletePhoto', [ServicesController::class, 'deletePhoto'])->name('services.deletePhoto');
    Route::post('services/datatable', [ServicesController::class, 'datatable'])->name('services.datatable');


    // ------------------ OperatorActivity
    Route::get('operatoractivity/index', [OperatorActivityController::class, 'index'])->name('operatoractivity.index');
    Route::get('operatoractivity/show{id}', [OperatorActivityController::class, 'show'])->name('operatoractivity.show');
    Route::post('/get-operator-activity-data', [OperatorActivityController::class, 'getOperatorActivityData'])->name('get.operator.activity.data');
    Route::get('operatoractivity/details/{id}', [OperatorActivityController::class, 'details'])->name('operatoractivity.details');
    Route::post('operatoractivity/search', [OperatorActivityController::class, 'search'])->name('operatoractivity.search');
    Route::post('operatoractivity/filter', [OperatorActivityController::class, 'filter'])->name('operatoractivity.filter');

    // ------------------ installmentpurchase
    Route::get('installmentpurchase/index', [installmentpurchaseController::class, 'index'])->name('installmentpurchase.index');

    // ------------------ Create colleague
    Route::get('createcolleague/index', [CreateColleagueController::class, 'index'])->name('createcolleague.index');
    Route::get('createcolleague/shopList', [CreateColleagueController::class, 'shopList'])->name('createcolleague.shopList');
    Route::post('createcolleague/shopListFilter', [CreateColleagueController::class, 'shopListFilter'])->name('createcolleague.shopListFilter');
    Route::get('createcolleague/create', [CreateColleagueController::class, 'create'])->name('createcolleague.create');
    Route::get('createcolleague/shopedit/{id}', [CreateColleagueController::class, 'shopedit'])->name('createcolleague.shopedit');
    Route::get('createcolleague/show/{id}', [CreateColleagueController::class, 'show'])->name('createcolleague.show');
    Route::get('createcolleague/fileDownload/{id}', [CreateColleagueController::class, 'fileDownload'])->name('createcolleague.fileDownload');
    Route::put('createcolleague/shopUpdate/{id}', [CreateColleagueController::class, 'shopUpdate'])->name('createcolleague.shopUpdate');
    Route::post('createcolleague/store', [CreateColleagueController::class, 'store'])->name('createcolleague.store');
    Route::post('createcolleague/Creditstore', [CreateColleagueController::class, 'colleagueCreditStore'])->name('createcolleague.Creditstore');
    Route::get('createcolleague/createcreditoperator', [CreateColleagueController::class, 'createcreditoperator'])->name('createcolleague.createcreditoperator');
    Route::post('createcolleague/storecreditoperator', [CreateColleagueController::class, 'storecreditoperator'])->name('createcolleague.storecreditoperator');
    Route::get('createcolleague/createdocument', [CreateColleagueController::class, 'createdocument'])->name('createcolleague.createdocument');
    Route::get('createcolleague/resalecredit', [CreateColleagueController::class, 'resalecredit'])->name('createcolleague.resalecredit');
    Route::get('createcolleague/reaccreditation', [CreateColleagueController::class, 'reaccreditationIndex'])->name('createcolleague.reaccreditation.index');
    Route::post('createcolleague/reaccreditationStore', [CreateColleagueController::class, 'reaccreditationStore'])->name('createcolleague.reaccreditation.store');
    Route::post('createcolleague/createDocumentStore', [CreateColleagueController::class, 'createDocumentStore'])->name('createcolleague.Documentstore');
    Route::get('createcolleague/accountList', [CreateColleagueController::class, 'accountList'])->name('createcolleague.accountList');

    // ------------------ installment Reports
    Route::get('installmentreports/index', [InstallmentReportsController::class, 'index'])->name('installmentreports.index');
    Route::get('installmentreports/payRequestList', [InstallmentReportsController::class, 'payRequestList'])->name('installmentreports.payRequestList');
    Route::get('installmentreports/payReqDetails/{id}', [InstallmentReportsController::class, 'payReqDetails'])->name('installmentreports.payReqDetails');
    Route::get('installmentreports/reqDocDownload/{id}', [InstallmentReportsController::class, 'reqDocDownload'])->name('installmentreports.reqDocDownload');
    Route::get('installmentreports/transactionFilter/{id}', [InstallmentReportsController::class, 'transactionFilter'])->name('installmentreports.transactionFilter');
    Route::get('installmentreports/transactionDetails/{id}', [InstallmentReportsController::class, 'transactionDetails'])->name('installmentreports.transactionDetails');
    Route::post('installmentreports/transactionFilter/data', [InstallmentReportsController::class, 'transactionFilterData'])->name('installmentreports.transactionFilter.data');
    Route::post('installmentreports/RequestPaymentStore', [InstallmentReportsController::class, 'RequestPaymentStore'])->name('installmentreports.RequestPaymentStore');
    Route::get('installmentreports/bankList', [InstallmentReportsController::class, 'bankList'])->name('installmentreports.banklist');
    Route::get('installmentreports/createinternalaccount', [InstallmentReportsController::class, 'createinternalaccount'])->name('installmentreports.createinternalaccount');
    Route::post('installmentreports/storebank', [InstallmentReportsController::class, 'storebank'])->name('installmentreports.storebank');
    Route::get('installmentreports/paidList', [InstallmentReportsController::class, 'paidList'])->name('installmentreports.paidList');

    Route::get('installmentreports/filter', [InstallmentReportsController::class, 'filter'])->name('installments.filter');
    Route::get('installmentreports/filter1', [InstallmentReportsController::class, 'filter1'])->name('installments.filter1');
    Route::get('installmentreports/filter2', [InstallmentReportsController::class, 'filter2'])->name('installments.filter2');
    Route::get('installmentreports/banktransaction', [InstallmentReportsController::class, 'banktransaction'])->name('installments.banktransaction');
    Route::get('installments/refuse/{id}', [InstallmentReportsController::class, 'refuse'])->name('installments.usrestatus.refuse');
    Route::get('installments/shop_installments/{id}/{slug}', [InstallmentReportsController::class, 'show_shop_installments'])->name('installments.shop.installments');
    Route::get('installments/shop_installments/filter', [InstallmentReportsController::class, 'show_shop_installments_filter'])->name('installments.shop.installments.filter');
    Route::get('installments/shop_installments_number/filter', [InstallmentReportsController::class, 'show_shop_installments_filter_name'])->name('installments.shop.installments.filter_name');



    // cornjob routs:

    Route::get('cornjob/index', [CornjobController::class, 'index'])->name('cornjob.index');
    Route::get('cornjob/setting', [CornjobController::class, 'create'])->name('cornjob.setting');
    Route::post('cornjob/store', [CornjobController::class, 'store'])->name('cornjob.store');


    // lottery routes:

    Route::get('lottery/index', [lotteryController::class, 'index'])->name('lottery.index');
    Route::get('lottery/dailyCode', [lotteryController::class, 'dailyCode'])->name('lottery.dailyCodes');
    Route::get('lottery/generate-daily-code', [lotteryController::class, 'generateCode'])->name('lottery.generateCode');
    Route::post('lottery/dailyCodeDatatable', [lotteryController::class, 'dailyCodeDatatable'])->name('lottery.dailyCodeDatatable');
    Route::get('lottery/dailyCodePrint', [lotteryController::class, 'dailyCodePrint'])->name('lottery.dailyCodePrint');

    Route::get('lottery/lotteryCodeNumbers', [lotteryController::class, 'lotteryCodeNumbers'])->name('lottery.lotteryCodeNumbers');

    Route::get('lottery/dailyCodeTest', [lotteryController::class, 'dailyCodeTest'])->name('lottery.dailyCodeTest');

    Route::post('lottery/codeWonState', [lotteryController::class, 'codeWonState'])->name('lottery.codeWonState');
    Route::post('lottery/CodeState', [lotteryController::class, 'CodeState'])->name('lottery.CodeState');

    Route::get('lottery/winners', [lotteryController::class, 'winners'])->name('lottery.winners');
    Route::get('lottery/winnerData', [lotteryController::class, 'winnerData'])->name('lottery.winnerData');

    Route::get('lottery/invoicesIndex', [lotteryController::class, 'invoicesIndex'])->name('lottery.invoicesIndex');
    Route::post('lottery/invoicesDatatable', [lotteryController::class, 'invoicesDatatable'])->name('lottery.invoicesDatatable');
    Route::get('lottery/invoiceRejection/{id?}', [lotteryController::class, 'invoiceRejection'])->name('lottery.invoiceRejection');
    Route::post('lottery/invoiceValidation/{id?}', [lotteryController::class, 'invoiceValidation'])->name('lottery.invoiceValidation');




    // ------------------ users
    Route::resource('users', UserController::class);
    Route::post('users/api/index', [UserController::class, 'apiIndex'])->name('users.apiIndex');
    Route::delete('users/api/multipleDestroy', [UserController::class, 'multipleDestroy'])->name('users.multipleDestroy');
    Route::get('users/export/create', [UserController::class, 'export'])->name('users.export');
    Route::get('users/{user}/views', [UserController::class, 'views'])->name('users.views');
    Route::get('user/profile', [UserController::class, 'showProfile'])->name('user.profile.show');
    Route::put('user/profile', [UserController::class, 'updateProfile'])->name('user.profile.update');
    Route::get('/searchUser', [UserController::class, 'searchUser'])->name('user.searchUser');
    Route::get('/sellerlist', [UserController::class, 'sellerlist'])->name('user.sellerlist');

    // ------------------ wallets
    Route::resource('wallets', WalletController::class)->only(['show', 'index']);
    Route::get('wallets/histories/{history}', [WalletController::class, 'history'])->name('wallets.history');
    Route::get('wallets/{wallet}/create', [WalletController::class, 'create'])->name('wallets.create');
    Route::post('wallets/{wallet}', [WalletController::class, 'store'])->name('wallets.store');

    // ------------------ products
    Route::resource('products', ProductController::class)->except('show');
    Route::post('products/api/index', [ProductController::class, 'apiIndex'])->name('products.apiIndex');
    Route::delete('products/api/multipleDestroy', [ProductController::class, 'multipleDestroy'])->name('products.multipleDestroy');
    Route::post('products/image-store', [ProductController::class, 'image_store']);
    Route::post('products/image-delete', [ProductController::class, 'image_delete']);
    Route::get('product/categories', [ProductController::class, 'categories'])->name('products.categories.index');
    Route::post('product/slug', [ProductController::class, 'generate_slug']);
    Route::post('product/sync', [ProductController::class, 'categories_sync'])->name('categories_sync');
    Route::post('product/get-all-product', [ProductController::class, 'products_sync'])->name('products_sync');
    Route::post('product/get-all-product-stock', [ProductController::class, 'stock_sync'])->name('stock_sync');
    Route::post('/update-parent-id', [ProductController::class, 'updateParentId'])->name('updateParentId');

    Route::get('products/export/create', [ProductController::class, 'export'])->name('products.export');

    Route::get('product/prices', [ProductController::class, 'indexPrices'])->name('product.prices.index');
    Route::put('product/prices', [ProductController::class, 'updatePrices'])->name('product.prices.update');

    // ------------------ discounts
    Route::resource('discounts', DiscountController::class)->except(['show']);

    // ------------------ provinces
    Route::resource('provinces', ProvinceController::class);
    Route::post('provinces/api/index', [ProvinceController::class, 'apiIndex'])->name('provinces.apiIndex');
    Route::delete('provinces/api/multipleDestroy', [ProvinceController::class, 'multipleDestroy'])->name('provinces.multipleDestroy');
    Route::post('provinces/api/sort', [ProvinceController::class, 'sort'])->name('provinces.sort');

    // ------------------ cities
    Route::resource('cities', CityController::class)->except(['index']);
    Route::post('cities/api/{province}/index', [CityController::class, 'apiIndex'])->name('cities.apiIndex');
    Route::delete('cities/api/multipleDestroy', [CityController::class, 'multipleDestroy'])->name('cities.multipleDestroy');
    Route::post('cities/api/sort', [CityController::class, 'sort'])->name('cities.sort');

    // ------------------ brands
    Route::resource('brands', BrandController::class)->except('show');
    Route::get('brands/ajax/get', [BrandController::class, 'ajax_get']);

    // ------------------ filters
    Route::resource('filters', FilterController::class)->except('show');

    // ------------------ attributeGroups
    Route::resource('attributeGroups', AttributeGroupController::class);
    Route::get('attributeGroups/{attributeGroup}/attributes', [AttributeGroupController::class, 'attributesIndex'])->name('attributes.index');
    Route::post('attributeGroup/sort', [AttributeGroupController::class, 'sort']);

    // ------------------ attributes
    Route::resource('attributes', AttributeController::class)->except(['index', 'show']);

    // ------------------ spec types
    Route::get('spectypes/spec-type-data', [SpecTypeController::class, 'getData'])->name('spectypes.getdata');
    Route::get('spectypes/ajax/get', [SpecTypeController::class, 'ajax_get']);
    Route::resource('spectypes', SpecTypeController::class)->except(['show', 'create']);

    // ------------------ size types
    Route::resource('sizetypes', SizeTypeController::class);
    Route::get('sizetypes/{sizetype}/values', [SizeTypeController::class, 'productsValues'])->name('sizetypes.editValues');
    Route::put('sizetypes/{sizetype}/values', [SizeTypeController::class, 'updateValues'])->name('sizetypes.updateValues');

    // ------------------ posts
    Route::resource('posts', PostController::class)->except(['show']);
    Route::get('post/categories', [PostController::class, 'categories'])->name('posts.categories.index');
    Route::post('post/slug', [PostController::class, 'generate_slug']);

    // ----------------- participance
    Route::get('participant/postIndex', [ParticipantController::class, 'postIndex'])->name('participant.postIndex');
    Route::resource('participant', ParticipantController::class);
    Route::post('participant/datatable', [ParticipantController::class, 'datatable'])->name('participant.datatable');
    Route::post('participant/postDatatable', [ParticipantController::class, 'postDatatable'])->name('participant.postDatatable');
    Route::post('participant/postStore', [ParticipantController::class, 'postStore'])->name('participant.postStore');
    Route::post('participant/postUpdate', [ParticipantController::class, 'postUpdate'])->name('participant.postUpdate');
    Route::post('participant/postEdit/{id}', [ParticipantController::class, 'postEdit'])->name('participant.postEdit');
    Route::post('participant/postShow/{id}', [ParticipantController::class, 'postShow'])->name('participant.postShow');
    Route::post('participant/stateDelete', [ParticipantController::class, 'stateDelete'])->name('participant.stateDelete');
    Route::post('participant/more/{id}', [ParticipantController::class, 'more'])->name('participant.more');
    Route::get('participant/deletePhoto/{id}', [ParticipantController::class, 'deletePhoto'])->name('participant.deletePhoto');

    // ------------------ categories
    Route::resource('categories', CategoryController::class)->only(['update', 'destroy', 'store', 'edit']);
    Route::post('categories/sort', [CategoryController::class, 'sort']);
    Route::post('category/slug', [CategoryController::class, 'generate_slug']);


    // ------------------ pages
    Route::resource('pages', PageController::class)->except(['show']);

    // ------------------ apikeys
    Route::resource('apikeys', ApikeyController::class)->except(['show']);

    // ------------------ tickets
    Route::resource('tickets', TicketController::class)->except(['edit']);
    Route::post('tickets/file/store', [TicketController::class, 'storeFile'])->name('tickets.file.store');
    Route::delete('tickets/file/destroy', [TicketController::class, 'destoryFile'])->name('tickets.file.destroy');

    // ------------------ menus
    Route::resource('menus', MenuController::class)->except(['edit']);
    Route::post('menus/sort', [MenuController::class, 'sort']);

    // ------------------ orders
    Route::resource('orders', OrderController::class);
    Route::post('orders/api/shippings-status', [OrderController::class, 'shippingsStatus'])->name('orders.shippings-status');
    Route::post('orders/{order}/shipping-status', [OrderController::class, 'shipping_status'])->name('orders.shipping-status');
    Route::get('orders/{order}/print', [OrderController::class, 'print'])->name('orders.print');
    Route::get('orders/{order}/shipping-form', [OrderController::class, 'shippingForm'])->name('orders.shipping-form');
    Route::post('orders/api/index', [OrderController::class, 'apiIndex'])->name('orders.apiIndex');
    Route::delete('orders/api/multipleDestroy', [OrderController::class, 'multipleDestroy'])->name('orders.multipleDestroy');
    Route::get('order/not-completed/products', [OrderController::class, 'notCompleted'])->name('orders.notCompleted');
    Route::get('orders/api/userInfo', [OrderController::class, 'userInfo'])->name('orders.userInfo');
    Route::get('orders/api/productsList', [OrderController::class, 'productsList'])->name('orders.productsList');
    Route::get('orders/api/printAllShippingForms', [OrderController::class, 'printAllShippingForms'])->name('orders.printAllShippingForms');
    Route::get('orders/api/printAll', [OrderController::class, 'printAll'])->name('orders.printAll');

    Route::get('orders/export/create', [OrderController::class, 'export'])->name('orders.export');

    // ------------------ carriers
    Route::resource('carriers', CarrierController::class);
    Route::get('carriers/{carrier}/cities', [CarrierController::class, 'cities'])->name('carriers.cities');

    // ------------------ tariffs
    Route::resource('tariffs', TariffController::class);

    // ------------------ transactions
    Route::resource('transactions', TransactionController::class)->only(['index', 'show', 'destroy']);
    Route::post('transactions/api/index', [TransactionController::class, 'apiIndex'])->name('transactions.apiIndex');
    Route::delete('transactions/api/multipleDestroy', [TransactionController::class, 'multipleDestroy'])->name('transactions.multipleDestroy');

    // ------------------ wallet-histories
    Route::resource('wallet-histories', WalletHistoryController::class)->only(['index', 'show']);

    // ------------------ currencies
    Route::resource('currencies', CurrencyController::class)->except(['show']);

    // ------------------ sliders
    Route::resource('sliders', SliderController::class)->except(['show']);
    Route::post('sliders/sort', [SliderController::class, 'sort']);

    // ------------------ banners
    Route::resource('banners', BannerController::class)->except(['show']);
    Route::post('banners/sort', [BannerController::class, 'sort']);

    // ------------------ links
    Route::resource('links', LinkController::class)->except(['show']);
    Route::post('links/sort', [LinkController::class, 'sort']);
    Route::get('links/groups', [LinkController::class, 'groups'])->name('links.groups.index');
    Route::put('links/groups/update', [LinkController::class, 'updateGroups'])->name('links.groups.update');

    // ------------------ statistics
    Route::get('statistics/viewsList', [StatisticsController::class, 'viewsList'])->name('statistics.viewsList');
    Route::get('statistics/views', [StatisticsController::class, 'views'])->name('statistics.views');
    Route::get('statistics/viewCounts', [StatisticsController::class, 'viewCounts'])->name('statistics.viewCounts');
    Route::get('statistics/viewerCounts', [StatisticsController::class, 'viewerCounts'])->name('statistics.viewerCounts');
    Route::get('statistics/viewers', [StatisticsController::class, 'viewers'])->name('statistics.viewers');

    Route::get('statistics/orders', [StatisticsController::class, 'orders'])->name('statistics.orders');
    Route::get('statistics/orderValues', [StatisticsController::class, 'orderValues'])->name('statistics.orderValues');
    Route::get('statistics/orderCounts', [StatisticsController::class, 'orderCounts'])->name('statistics.orderCounts');
    Route::get('statistics/orderUsers', [StatisticsController::class, 'orderUsers'])->name('statistics.orderUsers');
    Route::get('statistics/orderProducts', [StatisticsController::class, 'orderProducts'])->name('statistics.orderProducts');

    Route::get('statistics/users', [StatisticsController::class, 'users'])->name('statistics.users');
    Route::get('statistics/userCounts', [StatisticsController::class, 'userCounts'])->name('statistics.userCounts');

    Route::get('statistics/smsLog', [StatisticsController::class, 'smsLog'])->name('statistics.smsLog');
    Route::post('statistics/smsLogData', [StatisticsController::class, 'smsLogData'])->name('statistics.smsLogData');

    // ------------------ sms
    Route::resource('sms', SmsController::class)->only(['show']);

    // ------------------ contacts
    Route::resource('contacts', ContactController::class)->only(['index', 'show', 'destroy']);

    // ------------------ stock-notifies
    Route::resource('stock-notifies', StockNotifyController::class)->only(['index', 'show', 'destroy']);

    // ------------------ comments
    Route::resource('comments', CommentController::class)->only(['show', 'destroy', 'update']);
    Route::get('comments/index/products', [CommentController::class, 'productComments'])->name('comments.products');
    Route::get('comments/index/posts', [CommentController::class, 'postComments'])->name('comments.posts');
    Route::get('comments/index/state', [CommentController::class, 'stateComments'])->name('comments.state');

    // ------------------ reviews
    Route::get('reviews/shop', [ReviewController::class, 'shop'])->name('reviews.shop');
    Route::get('reviews/employee', [ReviewController::class, 'employee'])->name('reviews.employee');
    Route::get('reviews/service', [ReviewController::class, 'service'])->name('reviews.service');
    Route::resource('reviews', ReviewController::class)->only(['index', 'show', 'destroy', 'update']);

    // ------------------ roles
    Route::resource('roles', RoleController::class);

    // ------------------ permissions
    Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::put('permissions', [PermissionController::class, 'update'])->name('permissions.update');

    // ------------------ widgets
    Route::resource('widgets', WidgetController::class)->except(['show']);
    Route::get('widgets/{key}/template', [WidgetController::class, 'template'])->name('widgets.template');
    Route::post('widget/sort', [WidgetController::class, 'sort'])->name('widgets.sort');

    // ------------------ themes
    Route::resource('themes', ThemeController::class)->except(['edit']);

    Route::get('theme/settings', [ThemeController::class, 'showSettings'])->name('themes.settings');
    Route::post('theme/settings', [ThemeController::class, 'updateSettings']);

    // ------------------ settings
    Route::get('settings/information', [SettingController::class, 'showInformation'])->name('settings.information');
    Route::post('settings/information', [SettingController::class, 'updateInformation']);

    Route::get('settings/socials', [SettingController::class, 'showSocials'])->name('settings.socials');
    Route::post('settings/socials', [SettingController::class, 'updateSocials']);

    Route::get('settings/gateways', [SettingController::class, 'showGateways'])->name('settings.gateways');
    Route::post('settings/gateways', [SettingController::class, 'updateGateways']);

    Route::get('settings/others', [SettingController::class, 'showOthers'])->name('settings.others');
    Route::post('settings/others', [SettingController::class, 'updateOthers']);

    Route::get('settings/sms', [SettingController::class, 'showSms'])->name('settings.sms');
    Route::post('settings/sms', [SettingController::class, 'updateSms']);

    // ------------------ developer routes
    Route::group(['middleware' => 'CheckCreator'], function () {

        // ------------------ logs
        Route::get('logs', [LogViewerController::class, 'index'])->name('logs.index');

        // ------------------ settings
        Route::get('developer/settings', [DeveloperController::class, 'showSettings'])->name('developer.settings');
        Route::put('developer/settings', [DeveloperController::class, 'updateSettings']);

        Route::post('developer/downApplication', [DeveloperController::class, 'downApplication'])->name('developer.downApplication');
        Route::post('developer/upApplication', [DeveloperController::class, 'upApplication'])->name('developer.upApplication');

        Route::post('developer/webpushNotification', [DeveloperController::class, 'webpushNotification'])->name('developer.webpushNotification');

        // ------------------ updater
        Route::get('developer/updater', [DeveloperController::class, 'showUpdater'])->name('developer.showUpdater');
        Route::post('developer/updater', [DeveloperController::class, 'updateApplication'])->name('developer.updateApplication');
        Route::post('developer/updaterAfter', [DeveloperController::class, 'updaterAfter'])->name('developer.updaterAfter');
    });
});

// Push Subscriptions
Route::post('subscriptions', [PushSubscriptionController::class, 'update']);
Route::post('subscriptions/delete', [PushSubscriptionController::class, 'destroy']);

// Manifest file (optional if VAPID is used)
Route::get('manifest.json', function () {
    return [
        'name' => config('app.name'),
        'gcm_sender_id' => config('webpush.gcm.sender_id')
    ];
});
// Route::get('message/to/hussain', [CooperationSalesController::class, 'smsTest']);

// refresh csrf token
Route::get('refresh-csrf', function () {
    return csrf_token();
})->name('csrf');
