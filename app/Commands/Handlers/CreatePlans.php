<?php namespace App\Commands\Handlers;

use App\PlanRepository;
use App\User;
use App\UserRepository;
use Carbon\Carbon;
use Spatie\SlashCommand\Attachment;
use Spatie\SlashCommand\AttachmentAction as Action;
use Spatie\SlashCommand\Handlers\SignatureHandler;
use Spatie\SlashCommand\Request;
use Spatie\SlashCommand\Response;

class CreatePlans extends SignatureHandler
{
    public $signature = 'reinbot plans {time : The time you want the plans to happen at. E.g `9pm`}';

    public $description = 'Creates plans to game together.';

    /**
     * Handle the given request. Remember that Slack expects a response
     * within three seconds after the slash command was issued. If
     * there is more time needed, dispatch a job.
     *
     * @param Request $request
     *
     * @return \Spatie\SlashCommand\Response
     */
    public function handle(Request $request): Response
    {
        $user = app(UserRepository::class)->getBySlackId($request->userId);

        if ($this->timeIsInThePast($user, $this->getArgument('time'))) {
            return $this->respondToSlack('You may not create a plan in the past')
                        ->displayResponseToUserWhoTypedCommand();
        }

        if (app(PlanRepository::class)->findPlanAtRequestedTime($user, $this->getArgument('time'))) {
            return $this->respondToSlack('A plan already exists at that time, use `/rienbot rsvp` to rsvp')
                        ->displayResponseToUserWhoTypedCommand();
        }

        $plan = app(PlanRepository::class)->createPlan($user, $this->getArgument('time'), $request->responseUrl);

        return $this
            ->respondToSlack(__('messages.plans.text', [
                'user_id'        => $user->slack_user_id,
                'mention'        => $user->username,
                'time'           => $plan->scheduled_at->timestamp,
                'time_formatted' => $plan->scheduled_at->toCookieString(),
            ]))
            ->displayResponseToEveryoneOnChannel()
            ->withAttachment(
                Attachment::create()
                          ->setText(__('messages.plans.attachment_text'))
                          ->setFallback(__('messages.plans.attachment_text'))
                          ->setColor('#5d5956')
                          ->setCallbackId("plans-{$plan->id}")
                          ->addAction(
                              Action::create('rsvp', __('messages.plans.rsvp_yes'), 'button')
                                    ->setStyle(Action::STYLE_PRIMARY)
                                    ->setValue(1)
                          )
                          ->addAction(
                              Action::create('rsvp', __('messages.plans.rsvp_no'), 'button')
                                    ->setStyle(Action::STYLE_DANGER)
                                    ->setValue(0)
                          )
            );
    }

    /**
     * Determine if the requested plan time is in the past.
     *
     * @param \App\User $user
     * @param string    $time
     *
     * @return bool
     */
    private function timeIsInThePast(User $user, string $time): bool
    {
        $time = Carbon::parse($time, $user->timezone);

        $time->timezone = 'UTC';

        return $time->isPast();
    }
}
