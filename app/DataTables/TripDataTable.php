<?php

namespace App\DataTables;

use App\Models\Trip;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TripDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('enabled', fn(Trip $trip) => $trip->enabled ? 'Y' : 'N')
            ->editColumn('created_at', fn(Trip $trip) => $trip->created_at->format('M Y, d'))
            ->editColumn('departure_city', fn(Trip $trip) => $trip->departure_city_name)
            ->editColumn('arrival_city', fn(Trip $trip) => $trip->arrival_city_name)
            ->editColumn('travel_date', fn(Trip $trip) => $trip->travel_date->format('M d, Y'))
            ->editColumn('departure_time', fn(Trip $trip) => $trip->formatted_departure_time)
            ->editColumn('arrival_time', fn(Trip $trip) => $trip->formatted_arrival_time)
            ->editColumn('seat_price', fn(Trip $trip) => $trip->formatted_price)
            ->editColumn('available_seats', fn(Trip $trip) => $trip->available_seats . '/' . $trip->total_seats)
            ->editColumn('trip_type', fn(Trip $trip) => $trip->trip_type_label)
            ->addColumn('occupancy', fn(Trip $trip) => 
                '<div class="progress" style="height: 15px;">
                    <div class="progress-bar bg-'.($trip->occupancy_rate >= 90 ? 'danger' : ($trip->occupancy_rate >= 75 ? 'warning' : ($trip->occupancy_rate >= 50 ? 'info' : 'success'))).'" 
                         style="width: '.$trip->occupancy_rate.'%">
                        '.$trip->occupancy_rate.'%
                    </div>
                </div>'
            )
            ->addColumn('action', 'dashboard.trips.action')
            ->setRowId('id')
            ->rawColumns(['action', 'occupancy']);
    }

    public function query(Trip $model): QueryBuilder
    {
        $query = $model->newQuery()->with(['departureCity', 'arrivalCity']);

        // Apply filters from request
        if (request()->filled('trip_type')) {
            $query->where('trip_type', request('trip_type'));
        }

        if (request()->filled('departure_city')) {
            $query->whereHas('departureCity', function($q) {
                $q->where('name', 'like', '%' . request('departure_city') . '%');
            });
        }

        if (request()->filled('arrival_city')) {
            $query->whereHas('arrivalCity', function($q) {
                $q->where('name', 'like', '%' . request('arrival_city') . '%');
            });
        }

        if (request()->filled('travel_date')) {
            $query->whereDate('travel_date', request('travel_date'));
        }

        if (request()->filled('date_from')) {
            $query->whereDate('travel_date', '>=', request('date_from'));
        }

        if (request()->filled('date_to')) {
            $query->whereDate('travel_date', '<=', request('date_to'));
        }

        if (request()->filled('enabled')) {
            $enabled = request('enabled') === '1' ? true : false;
            $query->where('enabled', $enabled);
        }

        if (request()->filled('amenities')) {
            $amenity = request('amenities');
            $query->whereJsonContains('amenities', $amenity);
        }

        if (request()->filled('price_from')) {
            $query->where('seat_price', '>=', request('price_from'));
        }

        if (request()->filled('price_to')) {
            $query->where('seat_price', '<=', request('price_to'));
        }

        if (request()->filled('available_seats')) {
            $query->where('available_seats', '>=', request('available_seats'));
        }

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('data-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Blfrtip')
            ->orderBy(0)
            ->selectStyleSingle()
            ->buttons(array_reverse([
                Button::make('excel')->className('btn btn-sm float-right ms-1 p-1 text-light btn-success'),
                Button::make('csv')->className('btn btn-sm float-right ms-1 p-1 text-light btn-primary'),
                Button::make('print')->className('btn btn-sm float-right ms-1 p-1 text-light btn-secondary'),
                Button::make('reload')->className('btn btn-sm float-right ms-1 p-1 text-light btn-info')
            ]));
    }

    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('enabled'),
            Column::make('trip_type'),
            Column::make('departure_city'),
            Column::make('arrival_city'),
            Column::make('travel_date'),
            Column::make('departure_time'),
            Column::make('arrival_time'),
            Column::make('seat_price'),
            Column::make('available_seats'),
            Column::make('created_at'),
            Column::make('occupancy')->exportable(false)->printable(false),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Trips_' . date('YmdHis');
    }
} 