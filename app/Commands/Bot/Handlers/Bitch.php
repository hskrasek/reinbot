<?php namespace App\Commands\Bot\Handlers;

use App\User;
use Mpociot\BotMan\BotMan;

class Bitch
{
    public function handle(BotMan $bot)
    {
        $user = User::whereUsername('tunavi')->first();
        $bot->sendRequest('chat.postMessage', [
            'channel' => $bot->getMessage()->getChannel(),
            'text'    => "<@{$user->slack_user_id}|{$user->username}> is the bitch",
        ]);
    }
}
