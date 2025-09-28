<?php
// app/Domain/Cards/SuperType.php
namespace App\Domain\Cards;

enum SuperType: string { case Monster='monster'; case Spell='spell'; case Trap='trap'; }

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
}

// app/Domain/Cards/SpellType.php
namespace App\Domain\Cards;
enum SpellType:string { case QuickPlay='quick'; case Normal='normal'; case Equip='equip'; case Continuous='continuous'; case Field='field'; }

// app/Domain/Cards/TrapType.php
namespace App\Domain\Cards;
enum TrapType:string { case Normal='normal'; case Continuous='continuous'; case Counter='counter'; }
