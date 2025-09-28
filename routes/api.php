<?php
use App\Models\Card;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/ping', function () {
    return response()->json(['ok' => true, 'time' => now()]);
});
Route::post('/echo', function (Request $request) {
    return response()->json([
        'received' => $request->all(),
        'time' => now()
    ]);
});
Route::get('/cards', function () {
    return Card::all();
});

Route::get('/cards/{id}', function ($id) {
    return Card::findOrFail($id);
});
