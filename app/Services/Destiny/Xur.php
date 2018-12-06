<?php namespace App\Services\Destiny;

use App\InventoryItem;
use Illuminate\Support\Str;

class Xur
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getInventory(): array
    {
        return collect($this->client->getXurInventory())->filter(function ($item) {
            return !empty($item['costs']);
        })->map(function ($item) {
            return tap(
                InventoryItem::byBungieId($item['itemHash'])->first(),
                function (InventoryItem $inventoryItem) use ($item) {
                    $inventoryItem->cost = InventoryItem::byBungieId($item['costs'][0]['itemHash'])->first();
                    $inventoryItem->cost_amount = $item['costs'][0]['quantity'];
                }
            );
        })->transform(function (InventoryItem $item) {
            return $this->transformItemIntoSlackAttachment($item);
        })->toArray();
    }

    private function transformItemIntoSlackAttachment(InventoryItem $item): array
    {
        return [
            'title'      => data_get($item, 'json.displayProperties.name', ''),
            'title_link' => config('app.url') . '/destiny2/items/' . data_get($item, 'json.hash'),
            'text'       => data_get($item, 'json.displayProperties.description', ''),
            'thumb_url'  => 'https://www.bungie.net' . data_get($item, 'json.displayProperties.icon', ''),
            'fields'     => [
                [
                    'title' => 'Cost',
                    'value' => Str::lower($item->cost_amount . ' ' . data_get(
                        $item->cost,
                        'json.displayProperties.name',
                        ''
                    )),
                ],
            ],
            'color'      => $this->getColor(data_get($item, 'json.itemType'), data_get($item, 'json.classType')),
        ];
    }

    private function getColor(int $itemType, ?int $classType = null): string
    {
        if (\in_array($itemType, [0, 8], true)) {
            return '#ffbf00';
        }

        if ($itemType === 9) {
            return '#36454f';
        }

        if ($itemType === 3) {
            return '#C0C0C0';
        }

        switch ($classType) {
            case 0:
                return '#9B0800';
            case 1:
                return '#16449F';
            case 2:
                return '#675002';
            case 3:
            default:
                return '';
        }
    }
}
