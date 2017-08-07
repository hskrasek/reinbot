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
        $botMan->hears(
            '<@U6A8LC5L6> (?>who\'s|whos|who) (?>the|is a) bitch\?*',
            'App\Commands\Bot\Handlers\Bitch@handle'
        );
        $botMan->hears('<@U6A8LC5L6> get overwatch rank for {gamertag}', 'App\Commands\Bot\Handlers\OWRank@handle');
        $botMan->hears('<@U6A8LC5L6> help', function (BotMan $botMan) {
            $botMan->typesAndWaits(2)->reply('I currently am listening to:');
            $botMan->reply('@reinbot start a plan for 9pm');
            $botMan->reply('@reinbot get overwatch rank for R3v3rb');
        });

        $botMan->listen();

        return [
            'challenge' => $request->challenge,
        ];
    }
}
