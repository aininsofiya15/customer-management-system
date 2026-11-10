<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

// Home page sends the user to the customer list
Route::redirect('/', '/customers');

// All customers
Route::get('/customers', [CustomerController::class, 'index'])
    ->name('customers.index');

// Filtered customers (gender and/or born after 2000)
// e.g. /customers/filter?gender=Female&birthday=after2000
Route::get('/customers/filter', [CustomerController::class, 'filter'])
    ->name('customers.filter');
