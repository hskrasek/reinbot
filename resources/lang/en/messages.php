<?php

return [
    'plans'  => [
        'text'            => '<!everyone>, <@:user_id|:mention> started a plan <!date^:time^{date_pretty} at {time}|:time_formatted>.',
        'attachment_text' => 'Group up with me!',
        'rsvp_yes'        => 'Let\'s FIGHT!',
        'rsvp_no'         => 'Me? NEVER!',
        'rsvp'            => '<@:user_id|:mention> responded :rsvp',
        'hour_reminder'   => '<!everyone>! I\'m escorting the payload in 1 hour.',
        'reminder'        => '<!everyone>! Moving the payload, join me.',
    ],
    'errors' => [
        'plans' => [
            'yes_no'       => 'You must respond with a `yes` or `no` answer',
            'no_plan'      => 'No up coming plans, try creating one with `/reinbot plans`',
            'already_rsvp' => 'You\'ve already RSVPed to this plan',
        ]
    ]
];