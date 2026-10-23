<?php

namespace App\DataTables;

use App\Models\TripBooking;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TripBookingDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('created_at', fn(TripBooking $booking) => $booking->created_at->format('M Y, d H:i'))
            ->addColumn('action', 'dashboard.trip-bookings.partials.action')
            ->editColumn('passenger_name', fn(TripBooking $booking) =>'<a href="'.route('dashboard.trip-bookings.show', $booking).'">'.$booking->passenger_name.'</a>')
            ->editColumn('booking_reference', fn(TripBooking $booking) => '<span class="badge bg-primary">'.$booking->booking_reference.'</span>')
            ->editColumn('total_price', fn(TripBooking $booking) => '<strong>'.$booking->formatted_total_price.'</strong>')
            ->editColumn('number_of_passengers', fn(TripBooking $booking) => $booking->adults_count . ' Adults, ' . $booking->children_count . ' Children')
            ->editColumn('status', fn(TripBooking $booking) => 
                '<span class="badge bg-'.$booking->status_color.'">'.$booking->status_label.'</span>'
            )
            ->editColumn('trip.departure_city_name', fn(TripBooking $booking) => $booking->trip->departure_city_name ?? '-')
            ->editColumn('trip.arrival_city_name', fn(TripBooking $booking) => $booking->trip->arrival_city_name ?? '-')
            ->editColumn('trip.travel_date', fn(TripBooking $booking) => $booking->trip->travel_date->format('M d, Y') ?? '-')
            ->editColumn('client.name', fn(TripBooking $booking) => $booking->client->name ?? '-')
            ->addColumn('price_breakdown', fn(TripBooking $booking) => 
                '<small class="text-muted">'.$booking->formatted_price_breakdown.'</small>'
            )
            ->setRowId('id')
            ->rawColumns(['action','passenger_name', 'booking_reference', 'total_price', 'status', 'price_breakdown']);
    }

    public function query(TripBooking $model): QueryBuilder
    {
        $query = $model->newQuery()->with(['trip.departureCity', 'trip.arrivalCity', 'client']);
        
        // Apply filters
        if (request()->filled('trip_id')) {
            $query->where('trip_id', request('trip_id'));
        }
        
        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }
        
        if (request()->filled('date_from')) {
            $query->whereDate('created_at', '>=', request('date_from'));
        }
        
        if (request()->filled('date_to')) {
            $query->whereDate('created_at', '<=', request('date_to'));
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
            Column::make('booking_reference'),
            Column::make('passenger_name'),
            Column::make('trip.departure_city_name')->title('From'),
            Column::make('trip.arrival_city_name')->title('To'),
            Column::make('trip.travel_date')->title('Travel Date'),
            Column::make('number_of_passengers')->title('Passengers'),
            Column::make('total_price'),
            Column::computed('price_breakdown')->title('Price Breakdown'),
            Column::make('status'),
            Column::make('client.name')->title('Client'),
            Column::make('created_at'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'TripBooking_' . date('YmdHis');
    }
} 