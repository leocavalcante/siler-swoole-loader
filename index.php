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

function handle_token($res, string $token) {
    return function () use ($res, $token) {
        $res->end($token);
    };
}

function handle() {
    $config = parse_ini_file('.env');
    $loaderIoToken = $config['LOADER_IO'];
    $data = fetch_data();

    return function ($req, $res) use ($loaderIoToken, $data) {
        Route\get("/$loaderIoToken", handle_token($res, $loaderIoToken), Swoole\cast($req));
        Route\get('/data', handle_data($res, $data), Swoole\cast($req));
    };
}

Swoole\handle(handle());
Swoole\start('0.0.0.0', 9501);
