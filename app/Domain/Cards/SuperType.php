<?php
// app/Domain/Cards/SuperType.php
namespace App\Domain\Cards;

enum SuperType: string {
    case Monster='monster';
    case Spell='spell';
    case Trap='trap';

    public function label(): string {
        return match($this) {
            self::Monster => 'モンスター',
            self::Spell   => '魔法',
            self::Trap    => '罠',
        };
    }
}

// app/Domain/Cards/MonsterKind.php（ビットフラグ）
namespace App\Domain\Cards;

enum MonsterKind:int {
    case Normal  = 1<<0; // 1
    case Effect  = 1<<1; // 2
    case Ritual  = 1<<2; // 4
    case Fusion  = 1<<3; // 8
    case Synchro = 1<<4; // 16
    case Xyz     = 1<<5; // 32
    // 例: 通常かつシンクロ => 1 | 16 = 17

    public function label(): string {
        return match($this) {
            self::Normal  => '通常',
            self::Effect  => '効果',
            self::Ritual  => '儀式',
            self::Fusion  => '融合',
            self::Synchro => 'シンクロ',
            self::Xyz     => 'エクシーズ',
        };
    }
}

// app/Domain/Cards/SpellType.php
namespace App\Domain\Cards;
enum SpellType:string {
    case QuickPlay='quick';
    case Normal='normal';
    case Equip='equip';
    case Continuous='continuous';
    case Field='field';

    public function label(): string {
        return match($this) {
            self::QuickPlay  => '速攻',
            self::Normal     => '通常',
            self::Equip      => '装備',
            self::Continuous => '永続',
            self::Field      => 'フィールド',
        };
    }
}

// app/Domain/Cards/TrapType.php
namespace App\Domain\Cards;
enum TrapType:string {
    case Normal='normal';
    case Continuous='continuous';
    case Counter='counter';

    public function label(): string {
        return match($this) {
            self::Normal     => '通常',
            self::Continuous => '永続',
            self::Counter    => 'カウンター',
        };
    }
}
