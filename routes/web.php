<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

// Home page sends the user to the customer list
Route::redirect('/', '/customers');

// All customers
Route::get('/customers', [CustomerController::class, 'index'])
    ->name('customers.index');

// Filtered customers (gender, born after 2000, custom date range)
// e.g. /customers/filter?gender=Female&birthday=custom&from=1990-01-01&to=1999-12-31
Route::get('/customers/filter', [CustomerController::class, 'filter'])
    ->name('customers.filter');
