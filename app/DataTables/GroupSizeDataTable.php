<?php

namespace App\DataTables;

use App\Models\GroupSize;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class GroupSizeDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('enabled', fn(GroupSize $size) => $size->enabled ? 'Y' : 'N')
            ->editColumn('created_at', fn(GroupSize $size) => $size->created_at->format('M Y, d'))
            ->addColumn('action', 'dashboard.group-sizes.action')
            ->editColumn('name', fn(GroupSize $size) => $size->name)
            ->filterTranslatedColumn('name')
            ->orderColumn('name', fn($query, $dir) => $query->orderByTranslation('name', $dir))
            ->editColumn('slug', fn(GroupSize $size) => $size->slug)
            ->addColumn('range', fn(GroupSize $size) => $size->min_people && $size->max_people ? "{$size->min_people}-{$size->max_people}" : 'N/A')
            ->setRowId('id')
            ->rawColumns(['action']);
    }

    public function query(GroupSize $model): QueryBuilder
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
            Column::make('slug'),
            Column::make('range')->title('People Range')->searchable(false),
            Column::make('display_order'),
            Column::make('enabled'),
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
        return 'GroupSize_' . date('YmdHis');
    }
}
