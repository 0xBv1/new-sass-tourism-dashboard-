<?php

/**
 * @OA\Schema(
 *     schema="Trip",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="trip_name", type="string", example="Cairo to Alexandria"),
 *     @OA\Property(property="trip_type", type="string", enum={"one_way", "round_trip", "special_discount"}),
 *     @OA\Property(property="trip_type_label", type="string", example="One Way"),
 *     @OA\Property(property="from", type="string", example="Cairo"),
 *     @OA\Property(property="to", type="string", example="Alexandria"),
 *     @OA\Property(property="travel_date", type="string", format="date", example="2024-01-15"),
 *     @OA\Property(property="return_date", type="string", format="date", nullable=true),
 *     @OA\Property(property="departure_time", type="string", example="08:00"),
 *     @OA\Property(property="arrival_time", type="string", example="10:00"),
 *     @OA\Property(property="price", type="number", format="float", example=150.00),
 *     @OA\Property(property="amenities", type="array", @OA\Items(type="string"), example={"Wi-Fi", "AC"}),
 *     @OA\Property(property="available_seats", type="integer", example=25),
 *     @OA\Property(property="total_seats", type="integer", example=30),
 *     @OA\Property(property="additional_notes", type="string", nullable=true),
 *     @OA\Property(property="occupancy_rate", type="number", format="float", example=16.67),
 *     @OA\Property(property="occupancy_status", type="string", example="Available")
 * )
 */

/**
 * @OA\Schema(
 *     schema="Duration",
 *     title="Duration",
 *     description="Duration model",
 *     required={"id", "title"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="title", type="string", example="3 Days 2 Nights"),
 *     @OA\Property(property="description", type="string", example="Perfect for a short getaway"),
 *     @OA\Property(property="slug", type="string", example="3-days-2-nights"),
 *     @OA\Property(property="enabled", type="boolean", example=true),
 *     @OA\Property(property="featured", type="boolean", example=false),
 *     @OA\Property(property="display_order", type="integer", example=1),
 *     @OA\Property(property="days", type="integer", example=3),
 *     @OA\Property(property="nights", type="integer", example=2),
 *     @OA\Property(property="duration_type", type="string", enum={"days", "hours", "weeks", "months"}, example="days"),
 *     @OA\Property(property="tours_count", type="integer", example=15),
 *     @OA\Property(property="formatted_duration", type="string", example="3 Days 2 Nights"),
 *     @OA\Property(property="banner", type="string", nullable=true, example="banners/duration-banner.jpg"),
 *     @OA\Property(property="featured_image", type="string", nullable=true, example="images/duration-featured.jpg"),
 *     @OA\Property(property="gallery", type="array", @OA\Items(type="string"), example={"images/gallery1.jpg", "images/gallery2.jpg"}),
 *     @OA\Property(property="parent_id", type="integer", nullable=true, example=null),
 *     @OA\Property(
 *         property="seo",
 *         type="object",
 *         nullable=true,
 *         @OA\Property(property="meta_title", type="string", example="3 Days 2 Nights Tours"),
 *         @OA\Property(property="meta_description", type="string", example="Discover amazing 3 days 2 nights tour packages"),
 *         @OA\Property(property="meta_keywords", type="string", example="3 days, 2 nights, tours, packages")
 *     ),
 *     @OA\Property(property="children", type="array", @OA\Items(ref="#/components/schemas/Duration")),
 *     @OA\Property(property="parent", ref="#/components/schemas/Duration", nullable=true),
 *     @OA\Property(property="tours", type="array", @OA\Items(ref="#/components/schemas/Tour"))
 * )
 */

/**
 * @OA\Schema(
 *     schema="TripSearchResult",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="from", type="string", example="Cairo"),
 *     @OA\Property(property="to", type="string", example="Alexandria"),
 *     @OA\Property(property="departure_time", type="string", example="08:00"),
 *     @OA\Property(property="arrival_time", type="string", example="10:00"),
 *     @OA\Property(property="date", type="string", format="date", example="2024-01-15"),
 *     @OA\Property(property="price", type="number", format="float", example=150.00),
 *     @OA\Property(property="amenities", type="array", @OA\Items(type="string"), example={"Wi-Fi", "AC"}),
 *     @OA\Property(property="available_seats", type="integer", example=25),
 *     @OA\Property(property="notes", type="string", example="Available in date range: Jan 15, 2024 to Jan 20, 2024. Group booking for 2 passengers. Amenities: Wi-Fi, AC"),
 *     @OA\Property(property="trip_type", type="string", enum={"one_way", "round_trip", "special_discount"}),
 *     @OA\Property(property="additional_notes", type="string", nullable=true)
 * )
 */

/**
 * @OA\Schema(
 *     schema="TripAdvancedSearchResult",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/Trip"),
 *         @OA\Schema(
 *             @OA\Property(property="notes", type="string", example="Available in date range: Jan 15, 2024 to Jan 20, 2024. Price range: from 100.00 EGP to 500.00 EGP. Amenities: Wi-Fi, AC")
 *         )
 *     }
 * )
 */

/**
 * @OA\Schema(
 *     schema="TripSearchCriteria",
 *     @OA\Property(property="trip_type", type="string", example="one_way"),
 *     @OA\Property(property="departure_city_id", type="integer", example=1),
 *     @OA\Property(property="arrival_city_id", type="integer", example=2),
 *     @OA\Property(property="passengers", type="integer", example=2),
 *     @OA\Property(property="travel_date", type="string", format="date", example="2024-01-15"),
 *     @OA\Property(property="date_from", type="string", format="date", example="2024-01-15"),
 *     @OA\Property(property="date_to", type="string", format="date", example="2024-01-20"),
 *     @OA\Property(property="return_date", type="string", format="date", nullable=true)
 * )
 */

/**
 * @OA\Schema(
 *     schema="AdvancedTripSearchCriteria",
 *     @OA\Property(property="date_from", type="string", format="date", example="2024-01-15"),
 *     @OA\Property(property="date_to", type="string", format="date", example="2024-01-20"),
 *     @OA\Property(property="trip_type", type="string", example="one_way"),
 *     @OA\Property(property="departure_city_id", type="integer", example=1),
 *     @OA\Property(property="arrival_city_id", type="integer", example=2),
 *     @OA\Property(property="passengers", type="integer", example=2),
 *     @OA\Property(property="price_min", type="number", format="float", example=100.00),
 *     @OA\Property(property="price_max", type="number", format="float", example=500.00),
 *     @OA\Property(property="departure_time_from", type="string", format="time", example="08:00"),
 *     @OA\Property(property="departure_time_to", type="string", format="time", example="18:00"),
 *     @OA\Property(property="sort_by", type="string", example="price"),
 *     @OA\Property(property="sort_order", type="string", example="asc"),
 *     @OA\Property(property="per_page", type="integer", example=20)
 * )
 */

/**
 * @OA\Schema(
 *     schema="DateRangeInfo",
 *     @OA\Property(property="search_type", type="string", enum={"single_date", "date_range", "advanced_date_range"}),
 *     @OA\Property(property="from_date", type="string", format="date", example="2024-01-15"),
 *     @OA\Property(property="to_date", type="string", format="date", example="2024-01-20"),
 *     @OA\Property(property="total_days", type="integer", example=6),
 *     @OA\Property(property="date", type="string", format="date", example="2024-01-15", description="For single date searches")
 * )
 */

/**
 * @OA\Schema(
 *     schema="TripBookingResult",
 *     @OA\Property(property="booking_reference", type="string", example="TRP-2024-001"),
 *     @OA\Property(property="selected_seats", type="array", @OA\Items(type="integer"), example={1, 2}),
 *     @OA\Property(property="total_price", type="number", format="float", example=300.00),
 *     @OA\Property(property="number_of_passengers", type="integer", example=2),
 *     @OA\Property(property="booking_date", type="string", format="date", example="2024-01-15"),
 *     @OA\Property(property="status", type="string", example="pending")
 * )
 */

/**
 * @OA\Schema(
 *     schema="Pagination",
 *     @OA\Property(property="current_page", type="integer", example=1),
 *     @OA\Property(property="last_page", type="integer", example=5),
 *     @OA\Property(property="per_page", type="integer", example=15),
 *     @OA\Property(property="total", type="integer", example=75),
 *     @OA\Property(property="from", type="integer", example=1),
 *     @OA\Property(property="to", type="integer", example=15)
 * )
 */

/**
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     @OA\Property(property="success", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Trip not available")
 * )
 */

/**
 * @OA\Schema(
 *     schema="ValidationError",
 *     @OA\Property(property="message", type="string", example="The given data was invalid."),
 *     @OA\Property(property="errors", type="object", example={
 *         "trip_type": {"The trip type field is required."},
 *         "departure_city_id": {"The departure city id field is required."}
 *     })
 * )
 */ 