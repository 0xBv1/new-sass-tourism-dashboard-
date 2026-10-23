<?php

namespace App\DataTables;

use App\Models\CarRoute;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CarRouteDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('created_at', fn(CarRoute $carRoute) => $carRoute->created_at->format('M Y, d'))
            ->editColumn('destination_id', fn(CarRoute $carRoute) => $carRoute->destination?->name)
            ->editColumn('pickup_location_id', fn(CarRoute $carRoute) => $carRoute->pickup?->name)
            ->filterColumn('destination_id', function ($query, $term){
                $query->whereHas('destination', function ($q) use ($term){
                    $q->whereTranslationLike('name', "%$term%");
                });
            })
            ->filterColumn('pickup_location_id', function ($query, $term){
                $query->whereHas('pickup', function ($q) use ($term){
                    $q->whereTranslationLike('name', "%$term%");
                });
            })
            ->addColumn('action', 'dashboard.car-routes.action')
            ->setRowId('id')
            ->rawColumns(['action']);
    }

    public function query(CarRoute $model): QueryBuilder
    {
        return $model->newQuery()->with('destination', 'pickup');
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
            Column::make('pickup_location_id')->title('PickUp Location'),
            Column::make('destination_id')->title('Destination Location'),
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
        return 'CarRoute_' . date('YmdHis');
    }
}
