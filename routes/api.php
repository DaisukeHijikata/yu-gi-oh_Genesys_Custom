<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CardController;

Route::get('/ping', function () {
    return response()->json(['ok' => true, 'time' => now()]);
});
Route::post('/echo', function (Request $request) {
    return response()->json([
        'received' => $request->all(),
        'time' => now()
    ]);
});
Route::get('/cards', [CardController::class, 'index']);
Route::get('/cards/{id}', [CardController::class, 'show'])->whereNumber('id');
