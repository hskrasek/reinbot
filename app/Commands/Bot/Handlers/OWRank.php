<?php namespace App\Commands\Bot\Handlers;

use App\Commands\Attachment;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Mpociot\BotMan\BotMan;
use Spatie\SlashCommand\AttachmentField;

class OWRank
{
    public function handle(BotMan $bot, string $gamertag)
    {
        try {
            $response = (new Client)->get("http://ow-api.herokuapp.com/profile/xbl/us/{$gamertag}");
            $body     = json_decode($response->getBody(), true);

            $bot->sendRequest('chat.postMessage', [
                'channel'     => $bot->getMessage()->getChannel(),
                'text'        => '',
                'attachments' => json_encode([
                    Attachment::create()
                              ->setAuthorName($gamertag)
                              ->setAuthorIcon($body['portrait'])
                              ->setTitle(__('messages.owrank.title'))
                              ->setFallback($body['competitive']['rank'])
                              ->setColor('#DE58E6')
                              ->setFields([
                                  AttachmentField::create('SR', $body['competitive']['rank'])
                                                 ->displaySideBySide(),
                                  AttachmentField::create('Games Won', $body['games']['competitive']['won'])
                                                 ->displaySideBySide(),
                              ])
                              ->setThumbUrl($body['competitive']['rank_img'])->toArray(),
                ]),
            ]);
        } catch (ClientException $exception) {
            \Log::debug('error', [
                'error' => $exception->getMessage(),
            ]);
            $bot->reply(__('messages.owrank.error'));
        }
    }
}
