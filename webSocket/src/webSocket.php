<?php

/**
 * Created by PhpStorm.
 * User: JeemuZhou
 * Date: 2017/5/6
 * Time: 16:16
 */
class webSocket
{
    public function onOpen(Swoole\websocket\Server $server, Swoole\Http\Request $request)
    {
        //定时器
        /* $server->timeId = $server->tick(1000, function() use ($server, $request) {
             $server->push($request->fd, "hello world");
         });*/
        $server->push($request->fd, "hello world");
        foreach ($server->connections as $value){
            if ($request->fd === $value){
                continue;
            }
            $server->push($value, $request->fd . '加入了大家庭');
        }
    }

    public function onMessage(Swoole\Websocket\server $server, Swoole\Websocket\Frame $frame)
    {
        var_dump($frame);

        $server->push($frame->fd, time());
    }


    public function onClose(Swoole\Websocket\server $server, $fd)
    {
        /* var_dump($server->timeId);
         $server->clearTimer($server->timeId);
         var_dump($fd);*/
        foreach ($server->connections as $value) {
            $server->push($value, $fd . '离开了大家庭');
        }
    }
}