<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/appointments",
     *     tags={"Appointments"},
     *     summary="Create a general appointment",
     *     description="Submit a general trip inquiry or appointment request",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "phone", "adults", "arrival_date", "departure_date"},
     *             @OA\Property(property="name", type="string", maxLength=255, example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="phone", type="string", maxLength=50, example="+1234567890"),
     *             @OA\Property(property="country_phone_code", type="string", maxLength=10, example="+1"),
     *             @OA\Property(property="adults", type="integer", minimum=1, example=2),
     *             @OA\Property(property="children", type="integer", minimum=0, example=1),
     *             @OA\Property(property="infants", type="integer", minimum=0, example=0),
     *             @OA\Property(property="arrival_date", type="string", format="date", example="2025-02-15"),
     *             @OA\Property(property="departure_date", type="string", format="date", example="2025-02-22"),
     *             @OA\Property(property="notes", type="string", maxLength=1000, example="We are interested in historical sites"),
     *             @OA\Property(property="nationality", type="string", maxLength=100, example="American"),
     *             @OA\Property(property="hotel_choice", type="string", maxLength=255, example="5-star"),
     *             @OA\Property(property="age_range", type="string", maxLength=50, example="30-40"),
     *             @OA\Property(property="hear_about_us", type="string", maxLength=100, example="Google"),
     *             @OA\Property(property="type", type="string", enum={"appointment", "nile_cruise", "plan_trip"}, example="appointment")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Appointment created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Your request has been submitted successfully."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="reference", type="string", example="APT-000001")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'country_phone_code' => 'nullable|string|max:10',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'infants' => 'nullable|integer|min:0',
            'arrival_date' => 'required|date|after_or_equal:today',
            'departure_date' => 'required|date|after:arrival_date',
            'notes' => 'nullable|string|max:1000',
            'nationality' => 'nullable|string|max:100',
            'hotel_choice' => 'nullable|string|max:255',
            'age_range' => 'nullable|string|max:50',
            'hear_about_us' => 'nullable|string|max:100',
            'type' => 'nullable|in:appointment,nile_cruise,plan_trip',
        ]);

        $appointment = Appointment::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'country_phone_code' => $request->country_phone_code,
            'adults' => $request->adults,
            'children' => $request->children ?? 0,
            'infants' => $request->infants ?? 0,
            'arrival_date' => $request->arrival_date,
            'departure_date' => $request->departure_date,
            'notes' => $request->notes,
            'nationality' => $request->nationality,
            'hotel_choice' => $request->hotel_choice,
            'age_range' => $request->age_range,
            'hear_about_us' => $request->hear_about_us,
            'type' => $request->type ?? 'appointment',
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Your request has been submitted successfully. We will contact you soon.',
            'data' => [
                'id' => $appointment->id,
                'reference' => 'APT-' . str_pad($appointment->id, 6, '0', STR_PAD_LEFT),
            ],
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/appointments/nile-cruise",
     *     tags={"Appointments"},
     *     summary="Create a Nile Cruise inquiry",
     *     description="Submit a Nile Cruise booking inquiry",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "phone", "adults", "arrival_date", "departure_date", "cruise_type"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="phone", type="string", example="+1234567890"),
     *             @OA\Property(property="country_phone_code", type="string", example="+1"),
     *             @OA\Property(property="adults", type="integer", minimum=1, example=2),
     *             @OA\Property(property="children", type="integer", minimum=0, example=1),
     *             @OA\Property(property="arrival_date", type="string", format="date", example="2025-02-15"),
     *             @OA\Property(property="departure_date", type="string", format="date", example="2025-02-22"),
     *             @OA\Property(property="cruise_type", type="string", example="Luxury Nile Cruise"),
     *             @OA\Property(property="cruise_pick_drop_off", type="string", example="Luxor to Aswan"),
     *             @OA\Property(property="cruise_duration", type="string", example="4 nights"),
     *             @OA\Property(property="notes", type="string", example="We prefer a cabin with balcony")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Nile Cruise inquiry created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Your Nile Cruise inquiry has been submitted successfully."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="reference", type="string", example="NC-000001")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function nileCruise(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'country_phone_code' => 'nullable|string|max:10',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'arrival_date' => 'required|date|after_or_equal:today',
            'departure_date' => 'required|date|after:arrival_date',
            'cruise_type' => 'required|string|max:100',
            'cruise_pick_drop_off' => 'nullable|string|max:255',
            'cruise_duration' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
        ]);

        $appointment = Appointment::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'country_phone_code' => $request->country_phone_code,
            'adults' => $request->adults,
            'children' => $request->children ?? 0,
            'infants' => 0,
            'arrival_date' => $request->arrival_date,
            'departure_date' => $request->departure_date,
            'cruise_type' => $request->cruise_type,
            'cruise_pick_drop_off' => $request->cruise_pick_drop_off,
            'cruise_duration' => $request->cruise_duration,
            'notes' => $request->notes,
            'type' => 'nile_cruise',
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Your Nile Cruise inquiry has been submitted successfully.',
            'data' => [
                'id' => $appointment->id,
                'reference' => 'NC-' . str_pad($appointment->id, 6, '0', STR_PAD_LEFT),
            ],
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/appointments/plan-trip",
     *     tags={"Appointments"},
     *     summary="Create a custom trip planning request",
     *     description="Submit a custom trip planning request with detailed requirements",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "phone", "adults", "arrival_date", "departure_date", "notes"},
     *             @OA\Property(property="nickname", type="string", maxLength=100, example="Johnny"),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="phone", type="string", example="+1234567890"),
     *             @OA\Property(property="country_phone_code", type="string", example="+1"),
     *             @OA\Property(property="adults", type="integer", minimum=1, example=2),
     *             @OA\Property(property="children", type="integer", minimum=0, example=1),
     *             @OA\Property(property="infants", type="integer", minimum=0, example=0),
     *             @OA\Property(property="arrival_date", type="string", format="date", example="2025-02-15"),
     *             @OA\Property(property="departure_date", type="string", format="date", example="2025-02-22"),
     *             @OA\Property(property="notes", type="string", maxLength=2000, example="We want to visit pyramids, temples, and local markets"),
     *             @OA\Property(property="nationality", type="string", example="American"),
     *             @OA\Property(property="hotel_choice", type="string", example="5-star hotels")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Trip planning request created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Your trip planning request has been submitted successfully."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="reference", type="string", example="PLAN-000001")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function planTrip(Request $request)
    {
        $request->validate([
            'nickname' => 'nullable|string|max:100',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'country_phone_code' => 'nullable|string|max:10',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'infants' => 'nullable|integer|min:0',
            'arrival_date' => 'required|date|after_or_equal:today',
            'departure_date' => 'required|date|after:arrival_date',
            'notes' => 'required|string|max:2000',
            'nationality' => 'nullable|string|max:100',
            'hotel_choice' => 'nullable|string|max:255',
        ]);

        $appointment = Appointment::create([
            'nickname' => $request->nickname,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'country_phone_code' => $request->country_phone_code,
            'adults' => $request->adults,
            'children' => $request->children ?? 0,
            'infants' => $request->infants ?? 0,
            'arrival_date' => $request->arrival_date,
            'departure_date' => $request->departure_date,
            'notes' => $request->notes,
            'nationality' => $request->nationality,
            'hotel_choice' => $request->hotel_choice,
            'type' => 'plan_trip',
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Your trip planning request has been submitted successfully.',
            'data' => [
                'id' => $appointment->id,
                'reference' => 'PLAN-' . str_pad($appointment->id, 6, '0', STR_PAD_LEFT),
            ],
        ], 201);
    }
}

