<?php

use Lib\Route;

Route::any("/create", "QRCodeController@create");

Route::any("/", "welcome");

Route::on();
