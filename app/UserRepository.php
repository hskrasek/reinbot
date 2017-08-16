<?php namespace App;

use Lisennk\Laravel\SlackWebApi\Exceptions\SlackApiException;
use Lisennk\Laravel\SlackWebApi\SlackApi;

class UserRepository
{
    /**
     * @var \Lisennk\Laravel\SlackWebApi\SlackApi
     */
    private $slackApi;

    public function __construct(SlackApi $slackApi)
    {
        $this->slackApi = $slackApi;
    }

    public function getBySlackId($slackId): User
    {
        return User::firstOrCreate([
            'slack_user_id' => $slackId,
        ], $this->getSlackDataFromAPI($slackId));
    }

    /**
     * Get user details from the Slack Web API
     *
     * @param string $slackId
     *
     * @return array
     */
    protected function getSlackDataFromAPI($slackId)
    {
        try {
            $response = $this->slackApi->execute('users.info', [
                'user' => $slackId
            ]);
        } catch (SlackApiException $e) {
            \Log::error($e->getMessage(), [
                'slack_user_id' => $slackId
            ]);
            $response = [];
        }

        return ['username' => array_get($response, 'user.name'), 'timezone' => array_get($response, 'user.tz')];
    }
}
