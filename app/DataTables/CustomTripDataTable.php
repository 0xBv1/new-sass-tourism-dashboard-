<?php

namespace App\DataTables;

use App\Models\CustomTrip;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CustomTripDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('created_at', fn(CustomTrip $custom_trip) => $custom_trip->created_at->format('M Y, d'))
            ->editColumn('type', fn(CustomTrip $custom_trip) => $custom_trip->type_name)
            ->editColumn('destination', fn(CustomTrip $custom_trip) => $custom_trip->destination_name)
            ->editColumn('nationality', fn(CustomTrip $custom_trip) => $custom_trip->nationality)
            ->editColumn('name', fn(CustomTrip $custom_trip) => $custom_trip->name)
            ->addColumn('operator', function (CustomTrip $custom_trip) {
                if (!$custom_trip->operator) {
                    return 'Not Assigned Yet';
                }
                return 'Operator: ' . $custom_trip->operator?->name
                    . '<br />' .
                    'Assigned At: ' . $custom_trip->assigned_at->format('M Y, d')
                    . '<br />' .
                    'Assigned By: ' . $custom_trip->assigned_by?->name;
            })
            ->filterColumn('operator', function ($query, $keyword) {
                return $query->whereHas('operator', fn($q) => $q->where('name', 'LIKE', "%$keyword%"));
            })
            ->filterColumn('name', function ($query, $keyword) {
                return $query->where('first_name', 'LIKE', "%$keyword%")->orWhere('last_name', 'LIKE', "%$keyword%");
            })
            ->filterColumn('type', function ($query, $keyword) {
                return $query->where('type', 'LIKE', "%".\Str::snake($keyword)."%");
            })
            ->filterColumn('destination', function ($query, $keyword) {
                return $query->where('destination', 'LIKE', "%".\Str::snake($keyword)."%");
            })
            ->addColumn('action', 'dashboard.custom-trips.action')
            ->setRowId('id')
            ->rawColumns(['action', 'operator']);
    }

    public function query(CustomTrip $model): QueryBuilder
    {
        $q = $model->newQuery()->with('operator');
        if (!auth()->user()->hasRole('Administrator')) {
            $q->where('assigned_operator_id', auth()->id());
        }
        return $q;
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
            Column::make('type'),
            Column::make('destination'),
            Column::make('nationality'),
            Column::make('operator'),
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
        return 'CustomTrip_' . date('YmdHis');
    }
}
