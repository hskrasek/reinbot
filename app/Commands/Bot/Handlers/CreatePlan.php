<?php namespace App\Commands\Bot\Handlers;

use App\Commands\Action;
use App\Commands\Attachment;
use App\PlanRepository;
use App\RsvpRepository;
use App\User;
use App\UserRepository;
use Carbon\Carbon;
use Mpociot\BotMan\BotMan;

class CreatePlan
{
    /**
     * @var \App\UserRepository
     */
    private $users;

    /**
     * @var \App\PlanRepository
     */
    private $plans;

    public function __construct()
    {
        $this->users = app(UserRepository::class);
        $this->plans = app(PlanRepository::class);
    }

    public function handle(BotMan $bot, $time)
    {
        $user = $this->users->getBySlackId($bot->getUser()->getId());

        if ($this->timeIsInThePast($user, $time)) {
            $bot->reply('You may not create a plan in the past');

            return;
        }

        if ($plan = $this->plans->findPlanAtRequestedTime($user, $time)) {
            $bot->ask('A plan already exists at that time. Would you like to rsvp? (yes or no)', [
                [
                    'pattern'  => 'yes|yep|sure',
                    'callback' => function () use ($bot, $user, $plan) {
                        $rsvp = app(RsvpRepository::class)->getRsvpForUser($user, $plan);

                        if ($rsvp->response && $rsvp->exists) {
                            $bot->reply(__('messages.errors.plans.already_rsvp'));

                            return;
                        }

                        $rsvp = app(RsvpRepository::class)->rsvpUserToPlan($rsvp, true);

                        $bot->sendRequest('chat.postMessage', [
                            'channel' => $bot->getMessage()->getChannel(),
                            'text'    => __('messages.plans.rsvp', [
                                'user_id' => $user->slack_user_id,
                                'mention' => $user->username,
                                'rsvp'    => $rsvp->response ? 'Going' : 'Can\'t go',
                            ]),
                        ]);

                        return;
                    },
                ],
                [
                    'pattern'  => 'no|nope|nah',
                    'callback' => function () use ($bot, $user, $plan) {
                        $rsvp = app(RsvpRepository::class)->getRsvpForUser($user, $plan);

                        if (!$rsvp->response && $rsvp->exists) {
                            $bot->reply(__('messages.errors.plans.already_rsvp'));

                            return;
                        }

                        $rsvp = app(RsvpRepository::class)->rsvpUserToPlan($rsvp, false);

                        $bot->sendRequest('chat.postMessage', [
                            'channel' => $bot->getMessage()->getChannel(),
                            'text'    => __('messages.plans.rsvp', [
                                'user_id' => $user->slack_user_id,
                                'mention' => $user->username,
                                'rsvp'    => $rsvp->response ? 'Going' : 'Can\'t go',
                            ]),
                        ]);

                        return;
                    },
                ],
            ]);

            return;
        }

        $plan = $this->plans->createPlan($user, $time, '');

        /** @var \Symfony\Component\HttpFoundation\Response $response */
        $response = $bot->sendRequest('chat.postMessage', [
            'channel'     => $bot->getMessage()->getChannel(),
            'text'        => __('messages.plans.text', [
                'user_id'        => $user->slack_user_id,
                'mention'        => $user->username,
                'time'           => $plan->scheduled_at->timestamp,
                'time_formatted' => $plan->scheduled_at->toCookieString(),
            ]),
            'attachments' => json_encode([
                Attachment::create()
                          ->setText(__('messages.plans.attachment_text'))
                          ->setFallback(__('messages.plans.attachment_text'))
                          ->setColor('#5d5956')
                          ->setCallbackId("plans-{$plan->id}")
                          ->addAction(
                              Action::create('rsvp', __('messages.plans.rsvp_yes'), Action::TYPE_BUTTON)
                                    ->setStyle(Action::STYLE_PRIMARY)
                                    ->setValue(1)
                          )
                          ->addAction(
                              Action::create('rsvp', __('messages.plans.rsvp_no'), Action::TYPE_BUTTON)
                                    ->setStyle(Action::STYLE_DANGER)
                                    ->setValue(0)
                          )->toArray(),
            ]),
        ]);

        return;
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
