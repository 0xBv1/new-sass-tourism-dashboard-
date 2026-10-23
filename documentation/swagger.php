<?php

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Tourism API Documentation",
 *     description="Comprehensive API documentation for the Tourism Management System. Updated to reflect the new trip booking system with simplified passenger management (number_of_passengers instead of separate adult/children counts), booking date tracking, and advanced seat booking system with seat numbering and specific seat selection capabilities.",
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
 *     name="Amenities",
 *     description="Hotel and room amenities"
 * )
 * 
 * @OA\Tag(
 *     name="Appointments",
 *     description="Trip planning appointments, Nile Cruise inquiries, and custom trip requests"
 * )
 * 
 * @OA\Tag(
 *     name="Auth",
 *     description="Authentication endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Blog",
 *     description="Blog management endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Bookings",
 *     description="Trip booking management endpoints - Updated to use simplified passenger system with seat selection"
 * )
 * 
 * @OA\Tag(
 *     name="Cart",
 *     description="Shopping cart endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Categories",
 *     description="Category management endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Cities",
 *     description="City management endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Comments",
 *     description="Tour comments and reviews from customers"
 * )
 * 
 * @OA\Tag(
 *     name="Contact",
 *     description="Contact request endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Destinations",
 *     description="Destination management endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Durations",
 *     description="Duration management endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="FAQ Categories",
 *     description="FAQ categories with their FAQs"
 * )
 * 
 * @OA\Tag(
 *     name="Hotels",
 *     description="Hotel management endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Newsletter",
 *     description="Newsletter subscription management"
 * )
 * 
 * @OA\Tag(
 *     name="Payments",
 *     description="Payment processing endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Pricing",
 *     description="Tour pricing calculation, packages, tiered pricing, early bird discounts, and dynamic pricing"
 * )
 * 
 * @OA\Tag(
 *     name="Reviews",
 *     description="Trip review and rating endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Rooms",
 *     description="Hotel room management endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Seats",
 *     description="Seat management endpoints - New seat booking system"
 * )
 * 
 * @OA\Tag(
 *     name="Sliders",
 *     description="Homepage sliders and banners endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Team",
 *     description="Team members/employees endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Tour Reviews",
 *     description="Tour review and rating endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Trips",
 *     description="Trip management endpoints - Updated with seat booking system"
 * )
 * 
 * @OA\Tag(
 *     name="Wishlist",
 *     description="Wishlist management endpoints"
 * )
 */ 
