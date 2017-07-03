<?php

namespace App\Http\Controllers;

use App\PlanRepository;
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

    public function __construct(UserRepository $users, PlanRepository $plans, Translator $trans)
    {
        $this->users = $users;
        $this->plans = $plans;
        $this->trans = $trans;
    }

    public function __invoke(Request $request)
    {
        $payload = json_decode($request->input('payload'), true);

        $user = $this->users->getBySlackId(array_get($payload, 'user.id'));
        $plan = $this->plans->getById(explode('-', array_get($payload, 'callback_id'))[1]);

        $response = (bool) array_get($payload, 'actions.0.value');

        /** @var \App\Rsvp $rsvp */
        if (!$rsvp = $plan->rsvps()->where('user_id', $user->id)->first()) {
            $rsvp = $plan->rsvps()->create(['user_id' => $user->id]);
        }

        $rsvp->where(['user_id' => $user->id, 'plan_id' => $plan->id])->update(['response' => $response]);

        return [
            'response_type'    => 'in_channel',
            'replace_original' => false,
            'text'             => $this->trans->trans('messages.plan_rsvp', [
                'user_id' => $user->slack_user_id,
                'mention' => $user->username,
                'rsvp'    => $rsvp->response ? 'Going' : 'Can\'t go'
            ]),
        ];
    }
}
