<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\HotelDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\HotelRequest;
use App\Models\Amenity;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomAdult;
use App\Models\RoomChild;
use App\Models\MealType;

class HotelController extends Controller
{

    public function index(HotelDataTable $dataTable)
    {
        return $dataTable->render('dashboard.hotels.index');
    }

    public function store(HotelRequest $request)
    {
        $hotel = Hotel::create($request->getSanitized());
        $hotel->seo()->create($request->get('seo'));
        $hotel->amenities()->attach($request->get('amenities'));
        
        // Create rooms automatically
        $roomCount = $this->createRoomsForHotel($hotel, $request);
        
        // Create meal types
        $mealTypesCount = $this->createMealTypesForHotel($hotel, $request);
        
        $message = "Hotel Created Successfully! {$roomCount} rooms have been automatically created with the specified pricing structure.";
        if ($mealTypesCount > 0) {
            $message .= " {$mealTypesCount} meal types have been added.";
        }
        
        session()->flash('message', $message);
        session()->flash('type', 'success');
        return redirect()->route('dashboard.hotels.edit', $hotel);
    }

    public function create()
    {
        $amenities = Amenity::all();
        return view('dashboard.hotels.create', compact('amenities'));
    }

    public function show(Hotel $hotel)
    {
        //
    }


    public function edit(Hotel $hotel)
    {
        $amenities = Amenity::all();
        return view('dashboard.hotels.edit', compact('hotel', 'amenities'));
    }


    public function update(HotelRequest $request, Hotel $hotel)
    {
        $hotel->update($request->getSanitized());
        $hotel->amenities()->sync($request->get('amenities'));
        $hotel->seo ?
            $hotel->seo->update($request->get('seo')) :
            $hotel->seo()->create($request->get('seo'));
        session()->flash('message', 'Hotel Updated Successfully!');
        session()->flash('type', 'success');
        return back();
    }


    public function destroy(Hotel $hotel)
    {
        $hotel->delete();
        return response()->json([
            'message' => 'Hotel Deleted Successfully!'
        ]);
    }

    public function rooms(Hotel $hotel)
    {
        $rooms = $hotel->rooms()->get();
        return response()->json($rooms);
    }

    /**
     * Create rooms automatically for the hotel
     */
    private function createRoomsForHotel(Hotel $hotel, HotelRequest $request): int
    {
        $roomCount = $request->get('room_count');
        $adultPrices = $request->get('adult_prices', []);
        $childPrices = $request->get('child_prices', []);

        for ($i = 1; $i <= $roomCount; $i++) {
            // Create room
            $room = Room::create([
                'hotel_id' => $hotel->id,
                'slug' => $hotel->slug . '-room-' . $i,
                'bed_count' => 2, // Default bed count
                'room_type' => 'Standard', // Default room type
                'max_capacity' => 4, // Default max capacity
                'bed_types' => 'Double Bed', // Default bed type
                'night_price' => $adultPrices[0]['price'] ?? 0, // Default price from first adult price
                'enabled' => true,
            ]);

            // Create adult prices for this room
            foreach ($adultPrices as $adultData) {
                if (!empty($adultData['count']) && !empty($adultData['price'])) {
                    RoomAdult::create([
                        'room_id' => $room->id,
                        'age_range' => '18+',
                        'price' => $adultData['price'],
                        'max_count' => $adultData['count'],
                        'description' => $adultData['description'] ?? $adultData['count'] . ' Adult' . ($adultData['count'] > 1 ? 's' : ''),
                        'enabled' => true,
                    ]);
                }
            }

            // Create child prices for this room
            foreach ($childPrices as $childData) {
                if (!empty($childData['count']) && !empty($childData['price'])) {
                    RoomChild::create([
                        'room_id' => $room->id,
                        'age_range' => '0-17',
                        'price' => $childData['price'],
                        'max_count' => $childData['count'],
                        'description' => $childData['description'] ?? $childData['count'] . ' Child' . ($childData['count'] > 1 ? 'ren' : ''),
                        'enabled' => true,
                    ]);
                }
            }
        }
        
        return $roomCount;
    }

    /**
     * Create meal types for the hotel
     */
    private function createMealTypesForHotel(Hotel $hotel, HotelRequest $request): int
    {
        $mealTypes = $request->get('meal_types', []);
        
        foreach ($mealTypes as $mealData) {
            if (!empty($mealData['type'])) {
                // Handle image uploads
                $images = [];
                if ($request->hasFile('meal_types')) {
                    $mealIndex = array_search($mealData, $mealTypes);
                    $uploadedImages = $request->file("meal_types.{$mealIndex}.images");
                    
                    if ($uploadedImages) {
                        foreach ($uploadedImages as $image) {
                            $path = $image->store('meal-images', 'public');
                            $images[] = $path;
                        }
                    }
                }
                
                   MealType::create([
                       'hotel_id' => $hotel->id,
                       'name' => $mealData['type'], // Use type instead of name
                       'description' => $mealData['description'] ?? null,
                       'price' => $mealData['price'] ?? 0,
                       'images' => $images,
                       'enabled' => isset($mealData['enabled']) ? (bool)$mealData['enabled'] : true,
                   ]);
            }
        }
        
        return count(array_filter($mealTypes, function($meal) {
            return !empty($meal['type']); // Check for type instead of name
        }));
    }
}
