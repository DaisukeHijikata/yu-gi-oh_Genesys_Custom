<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Card as CardModel;
use App\Domain\Cards\{
    Card,
    SuperType,
    SpellType,
    TrapType,
    MonsterFlags
    // MonsterStats があるなら use を追加
};
use DateTimeImmutable;

class CardService
{
    /**
     * 一覧取得：Domain Entity の配列を返す
     * @return Card[]
     */
    public function list(): array
    {
        $models = CardModel::query()
            ->orderBy('id', 'asc')
            ->get();

        return $models
            ->map(fn(CardModel $m) => $this->toDomain($m))
            ->all();
    }

    /**
     * 単体取得：見つからなければ 404 を投げる
     */
    public function find(int|string $id): Card
    {
        /** @var CardModel $m */
        $m = CardModel::query()->findOrFail($id);
        return $this->toDomain($m);
    }

    /**
     * Model → Domain 変換（Assembler 的役割）
     */

    private function toDomain(CardModel $m): Card
    {
        $super = SuperType::from((string)$m->getAttribute('card_category'));

        // monster_flags は Cast で MonsterFlags になっている想定
        $flagsAttr = $m->getAttribute('monster_flags');
        $flags = $flagsAttr instanceof MonsterFlags
            ? $flagsAttr
            : new MonsterFlags((int)($flagsAttr ?? 0));

        // MonsterStats を使う場合はここで組み立てる
        $stats = null; // new MonsterStats(...);

        $spell = $m->getAttribute('spell_type');
        $spell = $spell ? SpellType::from((string)$spell) : null;

        $trap = $m->getAttribute('trap_type');
        $trap = $trap ? TrapType::from((string)$trap) : null;

        return new Card(
            id:          (string)$m->getAttribute('id'),
            name:        (string)$m->getAttribute('name'),
            superType:   $super,
            monsterFlags: $super === SuperType::Monster ? $flags : null,
            monsterStats: $super === SuperType::Monster ? $stats : null,
            spellType:   $super === SuperType::Spell   ? $spell : null,
            trapType:    $super === SuperType::Trap    ? $trap  : null,
            description: (string)($m->getAttribute('description') ?? ''),
            imageUrl:    $m->getAttribute('image_url'),
            createdAt:   new DateTimeImmutable((string)$m->getAttribute('created_at')),
            updatedAt:   new DateTimeImmutable((string)$m->getAttribute('updated_at')),
        );
    }
}
