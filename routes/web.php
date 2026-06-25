<?php

use App\Http\Controllers\Backend\CurrencyController;
use App\Http\Controllers\Backend\Pos\CartController;
use App\Http\Controllers\Backend\Product\ProductController;
use App\Http\Controllers\Backend\Report\ReportController;
use App\Http\Controllers\Backend\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RecoveryController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Backend\Product\CategoryController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\RolePermission\PermissionController;
use App\Http\Controllers\Backend\Pos\OrderController;
use App\Http\Controllers\Backend\InstallmentController;
use App\Http\Controllers\Backend\InstallmentDashboardController;
use App\Http\Controllers\Backend\BackupController;
use App\Http\Controllers\Backend\AuditLogController;
use App\Http\Controllers\Backend\SystemHealthController;
use App\Http\Controllers\Backend\NotificationController;
use App\Http\Controllers\Backend\StockMovementController;
use App\Http\Controllers\Backend\CashRegisterController;
use App\Http\Controllers\Backend\LicenseController;
use App\Http\Controllers\Backend\Report\AdvancedReportController;
use App\Http\Controllers\Backend\Product\BrandController;
use App\Http\Controllers\Backend\Product\PurchaseController;
use App\Http\Controllers\Backend\RolePermission\RoleController;
use App\Http\Controllers\Backend\Product\UnitController;
use App\Http\Controllers\Backend\UserManagementController;
use App\Http\Controllers\Backend\WebsiteSettingController;
use App\Models\Supplier;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ====================== FRONTEND ======================

// Account recovery — localhost only, no auth required
Route::get('/recovery', [RecoveryController::class, 'index'])->name('recovery.index');
Route::post('/recovery/{user}/reset', [RecoveryController::class, 'reset'])->name('recovery.reset');

// homepage
Route::get('/', function () {
    return to_route('login');
})->name('frontend.home');

//authentication
Route::match(['get', 'post'], 'login', [AuthController::class, 'login'])->name('login');

Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::match(['get', 'post'], 'sign-up', [AuthController::class, 'register'])->name('signup');
Route::match(['get', 'post'], 'forget-password', [AuthController::class, 'forgetPassword'])->name('forget.password');
Route::match(['get', 'post'], 'new-password', [AuthController::class, 'newPassword'])->name('new.password');
Route::match(['get', 'post'], 'password-reset', [AuthController::class, 'resetPassword'])->name('password.reset');
Route::get('resend-otp', [AuthController::class, 'resendOtp'])->name('resend.otp');
Route::redirect('login-otp', '/login')->name('login.otp');
Route::redirect('resend-login-otp', '/login')->name('resend.login.otp');

// google auth
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.handle.callback');

// ====================== /FRONTEND =====================

// ====================== BACKEND =======================

Route::prefix('admin')->as('backend.admin.')->middleware(['admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', ProductController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('purchase', PurchaseController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('units', UnitController::class);
    Route::resource('currencies', CurrencyController::class);
    Route::match(['get', 'post'], 'import/products', [ProductController::class,'import'])->name('products.import');
    Route::get('currencies/default/{id}', [CurrencyController::class, 'setDefault'])->name('currencies.setDefault');
    Route::get('customers/orders/{id}', [CustomerController::class, 'orders'])->name('customers.orders');
    Route::get('purchase/products/{id}', [PurchaseController::class, 'purchaseProducts'])->name('purchase.products');
    Route::get('orders/invoice/{id}', [OrderController::class,'invoice'])->name('orders.invoice');
    Route::get('orders/pos-invoice/{id}', [OrderController::class, 'posInvoice'])->name('orders.pos-invoice');
    Route::get('orders/transactions/{id}', [OrderController::class, 'transactions'])->name('orders.transactions');
    Route::match(['get', 'post'], 'orders/due/collection/{id}', [OrderController::class, 'collection'])->name('due.collection');
    Route::get('collection/invoice/{id}', [OrderController::class, 'collectionInvoice'])->name('collectionInvoice');
    Route::resource('categories', CategoryController::class);
    Route::get('installments', [InstallmentController::class, 'index'])->name('installments.index');
    Route::get('installments/{id}', [InstallmentController::class, 'show'])->name('installments.show');

    // Installment Dashboard
    Route::prefix('installment-dashboard')->as('installment-dashboard.')->group(function () {
        Route::get('/', [InstallmentDashboardController::class, 'index'])->name('index');
        Route::get('/overdue', [InstallmentDashboardController::class, 'overdue'])->name('overdue');
        Route::get('/due-today', [InstallmentDashboardController::class, 'dueToday'])->name('due-today');
        Route::get('/upcoming', [InstallmentDashboardController::class, 'upcoming'])->name('upcoming');
    });

    // Backup Management
    Route::prefix('backup')->as('backup.')->group(function () {
        Route::get('/', [BackupController::class, 'index'])->name('index');
        Route::post('/run', [BackupController::class, 'run'])->name('run');
        Route::get('/download/{id}', [BackupController::class, 'download'])->name('download');
        Route::post('/restore/{id}', [BackupController::class, 'restore'])->name('restore');
        Route::delete('/delete/{id}', [BackupController::class, 'destroy'])->name('delete');
    });

    // Audit Logs
    Route::prefix('audit-logs')->as('audit-logs.')->group(function () {
        Route::get('/', [AuditLogController::class, 'index'])->name('index');
        Route::get('/{id}', [AuditLogController::class, 'show'])->name('show');
    });

    // System Health
    Route::prefix('system-health')->as('system-health.')->group(function () {
        Route::get('/', [SystemHealthController::class, 'index'])->name('index');
        Route::get('/api', [SystemHealthController::class, 'api'])->name('api');
    });

    // Notification Center
    Route::prefix('notifications')->as('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/read', [NotificationController::class, 'markRead'])->name('mark-read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllRead'])->name('mark-all-read');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('delete');
        Route::get('/unread', [NotificationController::class, 'getUnread'])->name('unread');
    });

    // Stock Movements
    Route::prefix('stock-movements')->as('stock-movements.')->group(function () {
        Route::get('/', [StockMovementController::class, 'index'])->name('index');
        Route::post('/adjust', [StockMovementController::class, 'adjust'])->name('adjust');
    });

    // Cash Register
    Route::prefix('cash-register')->as('cash-register.')->group(function () {
        Route::get('/', [CashRegisterController::class, 'index'])->name('index');
        Route::post('/open', [CashRegisterController::class, 'open'])->name('open');
        Route::post('/close', [CashRegisterController::class, 'close'])->name('close');
        Route::get('/{cashRegister}/edit', [CashRegisterController::class, 'edit'])->name('edit');
        Route::post('/{cashRegister}/update', [CashRegisterController::class, 'update'])->name('update');
    });

    // License Management
    Route::prefix('license')->as('license.')->group(function () {
        Route::get('/', [LicenseController::class, 'index'])->name('index');
        Route::post('/update', [LicenseController::class, 'update'])->name('update');
    });

    // Advanced Reports
    Route::prefix('reports/advanced')->as('reports.advanced.')->group(function () {
        Route::get('/sales-by-day', [AdvancedReportController::class, 'salesByDay'])->name('sales-by-day');
        Route::get('/sales-by-month', [AdvancedReportController::class, 'salesByMonth'])->name('sales-by-month');
        Route::get('/sales-by-product', [AdvancedReportController::class, 'salesByProduct'])->name('sales-by-product');
        Route::get('/sales-by-employee', [AdvancedReportController::class, 'salesByEmployee'])->name('sales-by-employee');
        Route::get('/installment-collections', [AdvancedReportController::class, 'installmentCollections'])->name('installment-collections');
        Route::get('/outstanding-balances', [AdvancedReportController::class, 'outstandingBalances'])->name('outstanding-balances');
    });

    //start report

    Route::get('/sale/summery', [ReportController::class, 'saleSummery'])->name('sale.summery');
    Route::get('/sale/report', [ReportController::class, 'saleReport'])->name('sale.report');
    Route::get('/inventory/report', [ReportController::class, 'inventoryReport'])->name('inventory.report');
    //end report
   // start pos
    Route::get('/get/products', [CartController::class, 'getProducts'])->name('getProducts');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::put('/cart/increment', [CartController::class, 'increment']);
    Route::put('/cart/decrement', [CartController::class, 'decrement']);
    Route::put('/cart/delete', [CartController::class, 'delete']);
    Route::put('/cart/empty', [CartController::class, 'empty']);
    Route::put('/order/create', [OrderController::class, 'store']);
    Route::get('/get/customers',[CustomerController::class,'getCustomers']);
    Route::get('/get/customers/{id}/guarantors',[CustomerController::class,'getGuarantors']);
    Route::post('/create/customers', [CustomerController::class, 'store']);
    //end pos
    Route::get('profile', [DashboardController::class, 'profile'])->name('profile');
    Route::post('profile/update', [AuthController::class, 'update'])->name('profile.update');

    // user management
    Route::prefix('users')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('users');
        Route::get('suspend/{id}/{status}', [UserManagementController::class, 'suspend'])->name('user.suspend');
        Route::match(['get', 'post'], 'create', [UserManagementController::class, 'create'])->name('user.create');
        Route::match(['get', 'post'], 'edit/{id}', [UserManagementController::class, 'edit'])->name('user.edit');
        Route::get('delete/{id}', [UserManagementController::class, 'delete'])->name('user.delete');
    });

    // settings
    Route::prefix('settings')->group(function () {
        // website settings
        Route::prefix('website')->group(function () {
            Route::controller(WebsiteSettingController::class)->prefix('general')->group(function () {
                Route::get('/', 'websiteGeneral')->name('settings.website.general');
                Route::post('update-info', 'websiteInfoUpdate')->name('settings.website.info.update');
                Route::post('update-contacts', 'websiteContactsUpdate')->name('settings.website.contacts.update');
                Route::post('update-social-links', 'websiteSocialLinkUpdate')->name('settings.website.social.link.update');
                Route::post('update-style-settings', 'websiteStyleSettingsUpdate')->name('settings.website.style.settings.update');
                Route::post('update-custom-css', 'websiteCustomCssUpdate')->name('settings.website.custom.css.update');
                Route::post('update-notification-settings', 'websiteNotificationSettingsUpdate')->name('settings.website.notification.settings.update');
                Route::post('update-website-status', 'websiteStatusUpdate')->name('settings.website.status.update');

                Route::post('update-invoice-settings', 'websiteInvoiceUpdate')->name('settings.website.invoice.update');
            });

            Route::controller(RoleController::class)->prefix('roles')->group(function () {
                Route::get('/', 'index')->name('roles');
                Route::post('create', 'store')->name('roles.create');
                Route::get('show/{id}', 'show')->name('roles.show');
                Route::put('update/{id}', 'update')->name('roles.update');
                Route::get('delete/{id}', 'destroy')->name('roles.delete');
                Route::post('role-permission/{id}', 'updatePermission')->name('update.role-permissions');
                Route::get('role-wise-permissions/{id?}', 'roleWisePermissions')->name('role-wise-permissions');
            });

            Route::controller(PermissionController::class)->prefix('permissions')->group(function () {
                Route::get('/', 'index')->name('permissions');
                Route::post('create', 'store')->name('permissions.store');
                // Route::get('show/{id}', 'show')->name('roles.show');
                Route::put('update/{id}', 'update')->name('permissions.update');
                Route::get('delete/{id}', 'destroy')->name('permissions.delete');
            });
        });
    });
});

// ====================== /BACKEND ======================

Route::get('clear-all', function () {
    Artisan::call('optimize:clear');
    return redirect()->back();
});

Route::get('storage-link', function () {
    Artisan::call('storage:link');
    return redirect()->back();
});

