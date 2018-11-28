<?php

declare(strict_types=1);
require_once 'vendor/autoload.php';

use Siler\Swoole;
use Siler\Route;

function fetch_data() {
    return file_get_contents('mock_data.json');
}

function handle_data($res, $data) {
    return function () use ($res, $data) {
        $res->header('Content-Type', 'application/json');
        $res->end($data);
    };
}

function handle() {
    $data = fetch_data();

    return function ($req, $res) use ($data) {
        Route\get('/data', handle_data($res, $data), Swoole\cast($req));
    };
}

Swoole\handle(handle());
Swoole\start('0.0.0.0', 9501);
