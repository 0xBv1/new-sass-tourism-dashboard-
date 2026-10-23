<?php

namespace App\DataTables;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Str;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BlogDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('categories', fn(Blog $blog) => $blog->categories->implode('title',', '))
            ->filterColumn('categories', function ($q, $term) {
                $q->whereHas('categories', fn($q2) => $q2->whereTranslationLike('title', "%$term%"));
            })
            ->editColumn('created_at', fn(Blog $blog) => $blog->created_at->format('M Y, d'))
            ->editColumn('status', fn(Blog $blog) => Str::headline($blog->status))
            ->addColumn('action', 'dashboard.blogs.action')
            ->addColumn('published_at', fn(Blog $blog) => view('dashboard.blogs.publish', ['blog' => $blog])->render())
            ->editColumn('title', fn(Blog $blog) => $blog->title)
            ->filterTranslatedColumn('title')
            ->orderColumn('title', fn($query, $dir) => $query->orderByTranslation('title', $dir))
            ->editColumn('title', fn(Blog $blog) => '<a href="' . route('dashboard.blogs.edit', $blog) . '"><img alt="Featured Image" class="data-table-img-box" src="' . ($blog->featured_image ?? asset('assets/admin/images/placeholders/50x50.png')) . '" ></a> <a href="' . route('dashboard.blogs.edit', $blog) . '">' . $blog->title . '</a>')
            ->setRowId('id')
            ->rawColumns(['action', 'title', 'published_at']);
    }

    public function query(Blog $model): QueryBuilder
    {
        return $model->newQuery()->with('published_by', 'categories');
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
            Column::make('title'),
            Column::make('categories'),
            Column::make('published_at')->title("Publish")->searchable(false)->orderable(false),
            Column::make('status'),
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
        return 'Blog_' . date('YmdHis');
    }
}
