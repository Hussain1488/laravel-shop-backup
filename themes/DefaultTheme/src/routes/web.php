<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Themes\DefaultTheme\src\Controllers\installmentsListController;
use Themes\DefaultTheme\src\Controllers\MainController;
use Themes\DefaultTheme\src\Controllers\PostController;
use Themes\DefaultTheme\src\Controllers\ProductController;
use Themes\DefaultTheme\src\Controllers\ReviewController;
use Themes\DefaultTheme\src\Controllers\CartController;
use Themes\DefaultTheme\src\Controllers\StockNotifyController;
use Themes\DefaultTheme\src\Controllers\PageController;
use Themes\DefaultTheme\src\Controllers\BrandController;
use Themes\DefaultTheme\src\Controllers\OrderController;
use Themes\DefaultTheme\src\Controllers\UserController;
use Themes\DefaultTheme\src\Controllers\SitemapController;
use Themes\DefaultTheme\src\Controllers\ContactController;
use Themes\DefaultTheme\src\Controllers\FavoriteController;
use Themes\DefaultTheme\src\Controllers\TicketController;
use Themes\DefaultTheme\src\Controllers\VerifyController;
use Themes\DefaultTheme\src\Controllers\DiscountController;
use Themes\DefaultTheme\src\Controllers\FaceSwapController;
use Themes\DefaultTheme\src\Controllers\FittingController;
use Themes\DefaultTheme\src\Controllers\WalletController;
use Themes\DefaultTheme\src\Controllers\InstallmentsController;
use Themes\DefaultTheme\src\Controllers\LotteryController;
use Themes\DefaultTheme\src\Controllers\ParticipantController;
use Themes\DefaultTheme\src\Controllers\ShopController;

// ------------------ Front Part Routes

Route::group(['as' => 'front.'], function () {

    // ------------------ FaceSwapController
    Route::get('face-swap/download', [FaceSwapController::class, 'download'])->name('faceSwap.download');
    Route::post('face-swap', [FaceSwapController::class, 'createJob'])->name('faceSwap.createJob');
    Route::get('/face-swap/{jobId}', [FaceSwapController::class, 'getFaceSwapResult'])->name('faceSwap.getImage');

    // ------------------ FittingController

    Route::resource('fitting', FittingController::class);
    Route::post('fitting/downloadImage/{VirtualFitting}', [FittingController::class, 'downloadImage'])->name('fitting.downloadImage');
    // Route::get('fitting/show/{fitting}', [FittingController::class, 'show'])->name('fitting.show');


    // ------------------ MainController
    Route::get('/', [MainController::class, 'index'])->name('index');
    Route::get('/get-new-captcha', [MainController::class, 'captcha']);

    // ------------------ posts
    Route::resource('posts', PostController::class)->only(['index', 'show']);
    Route::get('posts/category/{category}', [PostController::class, 'category'])->name('posts.category');

    // ------------------participant
    Route::post('participant/comment', [ParticipantController::class, 'comment'])->name('participant.comment');
    Route::get('participant/comments/{id}', [ParticipantController::class, 'comments'])->name('participant.comments');
    Route::post('participant/like/{id}', [ParticipantController::class, 'like'])->name('participant.like');
    Route::post('participant/participate', [ParticipantController::class, 'participate'])->name('participant.participate');
    Route::post('participant/participateGetdata', [ParticipantController::class, 'participateGetdata'])->name('participant.participateGetdata');
    Route::post('participant/participatStore', [ParticipantController::class, 'participatStore'])->name('participant.participatStore');
    Route::post('participant/more/{id}', [ParticipantController::class, 'more'])->name('participant.more');
    Route::post('participant/stateForm/{id}', [ParticipantController::class, 'stateForm'])->name('participant.stateForm');
    Route::post('participant/postUpdate/{id}', [ParticipantController::class, 'postUpdate'])->name('participant.postUpdate');
    Route::post('participant/deletePhoto/{id}', [ParticipantController::class, 'deletePhoto'])->name('participant.deletePhoto');

    // ------------------ products
    Route::resource('products', ProductController::class)->only(['show', 'index']);
    Route::get('products/category/{category}', [ProductController::class, 'category'])->name('products.category');
    Route::get('products/category-products/{category}', [ProductController::class, 'categoryProducts'])->name('products.category-products');
    Route::get('products/category-specials/{category}', [ProductController::class, 'categorySpecials'])->name('products.category-specials');
    Route::get('search', [ProductController::class, 'search'])->name('products.search');
    Route::post('search', [ProductController::class, 'ajax_search'])->name('products.ajax_search');
    Route::get('all', [ProductController::class, 'all'])->name('products.all');
    Route::get('product/specials', [ProductController::class, 'specials'])->name('products.specials');
    Route::get('product/discount', [ProductController::class, 'discount'])->name('products.discount');
    Route::get('product/{product}/prices', [ProductController::class, 'prices'])->name('products.prices');
    Route::get('product/compare/{product1}/{product2?}/{product3?}', [ProductController::class, 'compare'])->name('products.compare');
    Route::post('product/compare', [ProductController::class, 'similarCompare'])->name('products.similar-compare');
    Route::get('products/{price}/priceChart', [ProductController::class, 'priceChart'])->name('products.priceChart');
    Route::get('p/{id}', [ProductController::class, 'shortLink'])->name('products.shortLink');
    Route::get('product/imageSwap/{id}', [ProductController::class, 'imageSwap'])->name('products.imageSwap');
    Route::post('products/image-store', [ProductController::class, 'imageStore'])->name('products.image-store');
    Route::post('products/image-delete', [ProductController::class, 'image_delete']);




    // ------------------ cart
    Route::get('cart', [CartController::class, 'show'])->name('cart');
    Route::post('cart/{product}', [CartController::class, 'store'])->name('cart.store');
    Route::put('cart', [CartController::class, 'update']);
    Route::delete('cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::post('stock-notify', [StockNotifyController::class, 'store']);


    // Lottery routes
    Route::get('lottery/index', [LotteryController::class, 'index'])->name('lottery.index');
    Route::get('lottery/dailyCode', [LotteryController::class, 'dailyCode'])->name('lottery.dailyCode');
    Route::post('lottery/invoiceCode', [LotteryController::class, 'invoiceCode'])->name('lottery.invoiceCode');


    // Shop routes
    Route::resource('shops', ShopController::class);
    Route::post('shops/review', [ShopController::class, 'storeReview'])->name('shop.review');
    Route::post('shops/employeeReview', [ShopController::class, 'employeeReview'])->name('shop.employeeReview');
    Route::post('shops/serviceReview', [ShopController::class, 'serviceReview'])->name('shop.serviceReview');
    Route::get('shops/employee/{id}', [ShopController::class, 'employee'])->name('shop.employee');
    Route::get('shops/service/{id}', [ShopController::class, 'service'])->name('shop.service');
    Route::post('shops/{shops}/like', [ShopController::class, 'like'])->name('shop.like');
    Route::post('shops/{shops}/dislike', [ShopController::class, 'dislike'])->name('shop.dislike');


    // ------------------ pages
    Route::get('pages/{page}', [PageController::class, 'show'])->name('pages.show');

    // ------------------ brands
    Route::get('brands/{brand}', [BrandController::class, 'show'])->name('brands.show');

    // ------------------ sitemap
    Route::get('sitemap', [SitemapController::class, 'index']);
    Route::get('sitemap-posts', [SitemapController::class, 'posts']);
    Route::get('sitemap-pages', [SitemapController::class, 'pages']);
    Route::get('sitemap-products', [SitemapController::class, 'products']);

    // ------------------ contacts
    Route::resource('contact', ContactController::class)->only(['index', 'store']);

    // ------------------ orders
    Route::any('orders/payment/callback/{gateway}', [OrderController::class, 'verify'])->name('orders.verify');

    // ------------------ wallet
    Route::any('wallet/payment/callback/{gateway}', [WalletController::class, 'verify'])->name('wallet.verify');

    // ------------------ authentication required
    Route::group(['middleware' => ['auth', 'verified', 'CheckPasswordChange']], function () {

        // ------------------ MainController
        Route::get('checkout', [MainController::class, 'checkout'])->middleware('check.product.availability')->name('checkout');
        Route::get('checkout-prices', [MainController::class, 'getPrices'])->middleware('check.product.availability')->name('checkout.prices');
        Route::get('reserve-cart', [MainController::class, 'reserveCart'])->middleware('check.product.availability')->name('reserve-cart');

        // ------------------ discount
        Route::post('discount', [DiscountController::class, 'store'])->name('discount.store');
        Route::delete('discount', [DiscountController::class, 'destroy'])->name('discount.destroy');

        // ------------------ orders
        Route::resource('orders', OrderController::class);
        Route::get('orders/pay/{order}', [OrderController::class, 'pay'])->name('orders.pay');

        // ------------------ wallet
        Route::resource('wallet', WalletController::class)->only(['index', 'show', 'create', 'store']);
        Route::post('wallet/rechargeVarify', [WalletController::class, 'rechargeVarify'])->name('wallet.rechargeVarify');
        Route::get('wallets/codeGenerate/{smsType}', [WalletController::class, 'codeGenerate'])->name('wallet.codeGenerate');
        Route::post('wallet/sendCode', [WalletController::class, 'sendCode'])->name('wallet.sentCode');

        // installmentsController
        Route::resource('installments', InstallmentsController::class)->only(['index', 'show', 'create', 'store']);
        Route::get('installments/userstatus/{id}', [InstallmentsController::class, 'userStatus'])->name('installments.usrestatus.edit');
        Route::get('installments/payment/{id1}/{id2}', [InstallmentsController::class, 'paymentStatus'])->name('installments.paymentStatus.edit');
        Route::get('installments/refuse/{id}', [InstallmentsController::class, 'refuse'])->name('installments.usrestatus.refuse');
        Route::get('installments/pay/{id}', [InstallmentsController::class, 'pay'])->name('installments.usrestatus.pay');

        Route::get('code-resent', [MainController::class, 'resentSms'])->name('resentSms');

        // installment LIst:
        Route::get('user/purchaselist', [installmentsListController::class, 'purchaseList'])->name('installments.list');



        // ------------------ user
        Route::get('user/profile', [UserController::class, 'profile'])->name('user.profile');
        Route::get('user/comments', [UserController::class, 'comments'])->name('user.comments');
        Route::get('user/edit-profile', [UserController::class, 'editProfile'])->name('user.profile.edit');
        Route::put('user/profile', [UserController::class, 'update_profile'])->name('user.profile.update');
        Route::get('user/change-password', [UserController::class, 'changePassword'])->name('user.password');
        Route::put('user/change-password', [UserController::class, 'updatePassword'])->name('user.password.update');
        Route::get('user/referrals', [UserController::class, 'referrals'])->name('user.referrals.index');


        Route::group(['middleware' => ['EnsureForceChange']], function () {
            Route::get('user/force-change-password', [UserController::class, 'forceChangePassword'])->name('user.force-change-password');
            Route::post('user/force-change-password', [UserController::class, 'forceUpdatePassword'])->name('user.force-update-password');
        });

        // ------------------ products
        Route::get('products/{price}/download', [ProductController::class, 'download'])->name('products.download');
        Route::post('products/{product}/comments', [ProductController::class, 'comments'])->name('product.comments');

        // ------------------ reviews
        Route::resource('reviews', ReviewController::class)->only(['store', 'index']);
        Route::get('reviews/{product}', [ReviewController::class, 'show'])->name('reviews.show');
        Route::post('reviews/{review}/like', [ReviewController::class, 'like'])->name('reviews.like');
        Route::post('reviews/{review}/dislike', [ReviewController::class, 'dislike'])->name('reviews.dislike');

        // ------------------ posts
        Route::post('posts/{post}/comments', [PostController::class, 'comments'])->name('post.comments');

        // ------------------ favorites
        Route::resource('favorites', FavoriteController::class)->only(['index', 'store', 'destroy']);

        // ------------------ Match
        Route::get('match', [ParticipantController::class, 'index'])->name('match.index');

        // ------------------ tickets
        Route::resource('tickets', TicketController::class)->except(['destroy']);
    });

    // ------------------ verify user routes
    Route::group(['middleware' => ['auth', 'notVerified', 'CheckPasswordChange']], function () {
        Route::get('verify', [VerifyController::class, 'showVerify'])->name('verify.showVerify');
        Route::post('verify', [VerifyController::class, 'verifyCode'])->name('verify.verifyCode')->middleware('throttle:15,1');
        Route::get('change-username', [VerifyController::class, 'showChangeUsername'])->name('verify.showChangeUsername');
        Route::post('change-username', [VerifyController::class, 'changeUsername'])->name('verify.changeUsername');
    });
});



// get auth user in 404 page
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
