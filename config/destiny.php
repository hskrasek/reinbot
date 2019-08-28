<?php declare(strict_types=1);

use App\Slack\Builder\{FlashpointBuilder, NightfallBuilder, ReckoningBuilder, WeeklyStrikeBurnsBuilder};

return [
    'milestones' => [
        '2171429505' => NightfallBuilder::class,
        '2853331463' => false, //'Nightfall Score'
        '463010297'  => FlashpointBuilder::class,
        '3660836525' => false, //'Leviathan Raid'
        '2683538554' => false,//'Spire of Stars'
        '4253138191' => false,//'Clan XP'
        '3603098564' => false,//'5000 clan XP'
        '536115997' => false,//'Guardian for all'
        '2188900244' => false,//'Black Armory'
        '1342567285' => false,//'Scourge of the Past Raid'
        '2986584050' => false,//'Eater of Worlds Raid'
        '4128810957' => false,//'Eva Levante (Dawning)'
        '3177730289' => false,//'Season of Giving'
        '2958665367' => false,//'Festival of the Lost'
        '3915793660' => false,//'Revelry'
        '291994631' => false,//'Eververse'
        '3082135827' => false,//'Heroic Story Missions'
        '3312018120' => false,//'Daily Crucible'
        '1300394968' => false,//'Heroic Adventure'
        '157823523'  => false,//'Basic Milestones'
        '3172444947' => false,//'Daily Strike'
        '1437935813' => WeeklyStrikeBurnsBuilder::class,
        '941217864' => false,//'Daily Gambit'
        '3448738070' => false,//'Weekly Gambit'
        '2010672046' => false,//'Weekly Gambit Prime'
        '601087286' => ReckoningBuilder::class,
        '534869653' => false,//Xur
    ],
];
