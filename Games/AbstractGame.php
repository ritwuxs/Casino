<?php

namespace Games;

abstract class AbstractGame
{
    public function __construct( 
        // TODO: добавить minimalBet
        // TODO: добавить коэфициент на который умножается
        // TODO: добавить gameType (вынести gameType в enums)
        protected float $bet
    ) {}
    abstract protected function play(): array;
}
