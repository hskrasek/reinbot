<?php namespace App\Commands\Bot\Handlers;

use App\Plan;
use Mpociot\BotMan\BotMan;
use Spatie\SlashCommand\Attachment;

class Debug
{
    public function handle(BotMan $bot)
    {
        $plans = Plan::orderByDesc('scheduled_at')->with('user')->limit(5)->get();
        $bot->sendRequest('chat.postMessage', [
            'channel'     => $bot->getMessage()->getChannel(),
            'text'        => "Here is your debug information",
            'attachments' => $plans->map(function (Plan $plan) {
                return Attachment::create()
                    ->setTitle("Plan $plan->id")
                    ->setAuthorName($plan->user->username)
                    ->setTimestamp($plan->scheduled_at)
                    ->toArray();
            })->toArray(),
        ]);
    }
}
