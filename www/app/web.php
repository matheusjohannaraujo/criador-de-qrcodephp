<?php

use Lib\Route;

Route::any("/qrcode/create", "QRCodeController@create")::name("qrcode.create");

Route::any("/qrcode/read", "QRCodeController@read")::name("qrcode.read");

Route::any("/", "home");

Route::on();
