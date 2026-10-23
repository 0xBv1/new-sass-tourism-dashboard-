<?php

namespace App\View\Components\Dashboard\Form;

use App\Models\Seo;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SeoForm extends Component
{
    private ?Seo $seo;

    public function __construct(Seo|null $seo)
    {
        $this->seo = $seo ?? new Seo;
    }

    public function render(): View
    {
        return view('components.dashboard.form.seo-form', [
            'seo' => $this->seo
        ]);
    }
}
