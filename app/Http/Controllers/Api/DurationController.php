<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Api\DurationResource;
use App\Http\Resources\Api\TourResource;
use App\Models\Duration;
use App\Models\Tour;
use App\Traits\Response\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Exception;

class DurationController extends Controller
{
    use HasApiResponse;

    /**
     * @OA\Get(
     *     path="/api/durations",
     *     tags={"Durations"},
     *     summary="Get all durations or search tours by duration",
     *     description="Returns durations list or tours filtered by duration search",
     *     @OA\Parameter(name="search", in="query", description="Duration ID or title to search tours", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Successful operation")
     * )
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        if ($search !== '') {
            $durations = Duration::query()
                ->when(is_numeric($search), function (Builder $q) use ($search) {
                    $q->where('id', (int) $search);
                }, function (Builder $q) use ($search) {
                    $q->where(function (Builder $inner) use ($search) {
                        $inner->whereHas('translations', function (Builder $t) use ($search) {
                            $t->where('title', 'like', "%{$search}%")
                              ->orWhere('description', 'like', "%{$search}%");
                        });
                    });
                })
                ->with(['tours' => function ($q) {
                    $q->where('enabled', true)->orderBy('display_order', 'asc');
                }])
                ->get();

            /** @var Collection<int, Tour> $tours */
            $tours = $durations->flatMap(function (Duration $duration) {
                return $duration->tours;
            })->unique('id')->values();

            return $this->send(TourResource::collection($tours));
        }

        $durations = Duration::query()
            ->where('enabled', true)
            ->with(['tours' => function ($q) {
                $q->where('enabled', true);
            }])
            ->orderBy('display_order', 'asc')
            ->get();

        return $this->send(DurationResource::collection($durations));
    }

    /**
     * @OA\Get(
     *     path="/api/durations/{slug}",
     *     tags={"Durations"},
     *     summary="Get duration by slug",
     *     @OA\Parameter(name="slug", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Duration not found")
     * )
     */
    public function show(Request $request, string $slug)
    {
        $request->merge([
            'slug' => $slug
        ]);
        $duration = Duration::query()
            ->where('slug', $slug)
            ->with(['tours' => function ($q) {
                $q->where('enabled', true);
            }])
            ->firstOrFail();

        return $this->send(new DurationResource($duration));
    }
} 