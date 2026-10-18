<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProdutoController;

Route::apiResource('/produtos', ProdutoController::class);
