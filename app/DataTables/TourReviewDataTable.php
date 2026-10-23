<?php

namespace App\DataTables;

use App\Models\TourReview;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TourReviewDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('created_at', fn(TourReview $tourReview) => $tourReview->created_at->format('M Y, d'))
            ->editColumn('tour', fn(TourReview $tourReview) => $tourReview->tour->title)
            ->setRowId('id')
            ->rawColumns([]);
    }

    public function query(TourReview $model): QueryBuilder
    {
        return $model->newQuery()->with('tour');
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
            Column::make('reviewer_name'),
            Column::make('tour')->orderable(false)->searchable(false),
            Column::make('rate'),
            Column::make('created_at'),

        ];
    }

    protected function filename(): string
    {
        return 'TourReview_' . date('YmdHis');
    }
}
