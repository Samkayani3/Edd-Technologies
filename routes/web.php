<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\RepairJobController;
use App\Http\Controllers\SparePartSupplierController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TechnicianController;

// Auth Routes
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/', function () {
    return redirect()->route('login');
});


// Customer Routes
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard', [CustomerController::class, 'dashboard'])->name('customer.dashboard');
    Route::post('/equipment', [EquipmentController::class, 'store'])->name('equipment');
    Route::get('/equipment', [EquipmentController::class, 'index'])->name('equipment.index');
    Route::get('/repair-job/{repairJob}', [RepairJobController::class, 'viewCustomerJob'])->name('repair-job.view-customer');
});

// Technician Routes
Route::middleware(['auth', 'role:technician'])->group(function () {
    Route::get('/technician/dashboard', [TechnicianController::class, 'dashboard'])->name('technician.dashboard');
    Route::post('/repair-job/{repairJob}/update-job', [TechnicianController::class, 'updateJob'])->name('repair-job.update-job');
    Route::get('/repair-jobs', [TechnicianController::class, 'listJobs'])->name('repair-job.list-technician');
    Route::get('/repair-job/{repairJob}', [RepairJobController::class, 'viewTechnicianJob'])->name('repair-job.view-technician');
    Route::post('/repair-job/{repairJob}/update-status', [RepairJobController::class, 'updateStatus'])->name('repair-job.update-status');

});


// Admin Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::post('/admin/add-user', [AdminController::class, 'storeUser'])->name('admin.store-user');
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::delete('/admin/delete-user/{id}', [AdminController::class, 'deleteUser'])->name('admin.delete-user');
    Route::get('/admin/manage-technicians', [AdminController::class, 'manageTechnicians'])->name('admin.manage-technicians');
    Route::get('/admin/manage-customers', [AdminController::class, 'manageCustomers'])->name('admin.manage-customers');
    Route::post('/admin/assign-equipment', [AdminController::class, 'assignEquipment'])->name('admin.assign-equipment');
    Route::get('/admin/manage-suppliers', [AdminController::class, 'manageSuppliers'])->name('admin.manage-suppliers');
    Route::post('/spare-part-supplier', [SparePartSupplierController::class, 'store'])->name('spare-part-supplier.store');
    Route::get('/admin/spare-part-suppliers', [SparePartSupplierController::class, 'index'])->name('admin.spare-part-suppliers');
    Route::get('/admin/spare-part-supplier/{id}/edit', [SparePartSupplierController::class, 'edit'])->name('admin.spare-part-supplier.edit');
    Route::put('/admin/spare-part-supplier/{id}', [SparePartSupplierController::class, 'update'])->name('admin.spare-part-supplier.update');
    Route::delete('/admin/spare-part-supplier/{id}', [SparePartSupplierController::class, 'destroy'])->name('admin.spare-part-supplier.destroy');
    Route::post('/admin/assign-equipment', [AdminController::class, 'assignEquipment'])->name('admin.assign-equipment');
Route::post('/admin/update-equipment-status', [AdminController::class, 'updateEquipmentStatus'])->name('admin.update-equipment-status');
Route::post('/admin/update-equipment', [AdminController::class, 'updateEquipment'])->name('admin.update-equipment');
Route::post('/equipmentt', [EquipmentController::class, 'store'])->name('equipmentt');
Route::delete('/admin/delete-equipment/{id}', [AdminController::class, 'deleteEquipment'])->name('admin.delete-equipment');
Route::get('/admin/manage-suppliers', [AdminController::class, 'manageSuppliers'])->name('admin.manage-suppliers');
Route::post('/spare-part-supplier', [SparePartSupplierController::class, 'store'])->name('spare-part-supplier.store');
Route::put('/spare-part-supplier/{id}', [SparePartSupplierController::class, 'update'])->name('spare-part-supplier.update');
Route::delete('/spare-part-supplier/{id}', [SparePartSupplierController::class, 'destroy'])->name('spare-part-supplier.destroy');
Route::post('/repair-job/{id}/generate-receipt', [RepairJobController::class, 'generateReceipt'])->name('repair-job.generate-receipt');
// Add the route for generating the receipt
Route::post('/admin/generate-receipt/{equipmentId}', [AdminController::class, 'generateReceipt'])->name('admin.generate-receipt');
// Route for adding price
Route::post('/admin/add-price/{equipmentId}', [AdminController::class, 'addPrice'])->name('admin.add-price');
Route::get('/admin/download-receipt/{equipment}', [AdminController::class, 'downloadReceipt'])->name('admin.download-receipt');

});

// Common Routes
Route::post('/repair-job', [RepairJobController::class, 'create'])->name('repair-job.create');
Route::post('/repair-job/{repairJob}/update-status', [RepairJobController::class, 'updateStatus'])->name('repair-job.update-status');

Auth::routes(); // This will handle the login, logout, and registration routes automatically

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
