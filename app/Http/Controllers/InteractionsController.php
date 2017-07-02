<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InteractionsController extends Controller
{
    public function __invoke(Request $request)
    {
        $payload = json_decode($request->input('payload'), true);

        $rsvp = array_get($payload, 'actions.0.value');

        return [
            'response_type'    => 'ephemeral',
            "replace_original" => false,
            'text'             => $rsvp === 'going' ? 'See you there!' : 'Sorry you can\'t make it',
        ];
    }
}
