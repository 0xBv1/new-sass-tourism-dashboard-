<?php

namespace App\DataTables;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AppointmentDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'dashboard.appointments.action')
            ->editColumn('arrival_date', fn($row) => $row->arrival_date->format('Y-m-d'))
            ->editColumn('departure_date', fn($row) => $row->departure_date->format('Y-m-d'))
            ->editColumn('status', function ($row) {
                $badges = [
                    'pending' => 'warning',
                    'contacted' => 'info',
                    'confirmed' => 'success',
                    'cancelled' => 'danger',
                ];
                $badge = $badges[$row->status] ?? 'secondary';
                return '<span class="badge bg-' . $badge . '">' . ucfirst($row->status) . '</span>';
            })
            ->editColumn('type', fn($row) => ucfirst(str_replace('_', ' ', $row->type)))
            ->addColumn('guests', fn($row) => $row->adults . ' Adults, ' . $row->children . ' Children')
            ->rawColumns(['action', 'status'])
            ->setRowId('id');
    }

    public function query(Appointment $model): QueryBuilder
    {
        return $model->newQuery()->latest();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('appointments-table')
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
            Column::make('email')->title('Email'),
            Column::make('phone')->title('Phone'),
            Column::make('type')->title('Type'),
            Column::make('guests')->title('Guests'),
            Column::make('arrival_date')->title('Arrival'),
            Column::make('departure_date')->title('Departure'),
            Column::make('status')->title('Status'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Appointments_' . date('YmdHis');
    }
}

