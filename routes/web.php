<?php

use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EmissionFactorController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\DeliveryConfirmationController;
use App\Http\Controllers\DeliveryExceptionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Operator\DashboardController as OperatorDashboardController;
use App\Http\Controllers\PublicTrackingController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\SustainabilityController;
use App\Http\Controllers\TrackingEventController;
use App\Http\Controllers\TransportLegController;
use Illuminate\Support\Facades\Route;

// =============================================================================
// PUBLIC GUEST ROUTES
// =============================================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/track/{tracking_number}', [PublicTrackingController::class, 'show'])->name('track.show');
Route::get('/track-search', [PublicTrackingController::class, 'search'])->name('track.search');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =============================================================================
// AUTHENTICATED ROUTES
// =============================================================================
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        $user = auth()->user();
        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'operator' => redirect()->route('operator.dashboard'),
            'customer' => redirect()->route('customer.dashboard'),
            default => redirect()->route('home'),
        };
    })->name('dashboard');

    // Common Freight Registry & Cockpit
    Route::resource('shipments', ShipmentController::class);
    Route::post('/shipments/{shipment}/legs', [TransportLegController::class, 'store'])->name('transport-legs.store');
    Route::patch('/transport-legs/{leg}', [TransportLegController::class, 'update'])->name('transport-legs.update');
    Route::post('/shipments/{shipment}/events', [TrackingEventController::class, 'store'])->name('tracking-events.store');
    Route::post('/shipments/{shipment}/exceptions', [DeliveryExceptionController::class, 'store'])->name('exceptions.store');
    Route::post('/delivery-exceptions/{exception}/resolve', [DeliveryExceptionController::class, 'resolve'])->name('exceptions.resolve');
    Route::post('/shipments/{shipment}/confirm-delivery', [DeliveryConfirmationController::class, 'confirm'])->name('delivery.confirm');

    // Sustainability Engine View
    Route::get('/sustainability', [SustainabilityController::class, 'index'])->name('sustainability.index');

    // -------------------------------------------------------------------------
    // ADMINISTRATOR PORTAL
    // -------------------------------------------------------------------------
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', UserController::class);
        Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::resource('emission-factors', EmissionFactorController::class)->only(['index', 'edit', 'update']);
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    });

    // -------------------------------------------------------------------------
    // LOGISTICS OPERATOR PORTAL
    // -------------------------------------------------------------------------
    Route::middleware('role:operator,admin')->prefix('operator')->name('operator.')->group(function () {
        Route::get('/dashboard', [OperatorDashboardController::class, 'index'])->name('dashboard');
    });

    // -------------------------------------------------------------------------
    // CUSTOMER PORTAL
    // -------------------------------------------------------------------------
    Route::middleware('role:customer')->prefix('customer')->name('customer.')->group(function () {
        Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    });
});
