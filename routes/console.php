<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Collection;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('raid', function () {
    $gamertags = collect([
        'HEATSEEKERBUNGE',
        'Tawpgun',
        'ToviTTCSNoname',
        'Rev3rb',
        'EvilAxel 1346',
        'WizzleGTO',
        'Chaos Krys',
        'CondemnedFawn7',
        'Awful Waffle96',
        'Oml3t',
        'Mr BlueberryJam',
    ]);

    $classes = [
        2271682572 => 'Warlock',
        3655393761 => 'Titan',
        671679327  => 'Hunter',
    ];

    /** @var \App\Services\Destiny\Client $client */
    $client = app(\App\Services\Destiny\Client::class);
    $gamertags->map(function ($gamertag) use ($client) {
        return data_get($client->get("/Platform/Destiny2/SearchDestinyPlayer/1/{$gamertag}/"), '0');
    })->mapToGroups(function ($memberships) use ($client) {
        $characters = array_get(
            $client->get("/Platform/Destiny2/1/Profile/{$memberships['membershipId']}/", [200]),
            'characters.data'
        );

        return [
            $memberships['displayName'] => $characters,
        ];
    })->map(function (Collection $characters) {
        return collect($characters->first());
    })->map(function (Collection $characters) {
        return $characters->sortByDesc(function ($character) {
            return array_get($character, 'light');
        })->first();
    })->sortByDesc('light')->map(function (array $character, string $gamertag) use ($classes) {
        return "{$gamertag},{$classes[$character['classHash']]},{$character['light']}";
    })->each(function ($line) {
        \Illuminate\Support\Facades\Storage::append('status.csv', $line);
    });
});
