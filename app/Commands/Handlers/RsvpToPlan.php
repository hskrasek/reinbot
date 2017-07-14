<?php namespace App\Commands\Handlers;

use App\PlanRepository;
use App\RsvpRepository;
use App\UserRepository;
use Spatie\SlashCommand\Handlers\SignatureHandler;
use Spatie\SlashCommand\Request;
use Spatie\SlashCommand\Response;

class RsvpToPlan extends SignatureHandler
{
    const DEFAULT_LIMIT = 5;

    public $signature = 'reinbot rsvp {response : Whether or not you can make it to the plan}';

    public $description = 'RSVP to the next active plan';

    /**
     * Handle the given request. Remember that Slack expects a response
     * within three seconds after the slash command was issued. If
     * there is more time needed, dispatch a job.
     *
     * @param \Spatie\SlashCommand\Request $request
     *
     * @return \Spatie\SlashCommand\Response
     */
    public function handle(Request $request): Response
    {
        $response = strtolower($this->getArgument('response'));

        if (!in_array($response, ['yes', 'no'])) {
            return $this->respondToSlack('You must respond with a `yes` or `no` answer')
                        ->displayResponseToUserWhoTypedCommand();
        }

        $response = $response === 'yes' ? true : false;

        if (!$plan = app(PlanRepository::class)->getNextPlan()) {
            return $this->respondToSlack('No up coming plans, try creating one with `/reinbot plans`')
                        ->displayResponseToUserWhoTypedCommand();

        }

        $user = app(UserRepository::class)->getBySlackId($request->userId);
        $rsvp = app(RsvpRepository::class)->getRsvpForUser($user, $plan);

        if ($rsvp->response === $response && $rsvp->exists) {
            return $this->respondToSlack('You\'ve already RSVPed to this plan')
                        ->displayResponseToUserWhoTypedCommand();
        }

        $rsvp = app(RsvpRepository::class)->rsvpUserToPlan($rsvp, $response);

        return $this->respondToSlack(
            trans('messages.plan_rsvp', [
                'user_id' => $user->slack_user_id,
                'mention' => $user->username,
                'rsvp'    => $rsvp->response ? 'Going' : 'Can\'t go'
            ])
        )->displayResponseToEveryoneOnChannel();
    }
}
