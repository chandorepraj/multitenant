<?php

use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ReportController;


Route::middleware('guest')->group(function () {
    Route::get('/', function () {
    return view('auth.login'); // Displays the login view directly if not logged in
    });
    Route::get(
    '/register/invitation/{token}',
    [InvitationController::class, 'register'])->name('invitation.register');
    Route::post('/invitation/accept/{token}', [InvitationController::class, 'accept_invitation'])
    ->name('invitation.accept');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/tenants/select', [TenantController::class, 'select'])
    ->name('tenants.select');
    Route::post('/users/tenant', [UserController::class, 'update_tenant'])
    ->name('users.tenant');

});  

Route::middleware(['auth', 'verified','tenant'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('tenants', TenantController::class);
    Route::resource('invitations',InvitationController::class);
    Route::resource('users',UserController::class);
    Route::resource('customers',CustomerController::class);
  
    Route::post('/users/update_role', [UserController::class, 'update_role'])
    ->name('users.update_role');  
    Route::post('/users/team_delete/{id}', [UserController::class, 'team_delete'])
    ->name('users.team_delete');
    Route::get('/leads/convert/{id}', [LeadController::class, 'convert'])
    ->name('leads.convert');
    Route::post('/leads/conversion_save', [LeadController::class, 'conversion_save'])
    ->name('lead.conversion_save');
    Route::resource('leads',LeadController::class);
    Route::resource('customers.notes', NoteController::class);
    Route::resource('leads.notes', NoteController::class);
    Route::resource('customers.tasks', TaskController::class);
    Route::resource('leads.tasks', TaskController::class);
    Route::get('/activitylog', [ActivityLogController::class, 'index'])->name('activitylog');
    Route::get('/tasks/my', [TaskController::class, 'my_tasks'])
    ->name('tasks.my');
    Route::get('/search', [SearchController::class, 'index'])->name('search');
    Route::get('/reports/leads', [ReportController::class, 'leads'])
    ->name('reports.leads');
    Route::get('/reports/leads/export/csv', [ReportController::class, 'exportLeadCsv'])
    ->name('reports.leads.csv');
    Route::get('/reports/leads/export/pdf', [ReportController::class, 'exportLeadPdf'])
    ->name('reports.leads.pdf');
    Route::get('/reports/customers', [ReportController::class, 'customers'])
    ->name('reports.customers');
    Route::get('/reports/customers/export/csv', [ReportController::class, 'exportCustomerCsv'])
    ->name('reports.customers.csv');
    Route::get('/reports/customers/export/pdf', [ReportController::class, 'exportCustomerPdf'])
    ->name('reports.customers.pdf');
});  
  
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
