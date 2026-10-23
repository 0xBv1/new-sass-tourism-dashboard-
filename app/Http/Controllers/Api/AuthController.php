<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Models\Client;
use App\Traits\Response\HasApiResponse;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use HasApiResponse;

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     tags={"Auth"},
     *     summary="Client login",
     *     description="Authenticate a client and return access token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Login successful"),
     *     @OA\Response(response=400, description="Invalid credentials or blocked account")
     * )
     */
    public function login(LoginRequest $request)
    {
        $client = Client::whereEmail($request->get('email'))->first();
        if ($client->blocked) {
            return $this->send(message: __('auth.blocked'), statusCode: Response::HTTP_BAD_REQUEST);
        }
        if (Hash::check($request->get('password'), $client->password)) {
            $token = $client->createToken('api.auth')->accessToken;
            return $this->send(array_merge($client->toArray(), ['accessToken' => $token]), __('messages.auth.logged_in_successfully'));
        }
        return $this->send(message: __('auth.password'), statusCode: Response::HTTP_BAD_REQUEST);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     tags={"Auth"},
     *     summary="Client registration",
     *     description="Register a new client account",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password"),
     *             @OA\Property(property="phone", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Registration successful"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function register(RegisterRequest $request)
    {
        $client = Client::create($request->getSanitized());
        return $this->send(data: $client, message: __('messages.auth.registered'), statusCode: Response::HTTP_CREATED);
    }
}
