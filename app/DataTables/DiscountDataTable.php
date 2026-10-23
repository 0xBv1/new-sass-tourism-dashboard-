<?php

namespace App\DataTables;

use App\Models\Discount;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DiscountDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'dashboard.discounts.action')
            ->editColumn('value', function ($row) {
                if ($row->type === 'percentage') {
                    return $row->value . '%';
                }
                return '$' . number_format($row->value, 2);
            })
            ->editColumn('is_active', function ($row) {
                $isCurrentlyActive = $row->isCurrentlyActive();
                $badge = $isCurrentlyActive ? 'success' : 'danger';
                $text = $isCurrentlyActive ? 'Active' : 'Inactive';
                return '<span class="badge bg-' . $badge . '">' . $text . '</span>';
            })
            ->editColumn('start_date', fn($row) => $row->start_date?->format('Y-m-d') ?? '-')
            ->editColumn('end_date', fn($row) => $row->end_date?->format('Y-m-d') ?? '-')
            ->addColumn('tours_count', fn($row) => $row->tours()->count())
            ->rawColumns(['action', 'is_active'])
            ->setRowId('id');
    }

    public function query(Discount $model): QueryBuilder
    {
        return $model->newQuery()->latest();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('discounts-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'desc')
            ->selectStyleSingle();
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('#'),
            Column::make('name')->title('Name'),
            Column::make('type')->title('Type'),
            Column::make('value')->title('Value'),
            Column::make('tours_count')->title('Tours'),
            Column::make('start_date')->title('Start'),
            Column::make('end_date')->title('End'),
            Column::make('is_active')->title('Status'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Discounts_' . date('YmdHis');
    }
}

