<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscribe;
use Illuminate\Http\Request;

class SubscribeController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/subscribe",
     *     tags={"Newsletter"},
     *     summary="Subscribe to newsletter",
     *     description="Subscribe an email to the newsletter list",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="subscriber@example.com"),
     *             @OA\Property(property="name", type="string", maxLength=255, example="John Doe")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successfully subscribed",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Thank you for subscribing to our newsletter!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Already subscribed or validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="This email is already subscribed to our newsletter.")
     *         )
     *     )
     * )
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'name' => 'nullable|string|max:255',
        ]);

        $existing = Subscribe::where('email', $request->email)->first();

        if ($existing) {
            if ($existing->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'This email is already subscribed to our newsletter.',
                ], 422);
            }

            // Resubscribe
            $existing->resubscribe();
            
            return response()->json([
                'success' => true,
                'message' => 'Welcome back! You have been resubscribed to our newsletter.',
            ]);
        }

        Subscribe::create([
            'email' => $request->email,
            'name' => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for subscribing to our newsletter!',
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/subscribe/unsubscribe/{token}",
     *     tags={"Newsletter"},
     *     summary="Unsubscribe from newsletter",
     *     description="Unsubscribe an email from the newsletter using the unsubscribe token",
     *     @OA\Parameter(
     *         name="token",
     *         in="path",
     *         required=true,
     *         description="Unique unsubscribe token sent to the subscriber's email",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully unsubscribed",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="You have been successfully unsubscribed from our newsletter.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Invalid unsubscribe link",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Invalid unsubscribe link.")
     *         )
     *     )
     * )
     */
    public function unsubscribe(Request $request, string $token)
    {
        $subscriber = Subscribe::where('unsubscribe_token', $token)->first();

        if (!$subscriber) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid unsubscribe link.',
            ], 404);
        }

        if (!$subscriber->is_active) {
            return response()->json([
                'success' => true,
                'message' => 'You are already unsubscribed.',
            ]);
        }

        $subscriber->unsubscribe();

        return response()->json([
            'success' => true,
            'message' => 'You have been successfully unsubscribed from our newsletter.',
        ]);
    }
}

