<?php

namespace App\Events;

use App\Common\CrudActions;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Class CartItemChangedEvent
 * @package App\Events
 */
class CartItemChangedEvent
{
    use Dispatchable, SerializesModels;

    /**
     * @var int
     */
    private int $id;

    /**
     * @var string
     */
    private string $action;

    /**
     * CartItemChangedEvent constructor.
     *
     * @param int    $id
     * @param string $action
     */
    public function __construct(int $id, string $action = CrudActions::ACTION_ADDED)
    {
        $this->id     = $id;
        $this->action = $action;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }
}
