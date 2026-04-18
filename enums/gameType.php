<?php

namespace Enums;

// DO: rename to GameType
enum GameType: int
{
    case DICE = 1;
    case COIN_FLIP = 2;
    case SLOTS = 3;
    case BLACK_JACK = 4;
}
