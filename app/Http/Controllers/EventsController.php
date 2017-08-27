<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Mpociot\BotMan\BotMan;
use Mpociot\BotMan\Middleware\Wit;

class EventsController extends Controller
{
    public function __invoke(Request $request)
    {
        /** @var BotMan $botMan */
        $botMan = app('botman');

        $botMan->hears('<@U6JUKRTE0> start a plan for {time}', 'App\Commands\Bot\Handlers\CreatePlan@handle');
        $botMan->hears(
            '<@U6JUKRTE0> (?>who\'s|whos|who) (?>the|is a) bitch\?*',
            'App\Commands\Bot\Handlers\Bitch@handle'
        );
        $botMan->hears('<@U6JUKRTE0> get overwatch rank for {gamertag}', 'App\Commands\Bot\Handlers\OWRank@handle');
        $botMan->hears('<@U6JUKRTE0> help', function (BotMan $botMan) {
            $botMan->typesAndWaits(2)->reply('I currently am listening to:');
            $botMan->reply('@reinbot start a plan for 9pm');
            $botMan->reply('@reinbot get overwatch rank for Rev3rb');
        });
        $botMan->hears('<@U6JUKRTE0> debug', 'App\Commands\Bot\Handlers\Debug@handle');
//        $botMan->hears('create_plan', function (BotMan $botMan) {
//            $entities = $botMan->getMessage()->getExtras('entities');
//            $botMan->reply('Received entities: ' . json_encode($entities));
////            $planTime = Carbon::parse($entities['datetime']['value']);
//
////            $botMan->reply('You wanted to create a plan for ' . (string) $planTime);
//        })->middleware(Wit::create(env('WIT_AI_TOKEN')));

        $botMan->listen();

        return [
            'challenge' => $request->challenge,
        ];
    }
}
