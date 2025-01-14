<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AttendanceController;
use App\Models\Report;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Budget Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/budgets/budget', [BudgetController::class, 'create'])->name('budget.create');
    Route::post('/budgets/budget', [BudgetController::class, 'store'])->name('budget.store');
    Route::get('/budget/history', [BudgetController::class, 'history'])->name('budget.history');
    Route::put('/budget/update', [BudgetController::class, 'update'])->name('budget.update');
});

// Expense Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/expenses/expense', [ExpenseController::class, 'create'])->name('expense.create');
    Route::post('/expenses/expense', [ExpenseController::class, 'store'])->name('expense.store');
    Route::get('/expense/history', [ExpenseController::class, 'history'])->name('expense.history');
    Route::put('/expense/update', [ExpenseController::class, 'update'])->name('expense.update');
});

// Income Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/incomes/income', [IncomeController::class, 'create'])->name('income.create');
    Route::post('/incomes/income', [IncomeController::class, 'store'])->name('income.store');
    Route::get('/income/history', [IncomeController::class, 'history'])->name('income.history');
    Route::put('/income/update', [IncomeController::class, 'update'])->name('income.update');
});

//Report
Route::get('/report', [ReportController::class, 'showReport'])
    ->middleware(['auth', 'verified'])
    ->name('report');

Route::post('/send-monthly-report', [ReportController::class, 'sendMonthlyReport'])
    ->middleware(['auth', 'verified'])
    ->name('sendMonthlyReport');

    
//Invoice
Route::group(['middleware' => ['auth', 'verified']], function () {
    // Invoice List Route (Requires authentication and email verification)
    Route::get('/invoices/invoice', [InvoiceController::class, 'index'])->name('invoice');

    // Create new Invoice (Requires authentication and email verification)
    Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/invoices/store', [InvoiceController::class, 'store'])->name('invoices.store');

    // Show Invoice Details (Requires authentication and email verification)
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');

    // Design Invoice (Requires authentication and email verification)
    Route::get('/invoices/{invoice}/design', [InvoiceController::class, 'design'])->name('invoices.design');

    // Save Invoice Design (Requires authentication and email verification)
    Route::post('/invoices/save-design', [InvoiceController::class, 'saveDesign'])->name('invoices.saveDesign');
});

// Preview Route (No authentication or verification needed here)
Route::post('/invoices/preview', [InvoiceController::class, 'preview'])->name('invoices.preview');

// Send Route (No authentication or verification needed here)
Route::post('/invoice/send', [InvoiceController::class, 'sendInvoice'])->name('invoice.send');

    
//Management
Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/managements/staff', [StaffController::class, 'index'])->name('staff');
    Route::post('/managements/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::delete('/staff/{id}', [StaffController::class, 'destroy'])->name('staff.destroy');
    Route::put('/staff/{id}', [StaffController::class, 'update'])->name('staff.update');
    Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');

    // Corrected Route
    Route::get('/managements/attendance', [AttendanceController::class, 'index'])->name('attendance');
    Route::post('/managements/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::delete('/attendance/{id}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
