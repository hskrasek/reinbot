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

    public $signature = 'reinbot rsvp {response : `yes` or `no`, dependant on if you can make it or not}';

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
            return $this->respondToSlack(__('messages.errors.plans.yes_no'))
                        ->displayResponseToUserWhoTypedCommand();
        }

        $response = $response === 'yes' ? true : false;

        if (!$plan = app(PlanRepository::class)->getNextPlan()) {
            return $this->respondToSlack(__('messages.errors.plans.no_plan'))
                        ->displayResponseToUserWhoTypedCommand();
        }

        $user = app(UserRepository::class)->getBySlackId($request->userId);
        $rsvp = app(RsvpRepository::class)->getRsvpForUser($user, $plan);

        if ($rsvp->response === $response && $rsvp->exists) {
            return $this->respondToSlack(__('messages.errors.plans.already_rsvp'))
                        ->displayResponseToUserWhoTypedCommand();
        }

        $rsvp = app(RsvpRepository::class)->rsvpUserToPlan($rsvp, $response);

        return $this->respondToSlack(
            __('messages.plans.rsvp', [
                'user_id' => $user->slack_user_id,
                'mention' => $user->username,
                'rsvp'    => $rsvp->response ? 'Going' : 'Can\'t go'
            ])
        )->displayResponseToEveryoneOnChannel();
    }
}
