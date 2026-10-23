<?php

namespace App\DataTables;

use App\Models\Room;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class RoomDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('created_at', fn(Room $room) => $room->created_at->format('M Y, d'))
            ->editColumn('hotel', fn(Room $room) => $room->hotel?->name)
            ->addColumn('action', 'dashboard.rooms.action')
            ->editColumn('name', fn(Room $room) => $room->name)
            ->addColumn('children_count', fn(Room $room) => $room->children()->count())
            ->addColumn('adults_count', fn(Room $room) => $room->adults()->count())
            ->filterColumn('hotel', function ($query, $keyword) {
                $query->whereHas('hotel', function ($query) use ($keyword) {
                    $query->whereTranslationLike('name', "%{$keyword}%");
                });
            })
            ->filterTranslatedColumn('name')
            ->orderColumn('name', fn($query, $dir) => $query->orderByTranslation('name', $dir))
            ->setRowId('id')
            ->rawColumns(['action']);
    }

    public function query(Room $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('data-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Blfrtip')
            //->dom('Bfrtip')
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
            Column::make('name'),
            Column::make('hotel'),
            Column::make('slug'),
            Column::make('enabled'),
            Column::make('bed_count'),
            Column::make('room_type'),
            Column::make('max_capacity'),
            Column::make('night_price'),
            Column::computed('children_info')
                  ->title('Children Pricing')
                  ->render('function(data, type, row) { return row.children_count + " age groups"; }')
                  ->orderable(false)
                  ->searchable(false),
            Column::computed('adults_info')
                  ->title('Adults Pricing')
                  ->render('function(data, type, row) { return row.adults_count + " age groups"; }')
                  ->orderable(false)
                  ->searchable(false),
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
        return 'Room_' . date('YmdHis');
    }
}
