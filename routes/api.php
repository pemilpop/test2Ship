<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('/exchange', 'App\Http\Controllers\API\ExchangeController');
