<?php namespace App\Commands;

use Illuminate\Support\Collection;
use Spatie\SlashCommand\Attachment as SpatieAttachment;

class Attachment extends SpatieAttachment
{
    /**
     * @var string
     */
    protected $callbackId;

    /**
     * @var Collection
     */
    protected $actions;

    public function __construct()
    {
        parent::__construct();

        $this->actions = new Collection;
    }

    /**
     * Add an action to the attachment.
     *
     * @param Action $action
     *
     * @return self
     */
    public function addAction(Action $action): self
    {
        $this->actions->push($action);

        return $this;
    }

    /**
     * @param array $actions
     *
     * @return self
     */
    public function addActions(array $actions): self
    {
        collect($actions)->each(function ($action, $_) {
            $this->addAction($action);
        });

        return $this;
    }

    /**
     * Set the actions for the attachment.
     *
     * @param array $actions
     *
     * @return self
     */
    public function setActions(array $actions): self
    {
        $this->clearActions();

        $this->addActions($actions);

        return $this;
    }

    /**
     * Clear all actions for this attachment.
     *
     * @return self
     */
    public function clearActions(): self
    {
        $this->actions = new Collection;

        return $this;
    }

    /**
     * @param string $callbackId
     *
     * @return self
     */
    public function setCallbackId(string $callbackId): self
    {
        $this->callbackId = $callbackId;

        return $this;
    }

    /**
     * Convert this attachment to its array representation.
     *
     * @return array
     */
    public function toArray()
    {
        return parent::toArray() + [
                'callback_id' => $this->callbackId,
                'actions'     => $this->actions->map(function (Action $action) {
                    return $action->toArray();
                })->toArray(),
            ];
    }
}
