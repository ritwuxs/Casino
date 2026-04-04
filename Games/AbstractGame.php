<?php

namespace Games;

use enums\coinSide;
use enums\gameType;

abstract class AbstractGame
{
    public function __construct(
        protected float $bet,
        protected float $minimalBet,
        protected int $coficient, // TODO: coefficient
        protected gameType $type
    ) {}

    public function getMinimalBet(): float
    {
        return $this->minimalBet;
    }

    public function getType(): gameType
    {
        return $this->type;
    }

    abstract public function play(): array;
}
