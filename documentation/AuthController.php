<?php

namespace Documentation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Traits\Response\HasApiResponse;


/**
 * @OA\Schema(
 *  schema="Client",
 *  title="Client Schema",
 *                     @OA\Property(
 *                         property="id",
 *                         type="number",
 *                         example="1"
 *                     ),
 *                     @OA\Property(
 *                         property="name",
 *                         type="string",
 *                         example="Lorem Ipsum"
 *                     ),
 *                     @OA\Property(
 *                         property="email",
 *                         type="string",
 *                         example="email@example.com"
 *                     ),
 *                     @OA\Property(
 *                         property="password",
 *                         type="string",
 *                         example="users"
 *                     ),
 *                     @OA\Property(
 *                         property="phone",
 *                         type="string",
 *                         example="0152222225"
 *                     ),
 *                     @OA\Property(
 *                         property="nationality",
 *                         type="string",
 *                         example="Egypt"
 *                     ),
 *                     @OA\Property(
 *                         property="birthday",
 *                         type="string",
 *                         example="2000-01-01"
 *                     ),
 *                     @OA\Property(
 *                         property="updated_at",
 *                         type="string",
 *                         example="2023-12-11T09:25:53.000000Z"
 *                     ),
 *                     @OA\Property(
 *                         property="created_at",
 *                         type="string",
 *                         example="2023-12-11T09:25:53.000000Z"
 *                     )
 * )
 */
class AuthController extends Controller
{
    use HasApiResponse;

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="Client Login",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"email": "email@example.com", "password": "Password"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
    }

    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     summary="Client Register",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password_confirmation",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="birthdate",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="phone",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="nationality",
     *                     type="string"
     *                 ),
     *                 example={"name":"Jane Deo","email": "email@example.com", "password": "Password", "password_confirmation": "Password", "phone": "01150225286", "birthdate":"2000-01-01", "nationality": "Egypt"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     )
     * )
     */
    public function register(RegisterRequest $request)
    {
    }

}
