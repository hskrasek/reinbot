<?php

namespace App\Http\Controllers;

use App\Jobs\UpdatePlanMessage;
use App\PlanRepository;
use App\RsvpRepository;
use App\UserRepository;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Http\Request;

class InteractionsController extends Controller
{
    /**
     * @var \App\UserRepository
     */
    private $users;

    /**
     * @var \App\PlanRepository
     */
    private $plans;

    /**
     * @var \Illuminate\Contracts\Translation\Translator
     */
    private $trans;

    /**
     * @var \App\RsvpRepository
     */
    private $rsvps;

    public function __construct(UserRepository $users, PlanRepository $plans, RsvpRepository $rsvps, Translator $trans)
    {
        $this->users = $users;
        $this->plans = $plans;
        $this->trans = $trans;
        $this->rsvps = $rsvps;
    }

    public function __invoke(Request $request)
    {
        $payload  = json_decode($request->input('payload'), true);
        $response = (bool) array_get($payload, 'actions.0.value');

        /** @var \App\User $user */
        $user = $this->users->getBySlackId(array_get($payload, 'user.id'));
        $plan = $this->plans->getById(explode('-', array_get($payload, 'callback_id'))[1]);

        if ($plan->hasStarted()) {
            return [
                'response_type'    => 'ephemeral',
                'replace_original' => false,
                'text'             => __('messages.errors.plans.plan_started'),
            ];
        }

        $rsvp = $this->rsvps->getRsvpForUser($user, $plan);

        if ($rsvp->response === $response && $rsvp->exists) {
            return [
                'response_type'    => 'ephemeral',
                'replace_original' => false,
                'text'             => __('messages.errors.plans.already_rsvp'),
            ];
        }

        $rsvp = $this->rsvps->rsvpUserToPlan($rsvp, $response);

        dispatch(new UpdatePlanMessage($payload, $plan));

        return [
            'response_type'    => 'in_channel',
            'replace_original' => false,
            'text'             => $this->trans->trans('messages.plans.rsvp', [
                'user_id' => $user->slack_user_id,
                'mention' => $user->username,
                'rsvp'    => $rsvp->response ? 'Going' : 'Can\'t go',
            ]),
        ];
    }
}
