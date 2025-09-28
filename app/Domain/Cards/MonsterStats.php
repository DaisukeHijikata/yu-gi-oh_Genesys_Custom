<?php
// app/Domain/Cards/MonsterStats.php
namespace App\Domain\Cards;

final readonly class MonsterStats {
    public function __construct(
        public ?int $level = null, // 1..12
        public ?int $rank  = null, // 1..13
        public ?int $atk   = null,
        public ?int $def   = null,
    ) {
        if ($this->level && $this->rank) {
            throw new \InvalidArgumentException('Level と Rank は同時に設定不可');
        }
    }
}
