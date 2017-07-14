<?php namespace App\Commands;

class Action
{
    const TYPE_BUTTON = 'button';
    const TYPE_SELECT = 'select';

    const STYLE_DEFAULT = 'default';
    const STYLE_PRIMARY = 'primary';
    const STYLE_DANGER = 'danger';

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $value = '';

    /**
     * @var string
     */
    protected $style = self::STYLE_DEFAULT;

    public static function create($name, $text, $type)
    {
        return new static($name, $text, $type);
    }

    public function __construct(string $name, string $text, string $type)
    {
        $this->name = $name;
        $this->text = $text;
        $this->type = $type;
    }

    /**
     * @param string $name
     *
     * @return Action
     */
    public function setName(string $name): Action
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $text
     *
     * @return Action
     */
    public function setText(string $text): Action
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @param string $type
     *
     * @return Action
     */
    public function setType(string $type): Action
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param string $value
     *
     * @return Action
     */
    public function setValue(string $value): Action
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @param string $style
     *
     * @return Action
     */
    public function setStyle(string $style): Action
    {
        $this->style = $style;

        return $this;
    }

    /**
     * Convert this action to its array representation
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name'  => $this->name,
            'text'  => $this->text,
            'style' => $this->style,
            'type'  => $this->type,
            'value' => $this->value,
        ];
    }
}
