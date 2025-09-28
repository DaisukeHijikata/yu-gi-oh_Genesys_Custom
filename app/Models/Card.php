<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Casts\MonsterFlagsCast; // ←これを忘れずに！

final class Card extends Model
{
    protected $table = 'cards';

    protected $fillable = [
        'name',
        'card_category',
        'monster_flags',
        'spell_type',
        'trap_type',
        'monster_level',
        'monster_rank',
        'attack',
        'defence',
        'image_url',
        'description',
    ];

    protected $casts = [
        'monster_flags' => MonsterFlagsCast::class,
    ];
}
