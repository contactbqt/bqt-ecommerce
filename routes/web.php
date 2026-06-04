<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

// ----------------------------------------- //
// Admin Authentication
// ----------------------------------------- //
use App\Livewire\Auth\AdminLogin;
use App\Livewire\Admin\Dashboard\DashboardIndex;
use App\Livewire\Admin\Category\CategoryIndex;
use App\Livewire\Admin\Category\CategoryAdditionalInfo;
use App\Livewire\Admin\Attribute\AttributeIndex;
use App\Livewire\Admin\Attribute\AttributeValueIndex;
use App\Livewire\Admin\Product\ProductIndex;
use App\Livewire\Admin\Product\ProductCreate;
use App\Livewire\Admin\Product\ProductEdit;
use App\Livewire\Admin\Product\ProductAttributeIndex;
use App\Livewire\Admin\Product\ProductVariantCreate;
use App\Livewire\Admin\Product\ProductVariantEdit;
use App\Livewire\Admin\Product\ProductImages;
use App\Livewire\Admin\Product\ProductMeta;
use App\Livewire\Admin\Backup\BackupIndex;
use App\Livewire\Admin\System\SystemReset;

// ----------------------------------------- //
// Web Guard Authentication (Patient/User)
// ----------------------------------------- //
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Frontend\ThankYou\Index as ThankYouIndex;

// ********************************************************** //
// Basic Home Route & Shop Route
// ********************************************************** //
Route::middleware([\App\Http\Middleware\CheckMaintenanceMode::class])->group(function () {
    Route::get('/', \App\Livewire\Frontend\Home\Index::class)->name('home');
    Route::get('/shop/{category?}', \App\Livewire\Frontend\Shop\Index::class)->name('shop');
    Route::get('product/{category_slug}/{product_slug}/{varient_id}', \App\Livewire\Frontend\Product\Details::class)->name('product.details');
    Route::get('/cart', \App\Livewire\Frontend\Cart\Index::class)->name('cart');
    Route::get('/checkout', \App\Livewire\Frontend\Checkout\Index::class)->name('checkout');
    Route::get('/thank-you', \App\Livewire\Frontend\ThankYou\Index::class)->name('thankyou');

    // Terms and Privacy Policy
    Route::get('/terms-and-conditions', function () {
        return view('frontend.pages.terms');
    })->name('terms');

    Route::get('/privacy-policy', function () {
        return view('frontend.pages.privacy');
    })->name('privacy');

    //facebook-data-deletion
    Route::get('/facebook-data-deletion', function () {
        return view('frontend.pages.facebook-data-deletion');
    })->name('facebook-data-deletion');

    // ********************************************************** //
    // Web Guard Routes (User Portal)
    // ********************************************************** //
    Route::middleware(['auth:web', 'verified'])->group(function () {
        Route::get('dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        Route::redirect('settings', 'settings/profile');
        Route::get('settings/profile', Profile::class)->name('settings.profile');
        Route::get('settings/password', Password::class)->name('settings.password');
        
        // User Orders
        Route::get('user/orders', \App\Livewire\Frontend\User\Order\Index::class)->name('user.orders');
        Route::get('user/orders/{id}', \App\Livewire\Frontend\User\Order\Details::class)->name('user.orders.details');

        // User Address Book
        Route::get('user/address-book', \App\Livewire\Frontend\User\Address\Index::class)->name('user.address.index');

        // User Wishlist
        Route::get('user/wishlist', \App\Livewire\Frontend\User\Wishlist\Index::class)->name('user.wishlist.index');
    });
});

Route::middleware(['auth:web'])->group(function () {
    Route::post('logout', function () {
        Auth::guard('web')->logout();
        Session::invalidate();
        Session::regenerateToken();
        return redirect()->route('home');
    })->name('logout');
});

// ********************************************************** //
// Admin Routes
// ********************************************************** //
Route::prefix('admin')->group(function () {
    Route::get('/', AdminLogin::class)->name('admin.login');
    Route::get('/login', AdminLogin::class)->name('admin.login');

    Route::middleware(['auth:admin'])->group(function () {
        // Admin Dashboard
        Route::get('dashboard', DashboardIndex::class)->name('admin.dashboard');

        // Categories Management
        Route::get('categories', CategoryIndex::class)->name('admin.category.index');
        Route::get('categories/{id}/additional-info', CategoryAdditionalInfo::class)->name('admin.category.info');
        Route::get('categories/{id}/tag-attributes', \App\Livewire\Admin\Category\CategoryTagAttributes::class)->name('admin.category.attributes');

        //Attributes Management
        Route::get('attributes', AttributeIndex::class)->name('admin.attribute.index');
        Route::get('attributes/{id}/values', AttributeValueIndex::class)->name('admin.attribute.values');

        //Product Management
        Route::get('products', ProductIndex::class)->name('admin.product.index');
        Route::get('product/create', ProductCreate::class)->name('admin.product.create');
        Route::get('product/{id}/edit', ProductEdit::class)->name('admin.product.edit');
        Route::get('product/{id}/attributes', ProductAttributeIndex::class)->name('admin.product.attributes');
        Route::get('product/{id}/variants', ProductVariantEdit::class)->name('admin.product.variants');
        Route::get('product/{id}/variants/create', ProductVariantCreate::class)->name('admin.product.variants.create');
        Route::get('product/{id}/variants/edit', ProductVariantEdit::class)->name('admin.product.variants.edit');
        Route::get('product/{id}/images', ProductImages::class)->name('admin.product.images');
        Route::get('product/{id}/meta', ProductMeta::class)->name('admin.product.meta');

        //Tag Management
        Route::get('tags', \App\Livewire\Admin\Tag\TagIndex::class)->name('admin.tag.index');
        Route::get('tags/{id}/edit', \App\Livewire\Admin\Tag\TagEdit::class)->name('admin.tag.edit');

        //Order Management
        Route::get('orders', \App\Livewire\Admin\Order\OrderIndex::class)->name('admin.order.index');
        Route::get('orders/{id}', \App\Livewire\Admin\Order\OrderDetails::class)->name('admin.order.details');

        //Customer Management
        Route::get('customers', \App\Livewire\Admin\Customer\CustomerIndex::class)->name('admin.customer.index');

        //Review Management
        Route::get('reviews', \App\Livewire\Admin\Review\ReviewIndex::class)->name('admin.reviews.index');

        //Import Management
        Route::get('import', \App\Livewire\Admin\Import\ImportIndex::class)->name('admin.import.index');

        // Settings Management
        Route::get('settings', \App\Livewire\Admin\Setting\SettingGroupIndex::class)->name('admin.setting.index');
        Route::get('settings/{slug}', \App\Livewire\Admin\Setting\SettingManage::class)->name('admin.setting.manage');

        //Backup Management
        Route::get('backup', BackupIndex::class)->name('admin.backup.index');

        //System Reset Management
        Route::get('system/reset', SystemReset::class)->name('admin.system.reset');

        Route::post('logout', function () {
            Auth::guard('admin')->logout();
            Session::invalidate();
            Session::regenerateToken();
            return redirect()->route('admin.login');
        })->name('admin.logout');
    });
});

// ********************************************************** //
// Authentication Routes
// ********************************************************** //
Route::middleware([\App\Http\Middleware\CheckMaintenanceMode::class])->group(function () {
    require __DIR__ . '/auth.php';
});
