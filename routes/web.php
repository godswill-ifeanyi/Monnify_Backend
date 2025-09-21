<?php

use App\Events\MyEvent;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/trigger-event', function () {
    $data = [
        "message" => "Hello",
        "time" => now()->toDateTimeString()
    ];
    broadcast(new MyEvent($data));

    return "Event Sent";
});
