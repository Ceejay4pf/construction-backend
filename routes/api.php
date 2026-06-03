<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\BoqController;
use App\Http\Controllers\LabourInvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\SiteProgressController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MpesaController;

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/mpesa/callback', [MpesaController::class, 'callback']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Projects
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::get('/projects/{id}', [ProjectController::class, 'show']);
    Route::put('/projects/{id}', [ProjectController::class, 'update']);
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);

    // Floors
    Route::get('/projects/{projectId}/floors', [FloorController::class, 'index']);
    Route::put('/floors/{id}', [FloorController::class, 'update']);

    // BOQ
    Route::get('/projects/{projectId}/boq', [BoqController::class, 'index']);
    Route::post('/boq', [BoqController::class, 'store']);
    Route::put('/boq/{id}', [BoqController::class, 'update']);
    Route::delete('/boq/{id}', [BoqController::class, 'destroy']);

    // Labour Invoices
    Route::get('/projects/{projectId}/invoices', [LabourInvoiceController::class, 'index']);
    Route::post('/invoices', [LabourInvoiceController::class, 'store']);
    Route::put('/invoices/{id}', [LabourInvoiceController::class, 'update']);
    Route::delete('/invoices/{id}', [LabourInvoiceController::class, 'destroy']);

    // Payments
    Route::get('/projects/{projectId}/payments', [PaymentController::class, 'index']);
    Route::post('/payments', [PaymentController::class, 'store']);

    // Materials
    Route::get('/projects/{projectId}/materials', [MaterialController::class, 'index']);
    Route::post('/materials', [MaterialController::class, 'store']);
    Route::put('/materials/{id}', [MaterialController::class, 'update']);
    Route::delete('/materials/{id}', [MaterialController::class, 'destroy']);

    // Site Progress
    Route::get('/projects/{projectId}/progress', [SiteProgressController::class, 'index']);
    Route::post('/progress', [SiteProgressController::class, 'store']);

    // Mpesa STK Push
    Route::post('/mpesa/stk-push', [MpesaController::class, 'stkPush']);
});