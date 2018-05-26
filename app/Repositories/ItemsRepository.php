<?php declare(strict_types=1);

namespace App\Repositories;

use App\CharacterClass;
use App\InventoryItem;
use App\Lore;
use App\Stat;
use App\StatGroup;
use Illuminate\Support\Collection;

class ItemsRepository
{
    public function getItemByHash(int $hash): InventoryItem
    {
        return tap(InventoryItem::byBungieId($hash)->first(), function (InventoryItem $item) {
            $item->class = CharacterClass::all()->filter(function (CharacterClass $class) use ($item) {
                return data_get($class->json, 'classType') === data_get($item->json, 'classType');
            })->first();
            $item->lore = Lore::byBungieId(data_get($item->json, 'loreHash'))->first();
            $statGroup = StatGroup::byBungieId(data_get($item->json, 'stats.statGroupHash'))->first();
            $item->stats = Collection::make(data_get($statGroup->json, 'scaledStats'))->map(function ($scaledStat) {
                return Stat::byBungieId(data_get($scaledStat, 'statHash'))->first();
            });
        });
    }
}
