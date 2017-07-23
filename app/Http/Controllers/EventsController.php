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

        $botMan->hears('<@U6A8LC5L6> start a plan for {time}', 'App\Commands\Bot\Handlers\CreatePlan@handle');

        $botMan->listen();

        return [
            'challenge' => $request->challenge,
        ];
    }
}
