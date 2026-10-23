<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;

class EmployeeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/team",
     *     tags={"Team"},
     *     summary="Get all team members",
     *     description="Returns a list of all active team members/employees ordered by their display order",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="position", type="string", example="Senior Tour Guide"),
     *                 @OA\Property(property="bio", type="string", example="Experienced guide with 10+ years..."),
     *                 @OA\Property(property="image", type="string", example="https://example.com/images/john.jpg"),
     *                 @OA\Property(property="social_links", type="object",
     *                     @OA\Property(property="facebook", type="string"),
     *                     @OA\Property(property="twitter", type="string"),
     *                     @OA\Property(property="linkedin", type="string"),
     *                     @OA\Property(property="instagram", type="string")
     *                 )
     *             ))
     *         )
     *     )
     * )
     */
    public function index()
    {
        $employees = Employee::active()
            ->ordered()
            ->get();

        return EmployeeResource::collection($employees);
    }
}

