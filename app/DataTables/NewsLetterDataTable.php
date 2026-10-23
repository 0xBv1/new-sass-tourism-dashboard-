<?php

namespace App\DataTables;

use App\Models\NewsLetter;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class NewsLetterDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'dashboard.newsletters.action')
            ->editColumn('status', function ($row) {
                $badges = [
                    'draft' => 'secondary',
                    'scheduled' => 'info',
                    'sending' => 'warning',
                    'sent' => 'success',
                ];
                $badge = $badges[$row->status] ?? 'secondary';
                return '<span class="badge bg-' . $badge . '">' . ucfirst($row->status) . '</span>';
            })
            ->editColumn('sent_at', fn($row) => $row->sent_at?->format('Y-m-d H:i') ?? '-')
            ->addColumn('stats', fn($row) => $row->sent_count . '/' . $row->total_recipients)
            ->rawColumns(['action', 'status'])
            ->setRowId('id');
    }

    public function query(NewsLetter $model): QueryBuilder
    {
        return $model->newQuery()->latest();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('newsletters-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'desc')
            ->selectStyleSingle();
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('#'),
            Column::make('subject')->title('Subject'),
            Column::make('status')->title('Status'),
            Column::make('stats')->title('Sent/Total'),
            Column::make('sent_at')->title('Sent At'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'NewsLetters_' . date('YmdHis');
    }
}

