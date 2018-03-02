<?php
/**
 * Created by PhpStorm.
 * User: JeemuZhou
 * Date: 2017/5/6
 * Time: 16:28
 */
include_once './src/webSocket.php';
$server = new Swoole\Websocket\Server("0.0.0.0", 9001);

$handle = new webSocket();
$server->on('open',[$handle,'onOpen']);

$server->on('Message',[$handle,'onMessage']);

$server->on('Close',[$handle,'onClose']);

$server -> start();