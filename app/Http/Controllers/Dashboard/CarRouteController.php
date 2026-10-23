<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\CarRouteDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\CarRouteRequest;
use App\Http\Requests\Dashboard\ImportCarRoutesRequest;
use App\Models\CarRoute;
use App\Models\Location;
use App\Services\Client\CarRental as CarRentalService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CarRouteController extends Controller
{

    private string $importFileHeader = 'place_from,place_to,sedan,minivan,minibus';

    public function index(CarRouteDataTable $dataTable)
    {
        return $dataTable->render('dashboard.car-routes.index');
    }


    public function create()
    {
        $locations = Location::active()->get();
        return view('dashboard.car-routes.create', compact('locations'));
    }


    public function store(CarRouteRequest $request)
    {
        try {
            DB::beginTransaction();
            $route = CarRoute::create($request->getSanitized());
            $route->prices()->createMany($request->collect('prices')->filter(fn($g) => !is_null($g['car_type']))->toArray());
            $route->stops()->createMany($request->collect('stops')->filter(fn($g) => !is_null($g['stop_location_id']))->toArray());
            session()->flash('message', 'Car Route Created Successfully!');
            session()->flash('type', 'success');
            DB::commit();
        } catch (\Throwable $throwable) {
            DB::rollBack();
            session()->flash('message', 'Something went wrong!');
            session()->flash('type', 'danger');
        }

        return back();
    }


    public function show(CarRoute $carRoute)
    {
        //
    }


    public function edit(CarRoute $carRoute)
    {
        $carRoute->load(['prices', 'stops']);
        $locations = Location::active()->get();
        return view('dashboard.car-routes.edit', compact('carRoute', 'locations'));
    }


    public function update(CarRouteRequest $request, CarRoute $carRoute)
    {

        $carRoute->update($request->getSanitized());
        $carRoute
            ->prices()
            ->whereNotIn(
                'id',
                $request->collect('prices')
                    ->pluck('id')
                    ->filter(fn($id) => !empty($id))->toArray()
            )->delete();

        $carRoute
            ->stops()
            ->whereNotIn(
                'id',
                $request->collect('stops')
                    ->pluck('id')
                    ->filter(fn($id) => !empty($id))->toArray()
            )->delete();

        foreach ($request->get('prices', []) as $group) {
            $carRoute->prices()->updateOrCreate([
                'id' => $group['id'] ?? null,
            ], $group);
        }

        foreach ($request->get('stops', []) as $stop) {
            $carRoute->stops()->updateOrCreate([
                'id' => $stop['id'] ?? null,
            ], $stop);
        }
        session()->flash('message', 'Car Route Updated Successfully!');
        session()->flash('type', 'success');
        return back();
    }


    public function destroy(CarRoute $carRoute)
    {
        $carRoute->delete();
        return response()->json([
            'message' => 'Car Route Deleted Successfully!'
        ]);
    }

    public function template()
    {
        $path = storage_path('app/temp');
        File::ensureDirectoryExists($path);
        File::put($path . '/car-routes.csv', $this->importFileHeader . PHP_EOL);
        return response()->download($path . '/car-routes.csv');
    }

    public function import(ImportCarRoutesRequest $request, CarRentalService $carRentalService)
    {
        $path = storage_path('app/files');
        File::ensureDirectoryExists($path);
        $file = $request->file('file')->store('files');
        $file_lines = file(storage_path('app/' . $file));
        $file_lines = collect($file_lines)
            ->map(fn($l) => trim($l))
            ->filter(fn($l) => !empty($l))
            ->values()
            ->toArray();
        $header = count($file_lines) > 0 ? strtolower($file_lines[0]) : '';
        if (empty($file_lines) || $header != $this->importFileHeader) {
            session()->flash('message', 'Courted File!');
            session()->flash('type', 'danger');
            return back();
        }
        unset($file_lines[0]);
        $routes = [];
        $imported_records = 0;
        $skipped_records = 0;
        foreach ($file_lines as $line) {
            try {
                list($from, $to, $sedan_price, $minivan_price, $minibus) = explode(',', $line);
                $route = [
                    'from' => trim($from),
                    'to' => trim($to),
                    'sedan_price' => is_numeric($sedan_price) ? floatval($sedan_price) : 0,
                    'minivan_price' => is_numeric($minivan_price) ? floatval($minivan_price) : 0,
                    'minibus_price' => is_numeric($minibus) ? floatval($minibus) : 0,
                ];
                $pick_up = Location::whereTranslation('name', $route['from'])->first() ?? Location::create(['active' => true, 'en' => ['name' => $route['from']]]);
                $destination = Location::whereTranslation('name', $route['to'])->first() ?? Location::create(['active' => true, 'en' => ['name' => $route['to']]]);
                $route_exist_check = $carRentalService->search($pick_up->id, $destination->id);
                if ($route_exist_check) {
                    $skipped_records++;
                    continue;
                }
                $carRoute = CarRoute::create([
                    'pickup_location_id' => $pick_up->id,
                    'destination_id' => $destination->id,
                ]);
                $carRoute->prices()->createMany([
                    ['from' => 1, 'to' => 3, 'oneway_price' => $route['sedan_price'], 'rounded_price' => $route['sedan_price'] * 2, 'car_type' => 'Sedan'],
                    ['from' => 4, 'to' => 6, 'oneway_price' => $route['minivan_price'], 'rounded_price' => $route['minivan_price'] * 2, 'car_type' => 'Minivan'],
                    ['from' => 7, 'to' => 11, 'oneway_price' => $route['minibus_price'], 'rounded_price' => $route['minibus_price'] * 2, 'car_type' => 'Minibus'],
                ]);
                $imported_records++;
            } catch (\Exception $exception) {

            }
        }
        session()->flash('message', "$imported_records Imported records, $skipped_records Skipped");
        session()->flash('type', 'success');
        return back();
    }
}
