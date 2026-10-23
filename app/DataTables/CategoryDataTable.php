<?php

namespace App\DataTables;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CategoryDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('parent', fn(Category $category) => $category->parent?->title ??  'N/A')
            ->editColumn('enabled', fn(Category $category) => $category->enabled ? 'Y' : 'N')
            ->editColumn('featured', fn(Category $category) => $category->featured ? 'Y' : 'N')
            ->editColumn('created_at', fn(Category $category) => $category->created_at->format('M Y, d'))
            ->addColumn('action', 'dashboard.categories.action')
            ->editColumn('title', fn(Category $category) => $category->title)
            ->filterTranslatedColumn('title')
            ->orderColumn('title', fn($query, $dir) => $query->orderByTranslation('title', $dir))
            ->editColumn('description', fn(Category $category) => $category->description)
            ->filterTranslatedColumn('description')
            ->orderColumn('description', fn($query, $dir) => $query->orderByTranslation('description', $dir))
            ->editColumn('slug', fn(Category $category) => $category->slug)

            ->orderColumn('slug', fn($query, $dir) => $query->orderByTranslation('slug', $dir))
            ->setRowId('id')
            ->editColumn('featured_image', function (Category $category) {
                return '<img class="data-table-img-box" src="' . ($category->featured_image ?? asset('assets/admin/images/placeholders/50x50.png')) . '" >';
            })
            ->rawColumns(['action', 'featured_image']);
    }

    public function query(Category $model): QueryBuilder
    {
        return $model->newQuery()->with('parent');
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
            Column::make('featured_image')->searchable(false)->orderable(false),
            Column::make('title'),
            Column::make('parent')->searchable(false)->orderable(false),
            Column::make('slug'),
            Column::make('tours_count'),
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
        return 'Category_' . date('YmdHis');
    }
}
