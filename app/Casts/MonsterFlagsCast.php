<?php
// app/Casts/MonsterFlagsCast.php
namespace App\Casts;
use App\Domain\Cards\MonsterFlags;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class MonsterFlagsCast implements CastsAttributes {
    public function get($model, $key, $value, $attributes) {
        return new MonsterFlags((int)($value ?? 0));
    }
    public function set($model, $key, $value, $attributes) {
        return [$key => $value instanceof MonsterFlags ? $value->bits : (int)$value];
    }
}
