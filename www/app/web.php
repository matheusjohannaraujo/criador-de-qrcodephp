<?php

use Lib\Route;

Route::any("/create/json", "QRCodeController@json")::name("create.json");

Route::any("/create/params", "QRCodeController@params")::name("create.params");

Route::any("/", "home");

Route::on();
