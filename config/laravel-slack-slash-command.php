<?php

return [

    /*
     * Over at Slack you can configure to which url the slack commands must be send.
     * url here. You must specify that. Be sure to leave of the domain name.
     */
    'url' => 'commands',

    /*
     * The token generated by Slack with which to verify if a incoming slash command request is valid.
     */
    'token' => env('SLACK_VERIFICATION_TOKEN'),

    /*
     * The handlers that will process the slash command. We'll call handlers from top to bottom
     * until the first one whose `canHandle` method returns true.
     */
    'handlers' => [
        //add your own handlers here
        App\Commands\Handlers\CreatePlans::class,

        App\Commands\Handlers\RsvpToPlan::class,

        App\Commands\Handlers\GetOWRank::class,

        //this handler will display instructions on how to use the various commands.
        Spatie\SlashCommand\Handlers\Help::class,

        //this handler will respond with a `Could not handle command` message.
        Spatie\SlashCommand\Handlers\CatchAll::class,
    ],
];
