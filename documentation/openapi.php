<?php

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Tourism API Documentation",
 *     description="Comprehensive API documentation for the Tourism Management System with date range search functionality",
 *     @OA\Contact(
 *         email="info@perfectsolutions4u.com",
 *         name="Perfect Solutions 4U",
 *         url="https://perfectsolutions4u.com"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 * 
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 * 
 * @OA\Tag(
 *     name="Trips",
 *     description="Trip management and search endpoints with date range functionality"
 * )
 * 
 * @OA\Tag(
 *     name="Seats",
 *     description="Seat management endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Bookings",
 *     description="Trip booking management endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Auth",
 *     description="Authentication endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Cities",
 *     description="City management endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Hotels",
 *     description="Hotel management endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Tours",
 *     description="Tour management endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Tour Options",
 *     description="Tour option management endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Blog",
 *     description="Blog management endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Cart",
 *     description="Shopping cart endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Payments",
 *     description="Payment processing endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Durations",
 *     description="Duration management endpoints"
 * )
 * 
 * @OA\Schema(
 *     schema="BlogComment",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="blog_id", type="integer", example=1),
 *     @OA\Property(property="parent_id", type="integer", nullable=true, example=null),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="comment", type="string", example="Great article! Very informative."),
 *     @OA\Property(property="is_approved", type="boolean", example=true),
 *     @OA\Property(property="is_featured", type="boolean", example=false),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="blog", type="object", description="Blog post details"),
 *     @OA\Property(property="client", type="object", nullable=true, description="Client details if logged in"),
 *     @OA\Property(property="parent", type="object", nullable=true, description="Parent comment if this is a reply"),
 *     @OA\Property(property="replies", type="array", @OA\Items(ref="#/components/schemas/BlogComment"), description="Replies to this comment")
 * )
 */ 