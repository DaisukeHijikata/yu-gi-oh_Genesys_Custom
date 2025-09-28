<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CardService;
use Illuminate\Http\JsonResponse;

// ▼ Domain
use App\Domain\Cards\{
    Card,
    SuperType,
    SpellType,
    TrapType,
    MonsterFlags,
    MonsterStats,
};
use DateTimeImmutable;

final class CardController extends Controller
{
    public function __construct(private readonly CardService $service) {}

    public function index(): JsonResponse
    {
        $cards = $this->service->list();
        $payload = array_map(fn($c) => $c->toApiArray(), $cards);
        return response()->json($payload);
    }

    public function show(int $id): JsonResponse
    {
        $card = $this->service->find($id);
        return response()->json($card->toApiArray());
    }
}
