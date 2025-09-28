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

    // --- 日本語ラベル（VO/Enumに委譲） ---
    public function superTypeLabel(): string
    {
        return $this->superType->label();
    }
    public function monsterKindLabels(): array
    {
        return $this->monsterFlags?->labels() ?? [];
    }
    public function spellTypeLabel(): ?string
    {
        return $this->spellType?->label();
    }
    public function trapTypeLabel(): ?string
    {
        return $this->trapType?->label();
    }

    /** API用の素直な配列（英語値＋日本語ラベルを同梱） */
    public function toApiArray(): array
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'super_type'      => $this->superType->value,
            'super_type_jp'   => $this->superTypeLabel(),
            'monster' => $this->isMonster() ? [
                'flags_bits' => $this->monsterFlags?->bits,
                'kinds_jp'   => $this->monsterKindLabels(),
                'stats'      => $this->monsterStats, // そのまま
            ] : null,
            'spell_type'      => $this->spellType?->value,
            'spell_type_jp'   => $this->spellTypeLabel(),
            'trap_type'       => $this->trapType?->value,
            'trap_type_jp'    => $this->trapTypeLabel(),
            'description'     => $this->description,
            'image_url'       => $this->imageUrl,
            'created_at'      => $this->createdAt->format(DATE_ATOM),
            'updated_at'      => $this->updatedAt->format(DATE_ATOM),
        ];
    }
}
