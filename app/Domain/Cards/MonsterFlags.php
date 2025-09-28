<?php
// app/Domain/Cards/MonsterFlags.php
namespace App\Domain\Cards;

final readonly class MonsterFlags {
    public function __construct(public int $bits = 0) {}
    public static function fromKinds(MonsterKind ...$kinds): self {
        return new self(array_reduce($kinds, fn($b,$k)=>$b|$k->value, 0));
    }
    public function has(MonsterKind $k): bool { return (bool)($this->bits & $k->value); }
    public function toArray(): array {
        return array_values(array_map(fn($k)=>$k->name,
            array_filter(MonsterKind::cases(), fn($k)=>$this->has($k))));
    }
}

