<?php

namespace App\Console\Commands;

use App\Token;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Console\Command;

class RefreshTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'destiny:refresh-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh Destiny API access tokens to utilize protected routes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** @var Token $token */
        $token = Token::latest('expires_in')->first();

        if (null === $token) {
            $this->error('No tokens currently exist. Manually seed with a generated token first.');

            return 1;
        }

        try {
            $response = (new Client())->post('https://www.bungie.net/platform/app/oauth/token/', [
                'form_params' => [
                    'grant_type'    => 'refresh_token',
                    'refresh_token' => $token->refresh_token,
                    'client_id'     => config('services.destiny.client_id'),
                    'client_secret' => config('services.destiny.client_secret'),
                ],
            ]);
        } catch (BadResponseException $exception) {
            $response = $exception->getResponse();
            $this->error('There was an issue refreshing the access token.');
            logger()->error('There was an issue refreshing the access token', [
                'token'                => $token->toArray(),
                'response_status_code' => $response->getStatusCode(),
                'response_reason'      => $response->getReasonPhrase(),
                'response_data'        => json_decode((string)$response->getBody(), true),
                'request_data'         => json_decode((string)$exception->getRequest()->getBody(), true),
            ]);

            return 1;
        }

        $response = json_decode((string)$response->getBody(), true);

        $newToken = Token::create([
            'access_token'       => array_get($response, 'access_token'),
            'refresh_token'      => array_get($response, 'refresh_token'),
            'type'               => array_get($response, 'token_type'),
            'expires_in'         => Carbon::now()->addSecond(array_get($response, 'expires_in')),
            'refresh_expires_in' => Carbon::now()->addSecond(array_get($response, 'refresh_expires_in')),
        ]);

        $this->info('Stored new access token: ' . $newToken->access_token);

        return 0;
    }
}
