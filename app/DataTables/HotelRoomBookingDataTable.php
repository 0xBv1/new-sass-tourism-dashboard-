<?php

namespace App\DataTables;

use App\Models\HotelRoomBooking;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class HotelRoomBookingDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('hotel', fn($booking) => $booking->hotel?->name)
            ->addColumn('room', fn($booking) => $booking->room?->name)
            ->editColumn('name', fn($booking) => $booking->name) // Client name
            ->editColumn('start_date', fn($booking) => $booking->start_date->format('Y-m-d'))
            ->editColumn('end_date', fn($booking) => $booking->end_date->format('Y-m-d'))
            ->editColumn('created_at', fn($booking) => $booking->created_at?->format('Y-m-d H:i'))
            ->editColumn('extra_beds_count', function($booking) {
                return $booking->extra_beds_count > 0 ? 
                    '<span class="badge bg-info">' . $booking->extra_beds_count . ' Extra Bed(s)</span>' : 
                    '<span class="badge bg-secondary">No Extra Beds</span>';
            })
            ->editColumn('total_price', function($booking) {
                return '$' . number_format($booking->total_price, 2);
            })
            ->editColumn('nights', function($booking) {
                return $booking->start_date->diffInDays($booking->end_date);
            })
            ->addColumn('action', 'dashboard.hotel_room_bookings.action')
            ->setRowId('id')
            ->rawColumns(['action', 'extra_beds_count']);
    }

    public function query(HotelRoomBooking $model): QueryBuilder
    {
        // No longer need to eager load client
        return $model->newQuery()->with(['hotel', 'room']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('data-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Blfrtip')
            ->orderBy(0, 'desc') // Order by ID descending by default
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
            Column::make('name')->title('Client Name'), // Changed from 'client'
            Column::make('hotel')->title('Hotel'),
            Column::make('room')->title('Room'),
            Column::make('start_date')->title('Check-in'),
            Column::make('end_date')->title('Check-out'), 
            Column::make('nights')->title('Nights'),
            Column::make('guests_count')->title('Guests'), 
            Column::make('extra_beds_count')->title('Extra Beds'),
            Column::make('total_price')->title('Total Price'),
            Column::make('status')->title('Status'),
            Column::make('created_at')->title('Booked At'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'HotelRoomBooking_' . date('YmdHis');
    }
}
