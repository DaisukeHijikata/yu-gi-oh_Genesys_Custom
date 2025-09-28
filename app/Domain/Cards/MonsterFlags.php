<?php
// app/Domain/Cards/MonsterFlags.php
namespace App\Domain\Cards;

final readonly class MonsterFlags implements \JsonSerializable
{
    public function __construct(public readonly int $bits) {}
    /** @return MonsterKind[] */
    public function kinds(): array
    {
        $result = [];
        foreach (MonsterKind::cases() as $kind) {
            if (($this->bits & $kind->value) === $kind->value) {
                $result[] = $kind;
            }
        }
        return $result;
    }
    public function has(MonsterKind $k): bool { return (bool)($this->bits & $k->value); }
    public function toArray(): array {
        return array_values(array_map(fn($k)=>$k->name,
            array_filter(MonsterKind::cases(), fn($k)=>$this->has($k))));
    }
    /** @return string[] 日本語ラベル配列 */
    public function labels(): array
    {
        return array_map(fn(MonsterKind $k) => $k->label(), $this->kinds());
    }

    /** API 直返し用：bits と合わせて見せたい場合に便利 */
    public function jsonSerialize(): mixed
    {
        return [
            'bits'   => $this->bits,
            'kinds'  => array_map(fn(MonsterKind $k) => $k->name, $this->kinds()),
            'labels' => $this->labels(), // 👈 日本語
        ];
    }
}

