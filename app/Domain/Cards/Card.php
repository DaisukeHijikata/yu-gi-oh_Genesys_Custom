<?php
// app/Domain/Cards/Card.php
namespace App\Domain\Cards;

use DateTimeImmutable;

final class Card
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly SuperType $superType,
        public readonly ?MonsterFlags $monsterFlags = null,
        public readonly ?MonsterStats $monsterStats = null,
        public readonly ?SpellType $spellType = null,
        public readonly ?TrapType $trapType = null,
        public readonly string $description = '',
        public readonly ?string $imageUrl = null,
        public readonly DateTimeImmutable $createdAt = new DateTimeImmutable(),
        public readonly DateTimeImmutable $updatedAt = new DateTimeImmutable(),
    ) {
        match ($this->superType) {
            SuperType::Monster => $this->assertMonster(),
            SuperType::Spell   => $this->assertSpell(),
            SuperType::Trap    => $this->assertTrap(),
        };
    }

    private function assertMonster(): void
    {
        if ($this->monsterFlags === null || $this->monsterStats === null) {
            throw new \InvalidArgumentException("Monster must have flags and stats.");
        }
    }

    private function assertSpell(): void
    {
        if ($this->spellType === null) {
            throw new \InvalidArgumentException("Spell must have spellType.");
        }
    }

    private function assertTrap(): void
    {
        if ($this->trapType === null) {
            throw new \InvalidArgumentException("Trap must have trapType.");
        }
    }

    // 💡 値を返すメソッドは用意せず、「ドメイン的な問い」だけを提供
    public function isMonster(): bool { return $this->superType === SuperType::Monster; }
    public function isSpell(): bool { return $this->superType === SuperType::Spell; }
    public function isTrap(): bool { return $this->superType === SuperType::Trap; }
}
