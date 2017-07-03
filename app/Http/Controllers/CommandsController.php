<?php

namespace App\Http\Controllers;

use App\PlanRepository;
use App\UserRepository;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Http\Request;

class CommandsController extends Controller
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
        $user = $this->users->getBySlackId($request->input('user_id'));
        $plan = $this->plans->createPlan($user, $request->input());

        return [
            'response_type' => 'in_channel',
            'text'          => $this->trans->trans('messages.plan_text', [
                'user_id'        => $user->slack_user_id,
                'mention'        => $user->username,
                'time'           => $plan->scheduled_at->timestamp,
                'time_formatted' => $plan->scheduled_at->toCookieString(),
            ]),
            'attachments'   => [
                [
                    "text"            => "Group up with me!",
                    "fallback"        => "Group up with me!",
                    "callback_id"     => "plans-{$plan->id}",
                    "color"           => "#3AA3E3",
                    "attachment_type" => "default",
                    "actions"         => [
                        [
                            "name"  => "rsvp",
                            "text"  => "Let's fight",
                            "style" => "primary",
                            "type"  => "button",
                            "value" => 1
                        ],
                        [
                            "name"  => "rsvp",
                            "text"  => "Me? NEVER!",
                            "style" => "danger",
                            "type"  => "button",
                            "value" => 0
                        ]
                    ]
                ]
            ],
        ];
    }
}
