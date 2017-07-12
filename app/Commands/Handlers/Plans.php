<?php namespace App\Commands\Handlers;

use App\Commands\Action;
use App\Commands\Attachment;
use App\PlanRepository;
use App\UserRepository;
use Spatie\SlashCommand\Handlers\SignatureHandler;
use Spatie\SlashCommand\Request;
use Spatie\SlashCommand\Response;

class Plans extends SignatureHandler
{
    public $signature = 'reinbot plans {time}';

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
        $plan = app(PlanRepository::class)->createPlan($user, $this->getArgument('time'), $request->responseUrl);

        $response = $this
            ->respondToSlack(trans('messages.plan_text', [
                'user_id'        => $user->slack_user_id,
                'mention'        => $user->username,
                'time'           => $plan->scheduled_at->timestamp,
                'time_formatted' => $plan->scheduled_at->toCookieString(),
            ]))
            ->displayResponseToEveryoneOnChannel()
            ->withAttachment(
                Attachment::create()
                          ->setText('Group up with me!')
                          ->setFallback('Group up with me!')
                          ->setColor('#3AA3E3')
                          ->setCallbackId("plans-{$plan->id}")
                          ->addAction(
                              Action::create('rsvp', 'Let\'s FIGHT!', Action::TYPE_BUTTON)
                                    ->setStyle(Action::STYLE_PRIMARY)
                                    ->setValue(1)
                          )
                          ->addAction(
                              Action::create('rsvp', 'Me? NEVER!', Action::TYPE_BUTTON)
                                    ->setStyle(Action::STYLE_DANGER)
                                    ->setValue(0)
                          )
            );

        \Log::debug('response.data', json_decode($response->getIlluminateResponse()->getContent(), true));

        return $response;
    }
}
