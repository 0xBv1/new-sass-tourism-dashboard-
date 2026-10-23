<?php

namespace App\DataTables;

use App\Models\Subscribe;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SubscribeDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'dashboard.subscribes.action')
            ->editColumn('is_active', function ($row) {
                $badge = $row->is_active ? 'success' : 'danger';
                $text = $row->is_active ? 'Active' : 'Inactive';
                return '<span class="badge bg-' . $badge . '">' . $text . '</span>';
            })
            ->editColumn('subscribed_at', fn($row) => $row->subscribed_at?->format('Y-m-d H:i'))
            ->rawColumns(['action', 'is_active'])
            ->setRowId('id');
    }

    public function query(Subscribe $model): QueryBuilder
    {
        return $model->newQuery()->latest();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('subscribes-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'desc')
            ->selectStyleSingle();
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('#'),
            Column::make('email')->title('Email'),
            Column::make('name')->title('Name'),
            Column::make('is_active')->title('Status'),
            Column::make('subscribed_at')->title('Subscribed At'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Subscribes_' . date('YmdHis');
    }
}

