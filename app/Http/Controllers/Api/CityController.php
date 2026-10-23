<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/cities",
     *     tags={"Cities"},
     *     summary="Get all cities",
     *     description="Returns a list of cities for trip departures/arrivals",
     *     @OA\Parameter(name="id", in="query", description="Filter by city ID", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="search", in="query", description="Search by city name", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Successful operation")
     * )
     */
    public function index(Request $request)
    {
        $query = City::query();

        // Search by ID
        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        // Search by name (keep for backward compatibility)
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Order by name
        $cities = $query->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $cities->map(function ($city) {
                return [
                    'id' => $city->id,
                    'name' => $city->name,
                    'slug' => $city->slug,
                ];
            })
        ]);
    }
} 