<?php

namespace App\DataTables;

use App\Models\BlogComment;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BlogCommentDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($row) {
                return view('dashboard.blog-comments.action', [
                    'id' => $row->id,
                    'parent_id' => $row->parent_id,
                    'is_approved' => $row->is_approved,
                    'is_featured' => $row->is_featured,
                ])->render();
            })
            ->editColumn('blog.title', fn($row) => $row->blog?->title ?? '-')
            ->editColumn('comment', fn($row) => \Str::limit($row->comment, 50))
            ->editColumn('parent_id', function ($row) {
                if ($row->parent_id) {
                    return '<span class="badge bg-info">Reply</span>';
                }
                $repliesCount = $row->replies_count ?? 0;
                return '<span class="badge bg-primary">Comment</span>' . 
                       ($repliesCount > 0 ? ' <span class="badge bg-secondary">' . $repliesCount . ' replies</span>' : '');
            })
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
            ->rawColumns(['action', 'is_approved', 'is_featured', 'parent_id'])
            ->setRowId('id');
    }

    public function query(BlogComment $model): QueryBuilder
    {
        return $model->newQuery()->with(['blog', 'parent'])
            ->withCount('replies')
            ->latest();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('blog-comments-table')
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
            Column::make('blog.title')->title('Blog'),
            Column::make('parent_id')->title('Type'),
            Column::make('comment')->title('Comment'),
            Column::make('is_approved')->title('Status'),
            Column::make('is_featured')->title('Featured'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(150)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'BlogComments_' . date('YmdHis');
    }
}
