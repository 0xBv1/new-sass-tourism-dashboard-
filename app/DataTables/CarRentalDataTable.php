<?php

namespace App\DataTables;

use App\Models\CarRental;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Str;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CarRentalDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('total_price', function (CarRental $carRental) {
                $totalPrice =  $carRental->currency_exchange_rate * ($carRental->car_route_price + $carRental->stops->sum('price'));
                return $carRental->currency?->symbol . $totalPrice;
            })
            ->editColumn('rent_type', fn(CarRental $carRental) => $carRental->rental_type)
            ->editColumn('created_at', fn(CarRental $carRental) => $carRental->created_at->format('M Y, d'))
            ->editColumn('pickup_date', fn(CarRental $carRental) => $carRental->pickup_date->format('d/m/Y'))
            ->editColumn('pickup_time', fn(CarRental $carRental) => $carRental->pickup_time->format('H:i'))
            ->editColumn('pickup', fn(CarRental $carRental) => $carRental->pickup?->name)
            ->editColumn('destination', fn(CarRental $carRental) => $carRental->destination?->name)
            ->addColumn('action', 'dashboard.car-rentals.action')
            ->setRowId('id')
            ->filterColumn('rent_type', function ($query, $term) {
                $query->where('oneway', Str::of($term)->lower()->startsWith(['one', 'way', 'oneway']));
            })
            ->filterColumn('destination', function ($query, $term) {
                $query->whereHas('destination', function ($q) use ($term) {
                    $q->whereTranslationLike('name', "%$term%");
                });
            })
            ->filterColumn('pickup', function ($query, $term) {
                $query->whereHas('pickup', function ($q) use ($term) {
                    $q->whereTranslationLike('name', "%$term%");
                });
            })
            ->rawColumns(['action']);
    }

    public function query(CarRental $model): QueryBuilder
    {
        return $model->newQuery();
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
            Column::make('name'),
            Column::make('pickup'),
            Column::make('destination'),
            Column::make('car_type'),
            Column::make('total_price')->searchable(false)->orderable(false),
            Column::make('rent_type'),
            Column::make('pickup_date'),
            Column::make('pickup_time'),
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
        return 'CarRental_' . date('YmdHis');
    }
}
