<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('commands', function (Request $request) {
    \Log::debug('command.data', $request->input());

    return [
        'response_type' => 'in_channel',
        'text'          => "@{$request['user_name']} started a plan {$request['text']}",
        'attachments'   => [
            [
                "text"            => "Can you attend?",
                "fallback"        => "Shame you can't attend",
                "callback_id"     => "plan-1234",
                "color"           => "#3AA3E3",
                "attachment_type" => "default",
                "actions"         => [
                    [
                        "name"  => "rsvp",
                        "text"  => "I'm going",
                        "style"   => "primary",
                        "type"  => "button",
                        "value" => "going"
                    ],
                    [
                        "name"  => "rsvp",
                        "text"  => "Maybe",
                        "type"  => "button",
                        "value" => "maybe"
                    ],
                    [
                        "name"    => "rsvp",
                        "text"    => "Can't go",
                        "style"   => "danger",
                        "type"    => "button",
                        "value"   => "cant",
                    ]
                ]
            ]
        ],
    ];
});

Route::post('interactions', function (Request $request) {
    \Log::debug('interaction.data', $request->input());

    return '';
});