<?php

namespace Games;

use enums\coinSide;
use enums\GameType;

abstract class AbstractGame
{
    public function __construct(
        protected float $bet,
        protected float $minimalBet,
        protected int $coefficient,
        protected GameType $type
    ) {}

    public function getMinimalBet(): float
    {
        return $this->minimalBet;
    }

    public function getType(): GameType
    {
        return $this->type;
    }

    abstract public function play(): array;
}
