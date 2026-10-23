<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\TourDataTable;
use App\Events\ResourceCreatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\TourRequest;
use App\Models\AccommodationCategory;
use App\Models\Category;
use App\Models\Destination;
use App\Models\Duration;
use App\Models\GroupSize;
use App\Models\Hotel;
use App\Models\Tour;
use App\Models\TourOption;

class TourController extends Controller
{

    public function index(TourDataTable $dataTable)
    {
        return $dataTable->render('dashboard.tours.index');
    }

    public function store(TourRequest $request)
    {
        $tour = Tour::create($request->getSanitized());
        $tour->seo()->create($request->get('seo'));
        $tour->categories()->attach($request->get('categories'));
        $tour->destinations()->attach($request->get('destinations'));
        $tour->options()->attach($request->get('options'));
        $request->collect('days')->each(fn($day) => $tour->days()->create($day));
        $seasons_keys = [
            'available',
            'pricing_groups',
        ];
        foreach ($request->get('seasons', []) as $index => $season) {
            $valid_season = true;
            // Build available array from accommodation data
            $available = [];
            if (isset($season['accommodation'])) {
                $available['accommodation'] = $season['accommodation'];
            }
            if (isset($season['without_accommodation'])) {
                $available['without_accommodation'] = $season['without_accommodation'];
            }
            
            // If we have accommodation data, create the season
            if (!empty($available)) {
                $seasonData = [
                    'available' => $available,
                    'pricing_groups' => $season['pricing_groups'] ?? [],
                    'enabled' => isset($season['enabled']),
                    'title' => $season['season_name'] ?? "Season " . ($index + 1),
                ];
                $tour->seasons()->create($seasonData);
            } elseif (!empty($season['available']) || !empty($season['pricing_groups'])) {
                // Fallback for old format
                $season['enabled'] = isset($season['enabled']);
                $tour->seasons()->create($season);
            }
        }
        
        // Also handle old 'season' format for backward compatibility
        foreach ($request->get('season', []) as $season) {
            $valid_season = true;
            foreach ($seasons_keys as $k) {
                if (empty($season[$k])) {
                    $valid_season = false;
                    break;
                }
            }
            if ($valid_season) {
                $season['enabled'] = isset($season['enabled']);
                $tour->seasons()->create($season);
            }
        }
        Category::whereIn('id', $request->get('categories', []))
            ->get()->each(function ($category) {
                $category->setToursCount();
            });
        Destination::whereIn('id', $request->get('destinations', []))
            ->get()->each(function ($destination) {
                $destination->setToursCount();
            });
        session()->flash('message', 'Tour Created Successfully!');
        session()->flash('type', 'success');
        ResourceCreatedEvent::dispatch($tour);
        return redirect()->route('dashboard.tours.edit', $tour);
    }

    public function create()
    {
        $relations = [
            'categories' => Category::all(),
            'destinations' => Destination::all(),
            'options' => TourOption::all(),
            'hotels' => Hotel::all(),
            'durations' => Duration::enabled()
                ->orderBy('display_order')
                ->orderBy('days')
                ->get(),
            'accommodation_categories' => AccommodationCategory::enabled()
                ->ordered()
                ->get(),
            'group_sizes' => GroupSize::enabled()
                ->ordered()
                ->get(),
        ];
        return view('dashboard.tours.create', compact('relations'));
    }

    public function show(Tour $tour)
    {
        //
    }


    public function edit(Tour $tour)
    {
        $relations = [
            'categories' => Category::all(),
            'destinations' => Destination::all(),
            'options' => TourOption::all(),
            'hotels' => Hotel::all(),
            'durations' => Duration::enabled()
                ->orderBy('display_order')
                ->orderBy('days')
                ->get(),
            'accommodation_categories' => AccommodationCategory::enabled()
                ->ordered()
                ->get(),
            'group_sizes' => GroupSize::enabled()
                ->ordered()
                ->get(),
        ];
        return view('dashboard.tours.edit', compact('tour', 'relations'));
    }


    public function update(TourRequest $request, Tour $tour)
    {
        $tour_old_category_ids = $tour->categories()->pluck('id')->toArray();
        $tour_old_destination_ids = $tour->destinations()->pluck('id')->toArray();
        $tour->update($request->getSanitized());
        $tour->seo ?
            $tour->seo->update($request->get('seo')) :
            $tour->seo()->create($request->get('seo'));
        $tour->categories()->sync($request->get('categories'));
        $tour->destinations()->sync($request->get('destinations'));
        $tour->options()->sync($request->get('options'));
        $tour->days()->delete();
        foreach ($request->get('days') as $tour_day) {
            if (isset($tour_day[config('app.locale')])) {
                $tour->days()->create($tour_day);
            }
        }
        $tour->seasons()->delete();
        $seasons_keys = [
            'available',
            'pricing_groups',
        ];

        // Handle new seasons format with accommodation data
        foreach ($request->get('seasons', []) as $index => $season) {
            $available = [];
            if (isset($season['accommodation'])) {
                $available['accommodation'] = $season['accommodation'];
            }
            if (isset($season['without_accommodation'])) {
                $available['without_accommodation'] = $season['without_accommodation'];
            }
            
            if (!empty($available)) {
                $seasonData = [
                    'available' => $available,
                    'pricing_groups' => $season['pricing_groups'] ?? [],
                    'enabled' => isset($season['enabled']),
                    'title' => $season['season_name'] ?? "Season " . ($index + 1),
                ];
                $tour->seasons()->create($seasonData);
            } elseif (!empty($season['available']) || !empty($season['pricing_groups'])) {
                $season['enabled'] = isset($season['enabled']);
                $tour->seasons()->create($season);
            }
        }

        // Also handle old 'season' format for backward compatibility
        foreach ($request->get('season', []) as $season) {
            $valid_season = true;
            foreach ($seasons_keys as $k) {
                if (empty($season[$k])) {
                    $valid_season = false;
                    break;
                }
            }
            if ($valid_season) {
                $season['enabled'] = isset($season['enabled']);
                $tour->seasons()->create($season);
            }
        }

        $c_ids = array_merge($tour_old_category_ids, $request->get('categories', []));
        Category::whereIn('id', array_unique($c_ids))
            ->get()->each(function ($category) {
                $category->setToursCount();
            });

        $d_ids = array_merge($tour_old_destination_ids, $request->get('destinations', []));
        Destination::whereIn('id', array_unique($d_ids))
            ->get()->each(function ($destination) {
                $destination->setToursCount();
            });

        session()->flash('message', 'Tour Updated Successfully!');
        session()->flash('type', 'success');
        return back();
    }


    public function destroy(Tour $tour)
    {
        $tour->delete();
        return response()->json([
            'message' => 'Tour Deleted Successfully!'
        ]);
    }
}
