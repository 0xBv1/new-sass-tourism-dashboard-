<?php

namespace App\DataTables;

use App\Models\FaqCategory;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class FaqCategoryDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'dashboard.faq-categories.action')
            ->editColumn('name', fn($row) => $row->name ?? '-')
            ->editColumn('is_active', function ($row) {
                $badge = $row->is_active ? 'success' : 'danger';
                $text = $row->is_active ? 'Active' : 'Inactive';
                return '<span class="badge bg-' . $badge . '">' . $text . '</span>';
            })
            ->addColumn('faqs_count', fn($row) => $row->faqs()->count())
            ->rawColumns(['action', 'is_active'])
            ->setRowId('id');
    }

    public function query(FaqCategory $model): QueryBuilder
    {
        return $model->newQuery()->with('translations')->ordered();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('faq-categories-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(3, 'asc')
            ->selectStyleSingle();
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('#'),
            Column::make('name')->title('Name'),
            Column::make('faqs_count')->title('FAQs'),
            Column::make('order')->title('Order'),
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
        return 'FaqCategories_' . date('YmdHis');
    }
}

