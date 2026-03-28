<?php

namespace Services;

use Helper\JsonStorage;

class HistoryService
{
    private JsonStorage $storage;
    public function __construct(JsonStorage $storage)
    {
        $this->storage = $storage;
    }
    public function logGame($userId, $type, $bet, $isWin, $payout): void // TODO: добавить типизацию параметров
    {
        $history = $this->storage->read();
        $history[] = [
            'userId'   => $userId,
            'gameType' => $type,
            'bet'      => $bet,
            'isWin'    => $isWin,
            'payout'   => $payout,
            'date'     => date('Y-m-d H:i:s')
        ];
        $this->storage->write($history);
    }

    public function showUserHistory(int $userId): void
    {
        $allHistory = $this->storage->read();

        echo "=== Your game history ===" . PHP_EOL;
        echo "Game | Bet | Win | Status" . PHP_EOL;
        echo "---------------------------------" . PHP_EOL;
        $found = false;
        foreach ($allHistory as $record) {
            if (isset($record['userId']) && $record['userId'] === $userId) {
                $status = ($record['isWin'] ?? false) ? "Win" : "Loss";
                $game   = $record['gameType'] ?? "Unknown";
                $payout = $record['payout'] ?? 0;
                $bet    = $record['bet'] ?? 0;

                echo "{$game} | {$bet} | {$payout} | {$status}" . PHP_EOL;
                $found = true;
            }
        }

        if (!$found) {
            echo "You haven't played yet." . PHP_EOL;
        }
        echo "---------------------------------" . PHP_EOL;
    }
}
