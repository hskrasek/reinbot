<?php namespace App\Commands\Handlers;

use App\Commands\Action;
use App\Commands\Attachment;
use App\PlanRepository;
use App\UserRepository;
use Spatie\SlashCommand\Handlers\SignatureHandler;
use Spatie\SlashCommand\Request;
use Spatie\SlashCommand\Response;

class CreatePlans extends SignatureHandler
{
    public $signature = 'reinbot plans {time : The time you want the plans to happen at}';

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

        if (app(PlanRepository::class)->findPlanAtRequestedTime($user, $this->getArgument('time'))) {
            return $this->respondToSlack('A plan already exists at that time, use `/rienbot rsvp` to rsvp')
                        ->displayResponseToUserWhoTypedCommand();
        }

        $plan = app(PlanRepository::class)->createPlan($user, $this->getArgument('time'), $request->responseUrl);

        return $this
            ->respondToSlack(trans('messages.plans.text', [
                'user_id'        => $user->slack_user_id,
                'mention'        => $user->username,
                'time'           => $plan->scheduled_at->timestamp,
                'time_formatted' => $plan->scheduled_at->toCookieString(),
            ]))
            ->displayResponseToEveryoneOnChannel()
            ->withAttachment(
                Attachment::create()
                          ->setText(trans('messages.plans.attachment_text'))
                          ->setFallback(trans('messages.plans.attachment_text'))
                          ->setColor('#3AA3E3')
                          ->setCallbackId("plans-{$plan->id}")
                          ->addAction(
                              Action::create('rsvp', trans('messages.plans.rsvp_yes'), Action::TYPE_BUTTON)
                                    ->setStyle(Action::STYLE_PRIMARY)
                                    ->setValue(1)
                          )
                          ->addAction(
                              Action::create('rsvp', trans('messages.plans.rsvp_no'), Action::TYPE_BUTTON)
                                    ->setStyle(Action::STYLE_DANGER)
                                    ->setValue(0)
                          )
            );
    }
}
