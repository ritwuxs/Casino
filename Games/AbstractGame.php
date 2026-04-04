<?php

namespace Games;

use enums\gameType;

abstract class AbstractGame
{
    public function __construct(
        // DO: добавить minimalBet
        // DO: добавить коэфициент на который умножается
        // DO: добавить gameType (вынести gameType в enums)
        protected float $bet,
        protected float $minimalBet,
        protected int $coficient,
        protected gameType $type
    ) {}
    public function getMinimalBet(): float
    {
        return $this->minimalBet;
    }
    public function getType():gameType{
        return $this->type;
    }
    abstract public function play(): array;
}
