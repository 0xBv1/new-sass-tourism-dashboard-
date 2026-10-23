<?php

namespace App\DataTables;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class EmployeeDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'dashboard.employees.action')
            ->editColumn('image', function ($row) {
                if ($row->image) {
                    return '<img src="' . $row->image_url . '" class="rounded-circle" width="40" height="40" alt="' . $row->name . '">';
                }
                return '<span class="badge bg-secondary">No Image</span>';
            })
            ->editColumn('is_active', function ($row) {
                $badge = $row->is_active ? 'success' : 'danger';
                $text = $row->is_active ? 'Active' : 'Inactive';
                return '<span class="badge bg-' . $badge . '">' . $text . '</span>';
            })
            ->rawColumns(['action', 'is_active', 'image'])
            ->setRowId('id');
    }

    public function query(Employee $model): QueryBuilder
    {
        return $model->newQuery()->ordered();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('employees-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(5, 'asc')
            ->selectStyleSingle();
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('#'),
            Column::make('image')->title('Photo'),
            Column::make('name')->title('Name'),
            Column::make('position')->title('Position'),
            Column::make('email')->title('Email'),
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
        return 'Employees_' . date('YmdHis');
    }
}

