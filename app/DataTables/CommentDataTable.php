<?php

namespace App\DataTables;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CommentDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'dashboard.comments.action')
            ->editColumn('tour.title', fn($row) => $row->tour?->title ?? '-')
            ->editColumn('rating', fn($row) => str_repeat('★', $row->rating) . str_repeat('☆', 5 - $row->rating))
            ->editColumn('comment', fn($row) => \Str::limit($row->comment, 50))
            ->editColumn('is_approved', function ($row) {
                $badge = $row->is_approved ? 'success' : 'warning';
                $text = $row->is_approved ? 'Approved' : 'Pending';
                return '<span class="badge bg-' . $badge . '">' . $text . '</span>';
            })
            ->editColumn('is_featured', function ($row) {
                $badge = $row->is_featured ? 'primary' : 'secondary';
                $text = $row->is_featured ? 'Yes' : 'No';
                return '<span class="badge bg-' . $badge . '">' . $text . '</span>';
            })
            ->rawColumns(['action', 'is_approved', 'is_featured'])
            ->setRowId('id');
    }

    public function query(Comment $model): QueryBuilder
    {
        return $model->newQuery()->with('tour')->latest();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('comments-table')
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
            Column::make('tour.title')->title('Tour'),
            Column::make('rating')->title('Rating'),
            Column::make('comment')->title('Comment'),
            Column::make('is_approved')->title('Status'),
            Column::make('is_featured')->title('Featured'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Comments_' . date('YmdHis');
    }
}

