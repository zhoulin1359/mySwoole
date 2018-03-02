<?php
/**
 * Created by PhpStorm.
 * User: JeemuZhou
 * Date: 2017/05/14
 * Time: 10:55
 */
$http = new Swoole\Http\Server("0.0.0.0", 9001);
$http->on('request', function ($request, $response) use ($http) {
    // 跨域
    $response->header('Access-Control-Allow-Origin', '*');
    // 设置 MIME 类型
    $response->header('Content-Type', 'text/event-stream');
    // 关闭 Web 缓存，否则消息可能不按先后次序到达
    $response->header('Cache-Control', 'no-cache');

    $redisClient = new Swoole\redis();
    $redisClient->on('message', function ( Swoole\redis $client, $result) use ($response) {
        //var_dump($result);
        if (false === $result) {
            $response->end('data:服务器出现问题'  . "\n\n");
            return;
        }
        if ('message' === $result[0]) {
            $response->write('data:' . $result[2] . "\n\n");
        }
    });
    $redisClient->connect('172.17.0.3', 6379, function ( Swoole\redis $client, $result) use ($http,$response) {
       // var_dump($result);
        if (false === $result){
            $response->end('data:服务器出现问题'  . "\n\n");
            return;
        }
        $client->subscribe('chan1'); //订阅频道
        $http->redisClient = $client;
    });
});

$http->on('close', function (Swoole\Http\Server $server) {
    if (isset($server->redisClient)){
        $server->redisClient->close();
    }

});
$http->start();