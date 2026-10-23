<?php

namespace App\DataTables;

use App\Models\Destination;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DestinationDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('parent', fn(Destination $destination) => $destination->parent?->title ??  'N/A')
            ->editColumn('created_at', fn(Destination $destination) => $destination->created_at->format('M Y, d'))
            ->editColumn('enabled', fn(Destination $destination) => $destination->enabled?'Y':'N')
            ->editColumn('featured', fn(Destination $destination) => $destination->featured?'Y':'N')
            ->addColumn('action', 'dashboard.destinations.action')
            ->editColumn('title', fn(Destination $destination) => $destination->title)
            ->filterTranslatedColumn('title')
            ->orderColumn('title', fn($query, $dir) => $query->orderByTranslation('title', $dir))
            ->editColumn('description', fn(Destination $destination) => $destination->description)
            ->filterTranslatedColumn('description')
            ->orderColumn('description', fn($query, $dir) => $query->orderByTranslation('description', $dir))
            ->editColumn('slug', fn(Destination $destination) => $destination->slug)

            ->orderColumn('slug', fn($query, $dir) => $query->orderByTranslation('slug', $dir))
            ->setRowId('id')
            ->editColumn('featured_image', function (Destination $destination) {
                return '<img class="data-table-img-box" src="' .($destination->featured_image ?? asset('assets/admin/images/placeholders/50x50.png')). '" >';
            })
            ->rawColumns(['action', 'featured_image']);
    }

    public function query(Destination $model): QueryBuilder
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
            Column::make('display_order'),
            Column::make('featured_image')->searchable(false)->orderable(false),
            Column::make('title'),
            Column::make('parent')->searchable(false)->orderable(false),
            Column::make('slug'),
            Column::make('enabled'),
            Column::make('featured'),
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
        return 'Destination_' . date('YmdHis');
    }
}
