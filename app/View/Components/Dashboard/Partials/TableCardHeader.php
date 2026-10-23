<?php

namespace App\View\Components\Dashboard\Partials;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Stringable;
use Illuminate\View\Component;

class TableCardHeader extends Component
{

    private Stringable $model;

    public function __construct($model)
    {
        $this->model = \Str::of($model);
    }

    public function render(): View
    {
        return view('components.dashboard.partials.table-card-header', [
            'model' => $this->model
        ]);
    }
}
