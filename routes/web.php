<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\TwilioVoiceController::class,'index']);
