<?php

namespace App\DataTables;

use App\Enums\PaymentStatus;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Str;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BookingDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('total_price', fn(Booking $booking) => $booking->currency?->symbol . number_format($booking->total_price*$booking->currency_exchange_rate, 2))
            ->editColumn('currency', fn(Booking $booking) => $booking->currency?->name)
            ->editColumn('type', fn(Booking $booking) => str($booking->type)->headline())
            ->editColumn('payment_method', fn(Booking $booking) => Str::headline($booking->payment_method))
            ->editColumn('payment_status', function(Booking $booking) {
                if ($booking->payment_status == PaymentStatus::PAID->value) {
                    return "<span class='badge badge-success'>".Str::headline($booking->payment_status)."</span>";
                }
                if ($booking->payment_status == PaymentStatus::NOT_PAID->value) {
                    return "<span class='badge badge-primary'>".Str::headline($booking->payment_status)."</span>";
                }
                return "<span class='badge badge-secondary'>".Str::headline($booking->payment_status)."</span>";
            })
            ->editColumn('created_at', fn(Booking $booking) => $booking->created_at->format('M Y, d'))
            ->addColumn('action', 'dashboard.bookings.action')
            ->editColumn('name', fn(Booking $booking) => $booking->first_name . ' ' . $booking->last_name)
            ->filterColumn('name', function ($query, $keyword) {
                return $query->where('first_name', 'LIKE', "%$keyword%")->orWhere('last_name', 'LIKE', "%$keyword%");
            })
            ->setRowId('id')
            ->rawColumns(['action', 'payment_status']);
    }

    public function query(Booking $model): QueryBuilder
    {
        return $model->newQuery()->with(['currency']);
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
            Column::make('type'),
            Column::make('name')->orderable(false),
            Column::make('phone'),
            Column::make('country'),
            Column::make('payment_method'),
            Column::make('payment_status'),
            Column::make('total_price'),
            Column::make('currency')->orderable(false)->searchable(false),
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
        return 'Booking_' . date('YmdHis');
    }
}
