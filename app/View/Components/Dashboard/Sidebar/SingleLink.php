<?php

namespace App\View\Components\Dashboard\Sidebar;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SingleLink extends Component
{
    public $title;
    public $link;
    public $icon;
    public $permissions;

    public function __construct($title, $link, $icon = null, $permissions = [])
    {
        $this->title = $title;
        $this->link = $link;
        $this->icon = $icon;
        $this->permissions = $permissions;
    }

    public function render(): View
    {
        return view('components.dashboard.sidebar.single-link', [
            'link' => $this->link,
            'icon' => $this->icon,
            'title' => $this->title,
            'permissions' => $this->permissions,
        ]);
    }
}
