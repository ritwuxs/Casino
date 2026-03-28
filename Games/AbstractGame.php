<?php

namespace Games;

abstract class AbstractGame
{
    public function __construct(
        protected float $bet
    ) {}
    abstract protected function play(): array;
}
