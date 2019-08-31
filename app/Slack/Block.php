<?php declare(strict_types=1);

namespace App\Slack;

use Illuminate\Support\Fluent;

/**
 * @property-read string $type
 * @property-read array $text
 * @property-read array $accessory
 */
class Block extends Fluent
{
    private const REQUIRED = [
        'type',
    ];

    public function __construct($attributes = [])
    {
        foreach (self::REQUIRED as $requiredKey) {
            if (!array_key_exists($requiredKey, $attributes)) {
                throw new \InvalidArgumentException('Missing required key: ' . $requiredKey);
            }
        }
        parent::__construct($attributes);
    }

    public static function section(string $text): Block
    {
        return new self([
            'type' => 'section',
            'text' => [
                'type' => 'mrkdwn',
                'text' => $text,
            ],
        ]);
    }

    public static function sectionWithImage(string $text, Accessory $accessory): Block
    {
        return new self([
            'type'      => 'section',
            'text'      => [
                'type' => 'mrkdwn',
                'text' => $text,
            ],
            'accessory' => $accessory,
        ]);
    }

    public static function divider(): Block
    {
        return new self([
            'type' => 'divider',
        ]);
    }
}
