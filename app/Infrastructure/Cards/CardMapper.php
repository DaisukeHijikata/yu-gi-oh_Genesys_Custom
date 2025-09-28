<?php
namespace App\Infrastructure\Cards;

use App\Models\Card as CardModel;
use App\Domain\Cards\{
    Card as CardEntity,
    SuperType, MonsterFlags, MonsterKind, MonsterStats,
    SpellType, TrapType
};
use DateTimeImmutable;

final class CardMapper
{
    /** Model → Entity */
    public static function toEntity(CardModel $m): CardEntity
    {
        $super = SuperType::from($m->card_category);

        $monsterFlags = null;
        $monsterStats = null;
        $spellType = null;
        $trapType  = null;

        if ($super === SuperType::Monster) {
            $monsterFlags = new MonsterFlags((int)($m->monster_flags ?? 0));
            $monsterStats = new MonsterStats(
                level: $m->monster_level ? (int)$m->monster_level : null,
                rank : $m->monster_rank  ? (int)$m->monster_rank  : null,
                atk  : $m->attack        !== null ? (int)$m->attack : null,
                def  : $m->defence       !== null ? (int)$m->defence: null,
            );
        } elseif ($super === SuperType::Spell) {
            // DBはenum or varchar、null許容
            $spellType = $m->spell_type ? SpellType::from($m->spell_type) : null;
        } elseif ($super === SuperType::Trap) {
            $trapType  = $m->trap_type ? TrapType::from($m->trap_type) : null;
        }

        return new CardEntity(
            id:        (string)$m->id,
            name:      (string)$m->name,
            superType: $super,
            monsterFlags: $monsterFlags,
            monsterStats: $monsterStats,
            spellType: $spellType,
            trapType:  $trapType,
            description: (string)$m->description,
            imageUrl:    $m->image_url ?: null,
            createdAt: new DateTimeImmutable($m->created_at ?? 'now'),
            updatedAt: new DateTimeImmutable($m->updated_at ?? 'now'),
        );
    }

    /** Entity → Model（既存更新 or 新規作成） */
    public static function toModel(CardEntity $e, ?CardModel $m = null): CardModel
    {
        $m ??= new CardModel();

        $m->name          = $e->name;
        $m->card_category = $e->superType->value;
        $m->description   = $e->description;
        $m->image_url     = $e->imageUrl;

        // Monster
        if ($e->superType === SuperType::Monster) {
            $m->monster_flags = $e->monsterFlags?->bits ?? 0;
            $m->monster_level = $e->monsterStats?->level;
            $m->monster_rank  = $e->monsterStats?->rank;
            $m->attack        = $e->monsterStats?->atk;     // 「?」は null を入れる
            $m->defence       = $e->monsterStats?->def;
            $m->spell_type    = null;
            $m->trap_type     = null;
        }
        // Spell
        if ($e->superType === SuperType::Spell) {
            $m->spell_type    = $e->spellType?->value;
            $m->monster_flags = null;
            $m->monster_level = null;
            $m->monster_rank  = null;
            $m->attack        = null;
            $m->defence       = null;
            $m->trap_type     = null;
        }
        // Trap
        if ($e->superType === SuperType::Trap) {
            $m->trap_type     = $e->trapType?->value;
            $m->monster_flags = null;
            $m->monster_level = null;
            $m->monster_rank  = null;
            $m->attack        = null;
            $m->defence       = null;
            $m->spell_type    = null;
        }

        return $m;
    }
}
