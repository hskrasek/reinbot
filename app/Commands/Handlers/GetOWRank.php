<?php namespace App\Commands\Handlers;

use GuzzleHttp;
use GuzzleHttp\Exception\ClientException;
use Spatie\SlashCommand\Attachment;
use Spatie\SlashCommand\AttachmentField;
use Spatie\SlashCommand\Handlers\SignatureHandler;
use Spatie\SlashCommand\Request;
use Spatie\SlashCommand\Response;

class GetOWRank extends SignatureHandler
{
    public $signature = 'reinbot owrank {gamertag : Xbox gamertag of the player}';

    public $description = 'Get the Overwatch rank of a gamertag.';

    /**
     * Handle the given request. Remember that Slack expects a response
     * within three seconds after the slash command was issued. If
     * there is more time needed, dispatch a job.
     *
     * API Response Format:
     * https://ow-api.herokuapp.com/docs/
     *
     * @param Request $request
     *
     * @return \Spatie\SlashCommand\Response
     */
    public function handle(Request $request): Response
    {
        $gamertag = $this->getArgument('gamertag');

        try {
            $response = (new GuzzleHttp\Client())
                ->get("http://ow-api.herokuapp.com/profile/xbl/us/{$gamertag}");
            $body = json_decode($response->getBody(), true);

            return $this->respondToSlack('')
                ->displayResponseToEveryoneOnChannel()
                ->withAttachment(
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
                        ->setThumbUrl($body['competitive']['rank_img'])
                );
        } catch (ClientException $e) {
            return $this->respondToSlack(__('messages.owrank.error'))
                ->displayResponseToUserWhoTypedCommand();
        }
    }
}
