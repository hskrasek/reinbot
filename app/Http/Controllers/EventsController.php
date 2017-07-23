<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mpociot\BotMan\BotMan;

class EventsController extends Controller
{
    public function __invoke(Request $request)
    {
        /** @var BotMan $botMan */
        $botMan = app('botman');
        \Log::debug('request', $request->all());

        $botMan->hears('<@U6A8LC5L6> start a plan for {time}', function (BotMan $botMan, $time) {
            $botMan->types()->reply('Creating a plan for ' . $time);
        });

        $botMan->listen();

        return [
            'challenge' => $request->challenge,
        ];
    }
}
